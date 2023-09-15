<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->get();

        if ($roles->count() > 0) {
            return response()->json(
                [
                    'status' => 200,
                    'roles' => $roles,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'roles' => 'No records found',
                ],
                404,
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 422,
                    'errors' => $validator->messages(),
                ],
                422,
            );
        } else {

            $role = Role::create([
                'name' => $request->name
            ]);

            if ($role) {
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'role created successfully',
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'status' => 500,
                        'message' => 'Something went wrong',
                    ],
                    500,
                );
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::find($id);
        if ($role) {
            return response()->json(
                [
                    'status' => 200,
                    'role' => $role,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such role found',
                ],
                404,
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::find($id);
        if ($role) {
            return response()->json(
                [
                    'status' => 200,
                    'role' => $role,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such role found',
                ],
                404,
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:191'
        ]);

        $role = Role::find($id);

        if ($role) {

            // Update the role data
            $role->name = $request->input('name', $role->name);

            $role->save();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Role updated successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'No such role found',
                ],
                500,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Role deleted successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such role found!',
                ],
                404,
            );
        }
    }
}
