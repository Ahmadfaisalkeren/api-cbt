<?php

namespace App\Http\Controllers\API;

use App\Models\StudentExam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StudentExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studentexams = StudentExam::orderBy('id', 'desc')->get();

        if ($studentexams->count() > 0) {
            return response()->json(
                [
                    'status' => 200,
                    'studentexams' => $studentexams,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'studentexams' => 'No records found',
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
            'exam_id' => 'required',
            'student_id' => 'required',
            'started_at' => 'required',
            'completed_at' => 'required',
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
            $studentexam = StudentExam::create([
                'exam_id' => $request->exam_id,
                'student_id' => $request->student_id,
                'started_at' => $request->started_at,
                'completed_at' => $request->completed_at,
            ]);

            if ($studentexam) {
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'studentexam created successfully',
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
        $studentexam = StudentExam::find($id);
        if ($studentexam) {
            return response()->json(
                [
                    'status' => 200,
                    'resu$studentexam' => $studentexam,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such resu$studentexam found',
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
        $studentexam = StudentExam::find($id);
        if ($studentexam) {
            return response()->json(
                [
                    'status' => 200,
                    'resu$studentexam' => $studentexam,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such resu$studentexam found',
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
            'exam_id' => 'nullable',
            'student_id' => 'nullable',
            'started_at' => 'nullable',
            'completed_at' => 'nullable'
        ]);

        $studentexam = StudentExam::find($id);

        if ($studentexam) {
            // Update the studentexam data
            $studentexam->exam_id = $request->input('exam_id', $studentexam->exam_id);
            $studentexam->student_id = $request->input('student_id', $studentexam->student_id);
            $studentexam->started_at = $request->input('started_at', $studentexam->started_at);
            $studentexam->completed_at = $request->input('completed_at', $studentexam->completed_at);

            $studentexam->save();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'StudentExam updated successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'No such studentexam found',
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
        $studentexam = StudentExam::find($id);
        if ($studentexam) {
            $studentexam->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'StudentExam deleted successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such studentexam found!',
                ],
                404,
            );
        }
    }
}
