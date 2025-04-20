<?php

namespace App\Http\Controllers\API\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        return User::with('roles', 'permissions')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'nullable|string'
        ]);

        $user = User::create([
            'username'     => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($request->role) {
            $user->assignRole($request->role);
        }

        return response()->json($user, 201);
    }

    public function show($id)
    {
        return User::with('roles', 'permissions')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only(['username', 'email']));

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        if ($request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

        return response()->json($user);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }
}
