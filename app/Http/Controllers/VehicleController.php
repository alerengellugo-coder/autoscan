<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Vehicle::query()->with('client');

        // Clients only see their own vehicles
        if ($user->isClient()) {
            $query->where('client_id', $user->id);
        }

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where('plate', 'like', "%{$s}%")->orWhere('brand', 'like', "%{$s}%")->orWhere('model', 'like', "%{$s}%");
        }
        if ($request->filled('brand')) $query->where('brand', $request->input('brand'));
        if ($request->filled('status')) $query->where('status', $request->input('status'));
        $vehicles = $query->orderByDesc('created_at')->paginate($request->input('per_page', 15))->withQueryString();

        $totalVehicles = Vehicle::count();
        $inServiceCount = Vehicle::where('status', 'in_service')->count();

        $page = Auth::user()->isAdmin() ? 'vehicles.index' : 'client.vehicles.index';
        $data = [
            'vehicles' => $vehicles,
            'filters' => $request->only('search', 'brand', 'status', 'per_page'),
        ];
        if (Auth::user()->isAdmin()) {
            $data['total_vehicles'] = $totalVehicles;
            $data['in_service_count'] = $inServiceCount;
        }
        return view($page, $data);
    }

    public function create(Request $request)
    {
        $clients = Auth::user()->isAdmin() ? User::clients()->active()->orderBy('name')->get(['id', 'name']) : [];
        return view('vehicles.create', [
            'clients' => $clients,
            'brands' => Vehicle::select('brand')->distinct()->orderBy('brand')->pluck('brand'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => Auth::user()->isClient() ? 'prohibited' : 'required|exists:users,id',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'plate' => 'required|string|max:20|unique:vehicles',
            'color' => 'nullable|string|max:50',
            'vin' => 'nullable|string|max:50|unique:vehicles',
            'mileage' => 'nullable|integer|min:0',
            'engine_type' => 'nullable|string',
            'transmission' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);
        if (Auth::user()->isClient()) $validated['client_id'] = Auth::id();
        Vehicle::create($validated);
        $route = Auth::user()->isAdmin() ? 'admin.vehiculos.index' : 'client.vehicles.index';
        return redirect()->route($route)->with('success', 'Vehículo registrado.');
    }

    public function show(Vehicle $vehicle)
    {
        // Clients can only view their own vehicles
        if (Auth::user()->isClient() && $vehicle->client_id !== Auth::id()) {
            abort(403, 'No autorizado para ver este vehículo.');
        }

        $vehicle->load(['client', 'serviceOrders' => fn ($q) => $q->latest()]);
        return view('vehicles.show', ['vehicle' => $vehicle]);
    }

    public function edit(Vehicle $vehicle)
    {
        // Clients can only edit their own vehicles
        if (Auth::user()->isClient() && $vehicle->client_id !== Auth::id()) {
            abort(403, 'No autorizado para editar este vehículo.');
        }

        $vehicle->load('client');
        return view('vehicles.edit', ['vehicle' => $vehicle]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        // Clients can only update their own vehicles
        if (Auth::user()->isClient() && $vehicle->client_id !== Auth::id()) {
            abort(403, 'No autorizado para editar este vehículo.');
        }

        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer',
            'plate' => 'required|string|max:20|unique:vehicles,plate,' . $vehicle->id,
            'color' => 'nullable|string',
            'vin' => 'nullable|string|max:50',
            'mileage' => 'nullable|integer|min:0',
            'engine_type' => 'nullable|string',
            'transmission' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $vehicle->update($validated);
        return back()->with('success', 'Vehículo actualizado.');
    }

    /**
     * AJAX: Get vehicles for a specific client (for order/quotation forms).
     */
    public function getClientVehicles(Request $request, $clientId)
    {
        $vehicles = Vehicle::where('client_id', $clientId)
            ->where('status', 'active')
            ->orderBy('brand')
            ->get(['id', 'plate', 'brand', 'model', 'year']);

        return response()->json($vehicles);
    }

    public function addVehicleForClient(Request $request, User $client)
    {
        $validated = $request->validate([
            'brand' => 'required|string', 'model' => 'required|string',
            'year' => 'required|integer', 'plate' => 'required|string|unique:vehicles',
        ]);
        $validated['client_id'] = $client->id;
        Vehicle::create($validated);
        return back()->with('success', 'Vehículo agregado.');
    }
}
