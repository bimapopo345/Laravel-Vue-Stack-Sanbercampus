<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * List all roles (Admin)
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    /**
     * Show single role detail (Admin)
     */
    public function show($id)
    {
        $role = Role::with('users')->find($id);
        if(!$role){
            return response()->json(['message' => 'Role not found.'], 404);
        }
        return response()->json($role, 200);
    }

    /**
     * Create a new role (Admin)
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Create role
        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Role created successfully.', 'role' => $role], 201);
    }

    /**
     * Update a role (Admin)
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json(['message' => 'Role not found.'], 404);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:roles,name,'.$id,
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Update role
        $role->update($request->only(['name']));

        return response()->json(['message' => 'Role updated successfully.', 'role' => $role], 200);
    }

    /**
     * Delete a role (Admin)
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json(['message' => 'Role not found.'], 404);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully.'], 200);
    }
}
