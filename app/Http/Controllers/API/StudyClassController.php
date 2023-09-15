<?php

namespace App\Http\Controllers\API;

use App\Models\StudyClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StudyClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studyClass = StudyClass::orderBy('id', 'desc')->get();

        if ($studyClass->count() > 0) {
            return response()->json(
                [
                    'status' => 200,
                    'studyClass' => $studyClass,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'studyClass' => 'No records found',
                ],
                404,
            );
        }
    }

    public function getClassName($classId)
    {
        $class_name = StudyClass::find($classId);

        if ($class_name) {
            return response()->json([
                'status' => 200,
                'class_name' => $class_name->class_name,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Records Found'
            ], 404);
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
            'class_name' => 'required|string|max:191',
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

            $studyClass = StudyClass::create([
                'class_name' => $request->class_name
            ]);

            if ($studyClass) {
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'studyClass created successfully',
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
        $studyClass = StudyClass::find($id);
        if ($studyClass) {
            return response()->json(
                [
                    'status' => 200,
                    'studyClass' => $studyClass,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such studyClass found',
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
        $studyClass = StudyClass::find($id);
        if ($studyClass) {
            return response()->json(
                [
                    'status' => 200,
                    'studyClass' => $studyClass,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such studyClass found',
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
            'class_name' => 'required|string|max:191'
        ]);

        $studyClass = StudyClass::find($id);

        if ($studyClass) {

            // Update the studyClass data
            $studyClass->class_name = $request->input('class_name', $studyClass->class_name);

            $studyClass->save();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Class Name updated successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'No such studyClass found',
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
        $studyClass = StudyClass::find($id);
        if ($studyClass) {
            $studyClass->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Class Name deleted successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such studyClass found!',
                ],
                404,
            );
        }
    }
}
