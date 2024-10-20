<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return response()->json([
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|size:10|regex:/^[6789]\d{9}$/', 
            'description' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
        ], [
            'phone.regex' => 'Please enter valid indian number',
        ]);

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('images', 'public');
        }

        $user = User::create(array_merge($request->all(), ['profile_image' => $profileImagePath]));
        if ($request->input('roles')) {
            $user->syncRoles($request->input('roles'));
        }
        return response()->json($user, 201);
    }
    
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        return response()->json([
            'roles' => $roles,
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|size:10|regex:/^[6789]\d{9}$/', 
            'description' => 'nullable|string',
        ], [
            'phone.regex' => 'Please enter valid indian number',
        ]);
        // dd($request->all());

        $user = User::findOrFail($id);
        $user->update($validatedData);
        if ($request->input('role')) {
            $user->syncRoles($request->input('role'));
        }
        if ($request->hasFile('profile_image')) {
            $user->profile_image = $request->file('profile_image')->store('images', 'public');
            $user->save();
        }

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}