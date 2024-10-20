<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Exception;


class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return response()->json([
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        // dd($data);
        $role = Role::create([
            "name" => $request->input('name'),
            "guard_name" => "web"
        ]);
        if ($request->input('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }
        return response()->json($role, 201);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            // Log::info('data:', $request->all());
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $role_name = $request->only('name');
            Role::where('id', $id)->update($role_name);
            $role = Role::where('id', $id)->first();

            if ($request->input('permission')) {
                $role->syncPermissions($request->input('permission'));
            }

            return response()->json($role);
        } catch (Exception $e) {
            Log::error('Error in update method: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(null, 204);
    }
}