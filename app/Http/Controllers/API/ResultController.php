<?php

namespace App\Http\Controllers\API;

use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Result::orderBy('id', 'desc')->get();

        if ($results->count() > 0) {
            return response()->json(
                [
                    'status' => 200,
                    'results' => $results,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'results' => 'No records found',
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
            'student_id' => 'required',
            'exam_id' => 'required',
            'score' => 'required',
            'submitted_at' => 'required',
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
            $result = Result::create([
                'student_id' => $request->student_id,
                'exam_id' => $request->exam_id,
                'score' => $request->score,
                'submitted_at' => $request->submitted_at,
            ]);

            if ($result) {
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'result created successfully',
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
        $result = Result::find($id);
        if ($result) {
            return response()->json(
                [
                    'status' => 200,
                    'resu$result' => $result,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such resu$result found',
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
        $result = Result::find($id);
        if ($result) {
            return response()->json(
                [
                    'status' => 200,
                    'resu$result' => $result,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such resu$result found',
                ],
                404,
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'student_id' => 'nullable',
            'exam_id' => 'nullable',
            'score' => 'nullable',
            'submitted_at' => 'nullable'
        ]);

        $result = Result::find($id);

        if ($result) {
            // Update the result data
            $result->student_id = $request->input('student_id', $result->student_id);
            $result->exam_id = $request->input('exam_id', $result->exam_id);
            $result->score = $request->input('score', $result->score);
            $result->submitted_at = $request->input('submitted_at', $result->submitted_at);

            $result->save();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Result updated successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'No such result found',
                ],
                500,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = Result::find($id);
        if ($result) {
            $result->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Result deleted successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such result found!',
                ],
                404,
            );
        }
    }
}
