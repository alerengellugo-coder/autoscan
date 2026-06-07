<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Enums\OrderPriority;
use App\Models\Enums\OrderStatus;
use App\Models\Enums\ServiceType;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\OrderCheckedIn;
use App\Notifications\OrderDelivered;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class ServiceOrderController extends Controller
{
    private function statusOptions(): array
    {
        return collect(OrderStatus::cases())->map(fn ($s) => ['value' => $s->value, 'label' => $s->label()])->values()->all();
    }

    private function priorityOptions(): array
    {
        return collect(OrderPriority::cases())->map(fn ($p) => ['value' => $p->value, 'label' => $p->label()])->values()->all();
    }

    private function technicianOptions(): array
    {
        return User::technicians()->active()->orderBy('name')->get(['id', 'name'])
            ->map(fn ($t) => ['value' => $t->id, 'label' => $t->name])->values()->all();
    }

    private function statusCounts(): array
    {
        return ServiceOrder::selectRaw('status, count(*) as count')
            ->groupBy('status')->pluck('count', 'status')->toArray();
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ServiceOrder::query()->with(['vehicle', 'client', 'technician']);

        if ($user->isClient()) {
            $query->forClient($user->id);
        } elseif ($user->isTechnician()) {
            $query->forTechnician($user->id);
        }

        if ($request->filled('status')) $query->byStatus($request->input('status'));
        if ($request->filled('priority')) $query->byPriority($request->input('priority'));
        if ($request->filled('technician_id') && $user->isAdmin()) $query->where('technician_id', $request->input('technician_id'));
        if ($request->filled('date_from') && $request->filled('date_to')) $query->byDateRange($request->input('date_from'), $request->input('date_to'));

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%")
                    ->orWhereHas('vehicle', fn ($v) => $v->where('plate', 'like', "%{$s}%")->orWhere('brand', 'like', "%{$s}%"))
                    ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$s}%"));
            });
        }

        $sort = $request->input('sort', 'created_at');
        $dir = $request->input('direction', 'desc');
        $query->{$sort === 'priority' ? 'orderByPriority' : 'orderBy'}($sort, $dir);

        $orders = $query->paginate($request->input('per_page', 15))->withQueryString();

        $page = $user->isTechnician() ? 'technician.orders.index' : 'orders.index';

        return view($page, [
            'orders'               => $orders,
            'status_options'       => $this->statusOptions(),
            'priority_options'     => $this->priorityOptions(),
            'technician_options'   => $user->isAdmin() ? $this->technicianOptions() : [],
            'status_counts'        => $this->statusCounts(),
            'filters'              => $request->only('search', 'status', 'priority', 'technician_id', 'date_from', 'date_to', 'sort', 'direction', 'per_page'),
        ]);
    }

    public function clientOrders(Request $request)
    {
        $user = Auth::user();
        $query = ServiceOrder::query()->with(['vehicle', 'client', 'technician'])->forClient($user->id);
        if ($request->filled('status')) $query->byStatus($request->input('status'));
        $orders = $query->orderByDesc('created_at')->paginate($request->input('per_page', 10))->withQueryString();

        return view('client.orders.index', [
            'orders'       => $orders,
            'status_options' => $this->statusOptions(),
            'filters'      => $request->only('status', 'per_page'),
        ]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $vehicles = $user->isClient()
            ? Vehicle::where('client_id', $user->id)->active()->get()
            : Vehicle::with('client')->active()->orderBy('brand')->get();
        $technicians = $user->isClient() ? [] : User::technicians()->active()->orderBy('name')->get(['id', 'name']);
        $clients = $user->isAdmin() ? User::clients()->active()->orderBy('name')->get(['id', 'name']) : [];

        return view('orders.create', [
            'vehicles'     => $vehicles,
            'technicians'  => $technicians,
            'clients'      => $clients,
            'service_types' => collect(ServiceType::cases())->map(fn ($s) => ['value' => $s->value, 'label' => $s->label()])->values()->all(),
            'priorities'   => $this->priorityOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id'              => ['required', 'exists:vehicles,id'],
            'technician_id'           => ['nullable', 'exists:users,id'],
            'service_type'            => ['required', 'string', 'in:' . implode(',', array_column(ServiceType::cases(), 'value'))],
            'description'             => ['required', 'string', 'max:2000'],
            'diagnosis'               => ['nullable', 'string', 'max:2000'],
            'priority'                => ['required', 'string', 'in:' . implode(',', array_column(OrderPriority::cases(), 'value'))],
            'estimated_cost'          => ['nullable', 'numeric', 'min:0'],
            'estimated_completion_date' => ['nullable', 'date', 'after:today'],
            'notes'                   => ['nullable', 'string', 'max:1000'],
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $validated['client_id'] = $vehicle->client_id;
        $validated['status'] = OrderStatus::Pending->value;
        if (Auth::user()->isClient()) unset($validated['technician_id']);

        $order = ServiceOrder::create($validated);

        // Notify client of check-in
        if ($order->client && $order->client->email) {
            $order->client->notify(new OrderCheckedIn($order));
        }

        $route = Auth::user()->isAdmin() ? 'admin.ordenes.show' : 'technician.orders.show';
        return redirect()->route($route, $order)->with('success', "Orden {$order->order_number} creada exitosamente.");
    }

    public function show(ServiceOrder $serviceOrder)
    {
        Gate::authorize('manage-orders');
        $serviceOrder->load(['vehicle.client', 'client', 'technician', 'reports' => fn ($q) => $q->orderByDesc('report_date'), 'reports.technician', 'quotation']);

        $user = Auth::user();
        $page = match (true) {
            $user->isAdmin() => 'orders.show',
            $user->isTechnician() => 'technician.orders.show',
            default => 'client.orders.show',
        };

        $reports = $serviceOrder->reports ?? collect();

        if ($user->isAdmin()) {
            $statusTimeline = [];
            $statusTimeline[] = [
                'status' => $serviceOrder->status->value,
                'label' => $serviceOrder->status->label(),
                'date' => $serviceOrder->created_at->toIso8601String(),
                'user_name' => $serviceOrder->client?->name ?? 'Sistema',
            ];
            if ($serviceOrder->started_at) {
                $statusTimeline[] = [
                    'status' => OrderStatus::InProgress->value,
                    'label' => OrderStatus::InProgress->label(),
                    'date' => $serviceOrder->started_at->toIso8601String(),
                    'user_name' => $serviceOrder->technician?->name ?? 'Sistema',
                ];
            }
            if ($serviceOrder->completed_at) {
                $statusTimeline[] = [
                    'status' => OrderStatus::Completed->value,
                    'label' => OrderStatus::Completed->label(),
                    'date' => $serviceOrder->completed_at->toIso8601String(),
                    'user_name' => $serviceOrder->technician?->name ?? 'Sistema',
                ];
            }
            if ($serviceOrder->delivered_at) {
                $statusTimeline[] = [
                    'status' => OrderStatus::Delivered->value,
                    'label' => OrderStatus::Delivered->label(),
                    'date' => $serviceOrder->delivered_at->toIso8601String(),
                    'user_name' => $serviceOrder->technician?->name ?? 'Sistema',
                ];
            }
            return view($page, [
                'order' => $serviceOrder,
                'status_timeline' => $statusTimeline,
                'reports' => $reports,
                'status_options' => $this->statusOptions(),
            ]);
        } elseif ($user->isTechnician()) {
            return view($page, [
                'order' => $serviceOrder,
                'reports' => $reports,
                'available_transitions' => [],
            ]);
        } else {
            return view($page, [
                'order' => $serviceOrder,
                'reports' => $reports,
            ]);
        }
    }

    public function updateStatus(Request $request, ServiceOrder $serviceOrder)
    {
        Gate::authorize('manage-orders');
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_column(OrderStatus::cases(), 'value'))],
            'notes'  => ['nullable', 'string', 'max:1000'],
        ]);
        $newStatus = OrderStatus::from($validated['status']);
        if (! $serviceOrder->canTransitionTo($newStatus)) {
            return back()->withErrors(['status' => "No se puede cambiar el estado de '{$serviceOrder->status->label()}' a '{$newStatus->label()}'."]);
        }
        $oldStatus = $serviceOrder->status;

        DB::transaction(function () use ($serviceOrder, $newStatus, $validated) {
            $data = ['status' => $newStatus->value];
            if ($newStatus === OrderStatus::InProgress && ! $serviceOrder->started_at) $data['started_at'] = now();
            if ($newStatus === OrderStatus::Completed && ! $serviceOrder->completed_at) $data['completed_at'] = now();
            if ($newStatus === OrderStatus::Delivered && ! $serviceOrder->delivered_at) $data['delivered_at'] = now();
            $serviceOrder->update($data);
            if (! empty($validated['notes'])) {
                $current = $serviceOrder->notes ?? '';
                $serviceOrder->update(['notes' => trim($current . "\n\n[" . now()->format('d/m/Y H:i') . '] ' . $validated['notes'])]);
            }
        });

        // Notify client of status change
        if ($serviceOrder->client && $serviceOrder->client->email) {
            if ($newStatus === OrderStatus::Delivered) {
                $serviceOrder->client->notify(new OrderDelivered($serviceOrder));
            } else {
                $serviceOrder->client->notify(new OrderStatusUpdated($serviceOrder, $oldStatus, $newStatus));
            }
        }

        return back()->with('success', "Estado actualizado a '{$newStatus->label()}'.");
    }

    public function addReport(Request $request, ServiceOrder $serviceOrder)
    {
        Gate::authorize('manage-orders');
        $validated = $request->validate([
            'report_date'     => ['required', 'date'],
            'description'      => ['required', 'string', 'max:2000'],
            'work_performed'  => ['nullable', 'string', 'max:2000'],
            'labor_hours'      => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'parts_used'       => ['nullable', 'array'],
            'parts_used.*.name'    => ['required_with:parts_used', 'string', 'max:200'],
            'parts_used.*.quantity' => ['required_with:parts_used', 'numeric', 'min:0.01'],
            'findings'        => ['nullable', 'string', 'max:2000'],
            'recommendations' => ['nullable', 'string', 'max:2000'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);
        $validated['service_order_id'] = $serviceOrder->id;
        $validated['technician_id'] = Auth::id();
        $serviceOrder->reports()->create($validated);
        return back()->with('success', 'Informe de servicio agregado exitosamente.');
    }

    public function destroy(ServiceOrder $serviceOrder)
    {
        Gate::authorize('manage-orders');
        if ($serviceOrder->status->isFinal()) {
            return back()->with('error', 'No se puede cancelar una orden completada o entregada.');
        }
        DB::transaction(function () use ($serviceOrder) {
            if ($serviceOrder->vehicle && $serviceOrder->vehicle->isInService()) $serviceOrder->vehicle->update(['status' => 'active']);
            $serviceOrder->update(['status' => OrderStatus::Cancelled->value]);
        });
        return redirect()->route('admin.ordenes.index')->with('success', "Orden {$serviceOrder->order_number} cancelada.");
    }
}
