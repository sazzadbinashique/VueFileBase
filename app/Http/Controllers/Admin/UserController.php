<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->withCount(['donations' => fn($q) => $q->where('status', 'completed')]);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->role) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $sortBy = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';
        $query->orderBy(in_array($sortBy, ['name', 'email', 'created_at', 'role']) ? $sortBy : 'created_at', $sortDir === 'asc' ? 'asc' : 'desc');

        $perPage = min((int)($request->per_page ?? 15), 100);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string|exists:roles,name',
            'status' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
            'status' => $request->boolean('status', true),
        ]);

        if ($request->role) {
            $role = \App\Models\Role::where('name', $request->role)->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }

        return response()->json($user->load('roles'), 201);
    }

    public function show($id)
    {
        $user = User::with(['roles', 'donations' => fn($q) => $q->with('project')->orderBy('created_at', 'desc')])
            ->withCount(['donations' => fn($q) => $q->where('status', 'completed')])
            ->findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => 'nullable|string|exists:roles,name',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->only('name', 'email', 'status');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->has('role')) {
            $role = \App\Models\Role::where('name', $request->role)->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
                $user->role = $request->role;
                $user->save();
            }
        }

        return response()->json($user->load('roles'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return response()->json(['message' => 'Cannot delete admin user.'], 422);
        }

        $user->delete();
        return response()->json(null, 204);
    }
}
