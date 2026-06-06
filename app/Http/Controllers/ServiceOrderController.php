<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Enums\OrderPriority;
use App\Models\Enums\OrderStatus;
use App\Models\Enums\ServiceType;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller: ServiceOrderController
 *
 * Manages service order lifecycle including creation, status transitions,
 * report additions, and cancellation. Provides role-scoped listing with
 * filtering, searching, and pagination.
 */
class ServiceOrderController extends Controller
{
    /**
     * Display a paginated listing of service orders.
     *
     * Supports filters for status, priority, technician, and date range.
     * Scoped by user role:
     *   - Admin: sees all orders.
     *   - Technician: sees orders assigned to them.
     *   - Client: sees their own orders.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();

        $query = ServiceOrder::query()->with(['vehicle', 'client', 'technician']);

        // Scope by role
        if ($user->isClient()) {
            $query->forClient($user->id);
        } elseif ($user->isTechnician()) {
            $query->forTechnician($user->id);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->byStatus($request->input('status'));
        }

        // Apply priority filter
        if ($request->filled('priority')) {
            $query->byPriority($request->input('priority'));
        }

        // Apply technician filter (admin only)
        if ($request->filled('technician_id') && $user->isAdmin()) {
            $query->where('technician_id', $request->input('technician_id'));
        }

        // Apply date range filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange(
                $request->input('date_from'),
                $request->input('date_to')
            );
        }

        // Apply search filter (order number, description)
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_number', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('vehicle', function ($vehicleQ) use ($searchTerm) {
                        $vehicleQ->where('plate', 'like', "%{$searchTerm}%")
                            ->orWhere('brand', 'like', "%{$searchTerm}%")
                            ->orWhere('model', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('client', function ($clientQ) use ($searchTerm) {
                        $clientQ->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Apply sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if ($sortField === 'priority') {
            $query->orderByPriority($sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $orders = $query->paginate($request->input('per_page', 15))
            ->withQueryString();

        // Data for filter dropdowns
        $technicians = [];
        if ($user->isAdmin()) {
            $technicians = User::technicians()->active()->orderBy('name')->get(['id', 'name']);
        }

        return Inertia::render('ServiceOrders/Index', [
            'orders'           => $orders,
            'filters'          => $request->only('search', 'status', 'priority', 'technician_id', 'date_from', 'date_to', 'sort', 'direction', 'per_page'),
            'statuses'         => OrderStatus::cases(),
            'priorities'       => OrderPriority::cases(),
            'technicians'      => $technicians,
        ]);
    }

    /**
     * Display orders for the currently logged-in client.
     *
     * Convenience method for the client-facing order listing page.
     */
    public function clientOrders(Request $request): Response
    {
        $user = Auth::user();

        if (! $user->isClient()) {
            abort(403, 'Solo los clientes pueden acceder a esta vista.');
        }

        $query = ServiceOrder::query()
            ->with(['vehicle', 'client', 'technician'])
            ->forClient($user->id);

        // Apply status filter
        if ($request->filled('status')) {
            $query->byStatus($request->input('status'));
        }

        $orders = $query->orderByDesc('created_at')
            ->paginate($request->input('per_page', 10))
            ->withQueryString();

        return Inertia::render('Client/ServiceOrders/Index', [
            'orders'   => $orders,
            'filters'  => $request->only('status', 'per_page'),
            'statuses' => OrderStatus::cases(),
        ]);
    }

    /**
     * Show the form for creating a new service order.
     *
     * Loads available vehicles, clients, and technicians for the form.
     */
    public function create(Request $request): Response
    {
        $user = Auth::user();

        // Load vehicles based on role
        $vehicles = collect();
        if ($user->isClient()) {
            $vehicles = Vehicle::where('client_id', $user->id)->active()->get();
        } else {
            $vehicles = Vehicle::with('client')->active()->orderBy('brand')->get();
        }

        // Technicians list (not for clients)
        $technicians = [];
        if (! $user->isClient()) {
            $technicians = User::technicians()->active()->orderBy('name')->get(['id', 'name']);
        }

        // Clients list (admin only)
        $clients = [];
        if ($user->isAdmin()) {
            $clients = User::clients()->active()->orderBy('name')->get(['id', 'name']);
        }

        return Inertia::render('ServiceOrders/Create', [
            'vehicles'    => $vehicles,
            'technicians' => $technicians,
            'clients'     => $clients,
            'serviceTypes' => ServiceType::cases(),
            'priorities'  => OrderPriority::cases(),
        ]);
    }

    /**
     * Store a newly created service order in storage.
     *
     * Validates input, sets the client_id based on the vehicle,
     * and auto-generates the order number via the model's boot method.
     */
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

        // If the user is a client, they cannot assign a technician
        if (Auth::user()->isClient()) {
            unset($validated['technician_id']);
        }

        $order = ServiceOrder::create($validated);

