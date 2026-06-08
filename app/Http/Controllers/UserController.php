<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%"));
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('users.index', [
            'users' => $users,
            'filters' => $request->only('search', 'role'),
        ]);
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('No puedes cambiar tu propio estado.');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', $user->is_active ? 'Usuario activado.' : 'Usuario desactivado.');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,technician,client',
            'phone'    => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return back()->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'role'     => 'sometimes|in:admin,technician,client',
            'phone'    => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return back()->with('success', 'Usuario eliminado exitosamente.');
    }
}
