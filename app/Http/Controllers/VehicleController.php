<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Enums\EngineType;
use App\Models\Enums\TransmissionType;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller: VehicleController
 *
 * Manages vehicle CRUD operations with role-aware access control.
 * - Admin: full access to all vehicles, can add vehicles for any client.
 * - Technician: sees vehicles linked to their assigned service orders.
 * - Client: sees and manages only their own vehicles.
 */
class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles.
     *
     * Returns a paginated list scoped by role:
     *   - Admin: all vehicles in the system.
     *   - Technician: vehicles linked to their assigned service orders.
     *   - Client: only their own vehicles.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();

        $query = Vehicle::query()->with('client');

        // Scope by role
        if ($user->isClient()) {
            $query->where('client_id', $user->id);
        } elseif ($user->isTechnician()) {
            $query->whereHas('serviceOrders', function ($q) use ($user) {
                $q->where('technician_id', $user->id);
            });
        }

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Apply brand filter
        if ($request->filled('brand')) {
            $query->byBrand($request->input('brand'));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Apply sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $vehicles = $query->paginate($request->input('per_page', 15))
            ->withQueryString();

        // Distinct brands for filter dropdown
        $brands = Vehicle::distinct()->orderBy('brand')->pluck('brand');

        return Inertia::render('Vehicles/Index', [
            'vehicles'          => $vehicles,
            'filters'           => $request->only('search', 'brand', 'status', 'sort', 'direction', 'per_page'),
            'brands'            => $brands,
            'availableStatuses' => ['active', 'in_service', 'sold', 'inactive'],
        ]);
    }

    /**
     * Show the form for creating a new vehicle.
     *
     * Admins see a client selection dropdown. Clients create vehicles
     * directly linked to their own account.
     */
    public function create(Request $request): Response
    {
        $user = Auth::user();

        $clients = [];
        if ($user->isAdmin()) {
            $clients = User::clients()->active()->orderBy('name')->get(['id', 'name', 'email']);
        }

        return Inertia::render('Vehicles/Create', [
            'clients'        => $clients,
            'engineTypes'    => EngineType::cases(),
            'transmissions'  => TransmissionType::cases(),
        ]);
    }

    /**
     * Store a newly created vehicle in storage.
     *
     * Validates the input, creates the vehicle, and redirects to the index
     * with a success message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'     => ['required', 'exists:users,id'],
            'brand'         => ['required', 'string', 'max:100'],
            'model'         => ['required', 'string', 'max:100'],
            'year'          => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'plate'         => ['required', 'string', 'max:20', 'unique:vehicles,plate'],
            'color'         => ['nullable', 'string', 'max:50'],
            'vin'           => ['nullable', 'string', 'max:50', 'unique:vehicles,vin'],
            'mileage'       => ['nullable', 'integer', 'min:0'],
            'engine_type'   => ['nullable', 'string', 'in:' . implode(',', array_column(EngineType::cases(), 'value'))],
            'transmission'  => ['nullable', 'string', 'in:' . implode(',', array_column(TransmissionType::cases(), 'value'))],
            'notes'         => ['nullable', 'string', 'max:1000'],
            'status'        => ['nullable', 'string', 'in:active,in_service,sold,inactive'],
        ]);

        $validated['status'] = $validated['status'] ?? 'active';

        $vehicle = Vehicle::create($validated);

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Vehículo registrado exitosamente.');
    }

    /**
     * Display the specified vehicle with its service orders.
     *
     * Authorization: clients can only view their own vehicles;
     * admins and technicians can view any vehicle.
     */
    public function show(Vehicle $vehicle): Response
    {
        Gate::authorize('view', $vehicle);

        $vehicle->load([
            'client',
            'serviceOrders' => function ($query) {
                $query->orderByDesc('created_at');
            },
        ]);

        return Inertia::render('Vehicles/Show', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehicle $vehicle): Response
    {
        Gate::authorize('update', $vehicle);

        $user = Auth::user();

        $clients = [];
        if ($user->isAdmin()) {
            $clients = User::clients()->active()->orderBy('name')->get(['id', 'name', 'email']);
        }

        return Inertia::render('Vehicles/Edit', [
            'vehicle'       => $vehicle,
            'clients'       => $clients,
            'engineTypes'   => EngineType::cases(),
            'transmissions' => TransmissionType::cases(),
        ]);
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);

        $validated = $request->validate([
            'client_id'     => ['required', 'exists:users,id'],
            'brand'         => ['required', 'string', 'max:100'],
            'model'         => ['required', 'string', 'max:100'],
            'year'          => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'plate'         => ['required', 'string', 'max:20', 'unique:vehicles,plate,' . $vehicle->id],
            'color'         => ['nullable', 'string', 'max:50'],
            'vin'           => ['nullable', 'string', 'max:50', 'unique:vehicles,vin,' . $vehicle->id],
            'mileage'       => ['nullable', 'integer', 'min:0'],
            'engine_type'   => ['nullable', 'string', 'in:' . implode(',', array_column(EngineType::cases(), 'value'))],
            'transmission'  => ['nullable', 'string', 'in:' . implode(',', array_column(TransmissionType::cases(), 'value'))],
            'notes'         => ['nullable', 'string', 'max:1000'],
            'status'        => ['nullable', 'string', 'in:active,in_service,sold,inactive'],
        ]);

        $vehicle->update($validated);

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    /**
     * Remove the specified vehicle from storage.
     *
     * Only admins can delete vehicles. Clients cannot.
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        // Prevent deletion if vehicle has active service orders
        if ($vehicle->serviceOrders()->active()->exists()) {
            return back()->with('error', 'No se puede eliminar el vehículo porque tiene órdenes de servicio activas.');
        }

        $vehicle->delete();

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehículo eliminado exitosamente.');
    }

    /**
     * Add a vehicle for a specific client (admin only).
     *
     * Allows an admin to register a vehicle on behalf of any client.
     * Validates input, creates the vehicle, and returns the vehicle data.
     */
    public function addVehicleForClient(Request $request)
    {
        $this->authorize('create', Vehicle::class);

        $validated = $request->validate([
            'client_id'     => ['required', 'exists:users,id'],
            'brand'         => ['required', 'string', 'max:100'],
            'model'         => ['required', 'string', 'max:100'],
            'year'          => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'plate'         => ['required', 'string', 'max:20', 'unique:vehicles,plate'],
            'color'         => ['nullable', 'string', 'max:50'],
            'vin'           => ['nullable', 'string', 'max:50', 'unique:vehicles,vin'],
            'mileage'       => ['nullable', 'integer', 'min:0'],
            'engine_type'   => ['nullable', 'string', 'in:' . implode(',', array_column(EngineType::cases(), 'value'))],
            'transmission'  => ['nullable', 'string', 'in:' . implode(',', array_column(TransmissionType::cases(), 'value'))],
            'notes'         => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['status'] = 'active';

        $vehicle = Vehicle::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Vehículo registrado exitosamente.',
                'vehicle' => $vehicle->load('client'),
            ], 201);
        }

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Vehículo registrado exitosamente para el cliente.');
    }
}
