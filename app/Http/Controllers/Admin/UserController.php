<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kaprodi;
use App\Models\Admin;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'kaprodi'])
                      ->with(['kaprodi.prodi'])
                      ->get();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $prodis = Prodi::all();
        return view('admin.user.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'kaprodi'])],
            'prodi_id' => 'nullable|exists:prodis,id',
        ]);

        if ($request->role === 'kaprodi') {
            $request->validate([
                'prodi_id' => 'required',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'kaprodi') {
            Kaprodi::create([
                'user_id' => $user->id,
                'nama' => $request->name,
                'email' => $request->email,
                'prodi_id' => $request->prodi_id,
            ]);
        } elseif ($request->role === 'admin') {
            Admin::create([
                'user_id' => $user->id,
                'nama' => $request->name,
                'email' => $request->email,
                // Add other admin specific fields if necessary
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if (!in_array($user->role, ['admin', 'kaprodi'])) {
            abort(403, 'Unauthorized action.');
        }
        $prodis = Prodi::all();
        // Load the related Kaprodi model if the user is a kaprodi
        if ($user->role === 'kaprodi') {
            $user->load('kaprodi');
        }
        return view('admin.user.edit', compact('user', 'prodis'));
    }

    public function update(Request $request, User $user)
    {
        if (!in_array($user->role, ['admin', 'kaprodi'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'kaprodi'])],
            'prodi_id' => 'nullable|exists:prodis,id',
        ]);

        if ($request->role === 'kaprodi') {
            $request->validate([
                'prodi_id' => 'required',
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->save();

        // Handle associated Kaprodi or Admin model updates/creations/deletions
        if ($user->role === 'kaprodi') {
            Kaprodi::updateOrCreate(
                ['user_id' => $user->id],
                ['nama' => $user->name, 'email' => $user->email, 'prodi_id' => $request->prodi_id]
            );
            Admin::where('email', $user->email)->delete(); // Delete if role changed from admin
        } elseif ($user->role === 'admin') {
            Admin::updateOrCreate(
                ['email' => $user->email],
                ['nama' => $user->name]
            );
            Kaprodi::where('user_id', $user->id)->delete(); // Delete if role changed from kaprodi
        } else {
            // If role changed to something else (shouldn't happen with current validation)
            Kaprodi::where('user_id', $user->id)->delete();
            Admin::where('email', $user->email)->delete();
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (!in_array($user->role, ['admin', 'kaprodi'])) {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated Kaprodi or Admin model first
        if ($user->role === 'kaprodi') {
            Kaprodi::where('user_id', $user->id)->delete();
        } elseif ($user->role === 'admin') {
            Admin::where('email', $user->email)->delete();
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
