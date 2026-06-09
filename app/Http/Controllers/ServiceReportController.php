<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceReport;
use App\Notifications\NewServiceReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ServiceReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ServiceReport::query()->with(['serviceOrder.vehicle', 'technician']);

        if ($user->isClient()) {
            $query->whereHas('serviceOrder', fn ($q) => $q->where('client_id', $user->id));
        } elseif ($user->isTechnician()) {
            $query->where('technician_id', $user->id);
        }

        if ($request->filled('service_order_id')) $query->where('service_order_id', $request->input('service_order_id'));
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(fn ($q) => $q->where('description', 'like', "%{$s}%")->orWhere('work_performed', 'like', "%{$s}%"));
        }

        $reports = $query->orderByDesc('report_date')->paginate($request->input('per_page', 15))->withQueryString();

        return view('reports.index', [
            'reports' => $reports,
            'filters' => $request->only('search', 'service_order_id', 'per_page'),
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $orders = $user->isTechnician()
            ? ServiceOrder::where('technician_id', $user->id)
                ->with('vehicle')
                ->whereIn('status', ['pending', 'diagnosing', 'in_progress', 'waiting_parts', 'quality_check'])
                ->orderByDesc('created_at')
                ->get()
            : ServiceOrder::with('vehicle')
                ->whereIn('status', ['pending', 'diagnosing', 'in_progress', 'waiting_parts', 'quality_check'])
                ->orderByDesc('created_at')
                ->get();

        return view('technician.reports.create', [
            'orders' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-orders');
        $validated = $request->validate([
            'service_order_id' => 'required|exists:service_orders,id',
            'report_date'      => 'required|date',
            'description'      => 'required|string|max:2000',
            'work_performed'   => 'nullable|string|max:2000',
            'labor_hours'       => 'nullable|numeric|min:0',
            'parts_used'        => 'nullable|array',
            'parts_used.*.name'     => 'required_with:parts_used|string|max:200',
            'parts_used.*.quantity' => 'required_with:parts_used|numeric|min:0.01',
            'findings'         => 'nullable|string|max:2000',
            'recommendations'  => 'nullable|string|max:2000',
            'notes'            => 'nullable|string|max:1000',
        ]);
        $validated['technician_id'] = Auth::id();
        $report = ServiceReport::create($validated);

        // Notify client of new service report
        try {
            $serviceOrder = ServiceOrder::with('client')->find($report->service_order_id);
            if ($serviceOrder && $serviceOrder->client && $serviceOrder->client->email) {
                $serviceOrder->client->notify(new NewServiceReport($report));
            }
        } catch (\Throwable $e) {
            \Log::warning('NewServiceReport notification failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Informe creado.');
    }

    public function show(ServiceReport $serviceReport)
    {
        Gate::authorize('view-reports');
        $serviceReport->load(['serviceOrder.vehicle.client', 'technician']);
        return view('reports.show', ['report' => $serviceReport]);
    }

    public function update(Request $request, ServiceReport $serviceReport)
    {
        Gate::authorize('view-reports');
        $serviceReport->update($request->validate([
            'report_date' => 'required|date', 'description' => 'required|string|max:2000',
            'work_performed' => 'nullable|string', 'labor_hours' => 'nullable|numeric|min:0',
        ]));
        return back()->with('success', 'Informe actualizado.');
    }

    public function destroy(ServiceReport $serviceReport)
    {
        Gate::authorize('delete-reports');
        $serviceReport->delete();
        return back()->with('success', 'Informe eliminado.');
    }
}