        return redirect()
            ->route('service-orders.show', $order)
            ->with('success', "Orden de servicio {$order->order_number} creada exitosamente.");
    }

    /**
     * Display the specified service order with its reports timeline.
     *
     * Loads all related data: vehicle, client, technician, reports,
     * and quotation if exists.
     */
    public function show(ServiceOrder $serviceOrder): Response
    {
        Gate::authorize('view', $serviceOrder);

        $serviceOrder->load([
            'vehicle.client',
            'client',
            'technician',
            'reports' => function ($query) {
                $query->orderByDesc('report_date');
            },
            'reports.technician',
            'quotation',
        ]);

        return Inertia::render('ServiceOrders/Show', [
            'serviceOrder' => $serviceOrder,
            'statuses'     => OrderStatus::cases(),
            'priorities'   => OrderPriority::cases(),
        ]);
    }

    /**
     * Update the status of a service order (technician/admin only).
     *
     * Validates that the status transition is allowed via the model's
     * canTransitionTo() method. Optionally sets timestamps for
     * started_at, completed_at, and delivered_at.
     */
    public function updateStatus(Request $request, ServiceOrder $serviceOrder)
    {
        Gate::authorize('updateStatus', $serviceOrder);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_column(OrderStatus::cases(), 'value'))],
            'notes'  => ['nullable', 'string', 'max:1000'],
        ]);

        $newStatus = OrderStatus::from($validated['status']);

        if (! $serviceOrder->canTransitionTo($newStatus)) {
            return back()->withErrors([
                'status' => "No se puede cambiar el estado de '{$serviceOrder->status->label()}' a '{$newStatus->label()}'.",
            ]);
        }

        DB::transaction(function () use ($serviceOrder, $newStatus, $validated) {
            $updateData = ['status' => $newStatus->value];

            // Set timestamps based on status transitions
            if ($newStatus === OrderStatus::InProgress && ! $serviceOrder->started_at) {
                $updateData['started_at'] = now();
            }

            if ($newStatus === OrderStatus::Completed && ! $serviceOrder->completed_at) {
                $updateData['completed_at'] = now();
            }

            if ($newStatus === OrderStatus::Delivered && ! $serviceOrder->delivered_at) {
                $updateData['delivered_at'] = now();
            }

            $serviceOrder->update($updateData);

            // Append notes if provided
            if (! empty($validated['notes'])) {
                $currentNotes = $serviceOrder->notes ?? '';
                $serviceOrder->update([
                    'notes' => trim($currentNotes . "\n\n[" . now()->format('d/m/Y H:i') . '] ' . $validated['notes']),
                ]);
            }
        });

        return back()->with('success', "Estado de la orden actualizado a '{$newStatus->label()}'.");
    }

    /**
     * Add a service report to the specified service order (technician only).
     *
     * Validates the report data, creates the report linked to the order,
     * and optionally updates the order's diagnosis field.
     */
    public function addReport(Request $request, ServiceOrder $serviceOrder)
    {
        Gate::authorize('addReport', $serviceOrder);

        $validated = $request->validate([
            'report_date'     => ['required', 'date'],
            'description'      => ['required', 'string', 'max:2000'],
            'work_performed'  => ['nullable', 'string', 'max:2000'],
            'labor_hours'      => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'parts_used'       => ['nullable', 'array'],
            'parts_used.*.name'    => ['required_with:parts_used', 'string', 'max:200'],
            'parts_used.*.quantity' => ['required_with:parts_used', 'numeric', 'min:0.01'],
            'parts_used.*.cost'    => ['nullable', 'numeric', 'min:0'],
            'findings'        => ['nullable', 'string', 'max:2000'],
            'recommendations' => ['nullable', 'string', 'max:2000'],
            'images'          => ['nullable', 'array'],
            'images.*'        => ['url'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['service_order_id'] = $serviceOrder->id;
        $validated['technician_id'] = Auth::id();

        $report = $serviceOrder->reports()->create($validated);

        return redirect()
            ->route('service-orders.show', $serviceOrder)
            ->with('success', 'Informe de servicio agregado exitosamente.');
    }

    /**
     * Remove the specified service order (cancel it).
     *
     * Only admin can cancel orders. This sets the status to 'cancelled'.
     */
    public function destroy(ServiceOrder $serviceOrder)
    {
        $this->authorize('delete', $serviceOrder);

        if ($serviceOrder->status->isFinal()) {
            return back()->with('error', 'No se puede cancelar una orden que ya está completada o entregada.');
        }

        DB::transaction(function () use ($serviceOrder) {
            // Restore vehicle status if it was in_service
            if ($serviceOrder->vehicle && $serviceOrder->vehicle->isInService()) {
                $serviceOrder->vehicle->update(['status' => 'active']);
            }

            $serviceOrder->update(['status' => OrderStatus::Cancelled->value]);
        });

        return redirect()
            ->route('service-orders.index')
            ->with('success', "Orden de servicio {$serviceOrder->order_number} cancelada exitosamente.");
    }
}
