<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller: ServiceReportController
 *
 * Manages service reports linked to service orders.
 * Technicians create and update reports; admins have full access;
 * clients have read-only access to their order reports.
 */
class ServiceReportController extends Controller
{
    /**
     * Display a listing of service reports.
     *
     * Optionally filter by service order or date range.
     * Scoped by role: admins see all, technicians see their own,
     * clients see reports for their orders.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();

        $query = ServiceReport::query()->with(['serviceOrder.vehicle', 'technician']);

        // Scope by role
        if ($user->isClient()) {
            $query->whereHas('serviceOrder', function ($q) use ($user) {
                $q->where('client_id', $user->id);
            });
        } elseif ($user->isTechnician()) {
            $query->byTechnician($user->id);
        }

        // Filter by service order
        if ($request->filled('service_order_id')) {
            $query->forOrder($request->integer('service_order_id'));
        }

        // Filter by date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange(
                $request->input('date_from'),
                $request->input('date_to')
            );
        }

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', "%{$searchTerm}%")
                    ->orWhere('work_performed', 'like', "%{$searchTerm}%")
                    ->orWhere('findings', 'like', "%{$searchTerm}%");
            });
        }

        $reports = $query->latestFirst()
            ->paginate($request->input('per_page', 15))
            ->withQueryString();

        return Inertia::render('ServiceReports/Index', [
            'reports'  => $reports,
            'filters'  => $request->only('search', 'service_order_id', 'date_from', 'date_to', 'per_page'),
        ]);
    }

    /**
     * Store a newly created service report.
     *
     * Validates input, creates the report linked to the specified
     * service order, and sets the technician to the logged-in user.
     * Dispatches a notification to the client.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        Gate::authorize('create', ServiceReport::class);

        $validated = $request->validate([
            'service_order_id' => ['required', 'exists:service_orders,id'],
            'report_date'      => ['required', 'date'],
            'description'      => ['required', 'string', 'max:2000'],
            'work_performed'   => ['nullable', 'string', 'max:2000'],
            'labor_hours'       => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'parts_used'        => ['nullable', 'array'],
            'parts_used.*.name'     => ['required_with:parts_used', 'string', 'max:200'],
            'parts_used.*.quantity' => ['required_with:parts_used', 'numeric', 'min:0.01'],
            'parts_used.*.cost'    => ['nullable', 'numeric', 'min:0'],
            'findings'         => ['nullable', 'string', 'max:2000'],
            'recommendations'  => ['nullable', 'string', 'max:2000'],
            'images'           => ['nullable', 'array'],
            'images.*'         => ['url'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        $serviceOrder = ServiceOrder::findOrFail($validated['service_order_id']);

        Gate::authorize('addReport', $serviceOrder);

        $validated['technician_id'] = $user->id;

        $report = ServiceReport::create($validated);

        // Notify the client about the new report
        if ($serviceOrder->client) {
            $serviceOrder->client->notify(
                new \App\Notifications\ServiceReportAdded($serviceOrder, $report)
            );
        }

        return redirect()
            ->route('service-reports.show', $report)
            ->with('success', 'Informe de servicio creado exitosamente.');
    }

    /**
     * Display the specified service report.
     */
    public function show(ServiceReport $serviceReport): Response
    {
        Gate::authorize('view', $serviceReport);

        $serviceReport->load([
            'serviceOrder' => function ($query) {
                $query->with(['vehicle', 'client', 'technician']);
            },
            'technician',
        ]);

        return Inertia::render('ServiceReports/Show', [
            'report' => $serviceReport,
        ]);
    }

    /**
     * Update the specified service report.
     *
     * Only the original technician or an admin may update the report.
     */
    public function update(Request $request, ServiceReport $serviceReport)
    {
        Gate::authorize('update', $serviceReport);

        $validated = $request->validate([
            'report_date'      => ['required', 'date'],
            'description'      => ['required', 'string', 'max:2000'],
            'work_performed'   => ['nullable', 'string', 'max:2000'],
            'labor_hours'       => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'parts_used'        => ['nullable', 'array'],
            'parts_used.*.name'     => ['required_with:parts_used', 'string', 'max:200'],
            'parts_used.*.quantity' => ['required_with:parts_used', 'numeric', 'min:0.01'],
            'parts_used.*.cost'    => ['nullable', 'numeric', 'min:0'],
            'findings'         => ['nullable', 'string', 'max:2000'],
            'recommendations'  => ['nullable', 'string', 'max:2000'],
            'images'           => ['nullable', 'array'],
            'images.*'         => ['url'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        $serviceReport->update($validated);

        return redirect()
            ->route('service-reports.show', $serviceReport)
            ->with('success', 'Informe de servicio actualizado exitosamente.');
    }

    /**
     * Remove the specified service report from storage.
     *
     * Only the original technician or an admin may delete reports.
     */
    public function destroy(ServiceReport $serviceReport)
    {
        Gate::authorize('delete', $serviceReport);

        $serviceReport->delete();

        return redirect()
            ->route('service-reports.index')
            ->with('success', 'Informe de servicio eliminado exitosamente.');
    }
}
