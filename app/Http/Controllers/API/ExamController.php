<?php

namespace App\Http\Controllers\API;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\NewExamAdded;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teacherId = $request->input('teacher_id');

        $query = Exam::with('studyClass')->with('teacher')
            ->orderBy('id', 'desc');

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        $exams = $query->get();

        if ($exams->count() > 0) {
            return response()->json(
                [
                    'status' => 200,
                    'exams' => $exams,
                ],
                200
            );
        } else {
            // Instead of returning a 404 status, return an empty array
            return response()->json(
                [
                    'status' => 200,
                    'exams' => [],
                ],
                200
            );
        }
    }

    public function getExamsByTeacher($teacherId)
    {
        $exams = Exam::with('teacher')
            ->where('teacher_id', $teacherId)
            ->get();

        if ($exams->count() > 0) {
            return response()->json([
                'status' => 200,
                'exams' => $exams,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No exams found for the teacher',
            ], 404);
        }
    }

    public function getExamName($examId)
    {
        $exam = Exam::find($examId);

        if ($exam) {
            return response()->json([
                'status' => 200,
                'exam_title' => $exam->title,
            ]);
        } else {
            return response([
                'status' => 404,
                'message' => 'No Exam Data Found',
            ]);
        }
    }

    public function getUserExams()
    {
        $user = Auth::user();
        $classId = $user->class_id;

        $exams = Exam::with('teacher')->where('class_id', $classId)->get();

        return response()->json([
            'status' => 200,
            'class_id' => $classId,
            'exams' => $exams,
        ], 200);
    }

    public function getExamsByClass($class_id)
    {
        try {
            $exams = Exam::with('teacher')->where('class_id', $class_id)->get();

            return response()->json(
                [
                    'status' => 200,
                    'exams' => $exams,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'Error fetching exams',
                ],
                500
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
            'title' => 'required|string|max:191',
            'duration' => 'required',
            'teacher_id' => 'required',
            'class_id' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 422,
                    'errors' => $validator->messages(),
                ],
                422
            );
        } else {
            $timeStart = Carbon::parse($request->input('time_start'))->toDateTimeString();
            $timeEnd = Carbon::parse($request->input('time_end'))->toDateTimeString();

            $exam = Exam::create([
                'title' => $request->title,
                'duration' => $request->duration,
                'teacher_id' => $request->teacher_id,
                'class_id' => $request->class_id,
                'time_start' => $timeStart,
                'time_end' => $timeEnd,
            ]);

            if ($exam) {
                event(new NewExamAdded([
                    'exam_id' => $exam->id,
                    'title' => $exam->title,
                    'teacher_id' => $exam->teacher_id,
                    'class_id' => $exam->class_id
                ]));

                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Exam created successfully',
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'status' => 500,
                        'message' => 'Something went wrong',
                    ],
                    500
                );
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $exam = Exam::find($id);
        if ($exam) {
            return response()->json(
                [
                    'status' => 200,
                    'exam' => $exam,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such exam found',
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
        $exam = Exam::find($id);
        if ($exam) {
            return response()->json(
                [
                    'status' => 200,
                    'exam' => $exam,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such exam found',
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
            'title' => 'nullable|string|max:191',
            'duration' => 'nullable',
            'teacher_id' => 'nullable',
            'class_id' => 'nullable',
            'time_start' => 'nullable',
            'time_end' => 'nullable',
        ]);

        $exam = Exam::find($id);

        $timeStart = Carbon::parse($request->input('time_start'))->toDateTimeString();
        $timeEnd = Carbon::parse($request->input('time_end'))->toDateTimeString();

        if ($exam) {
            // Update the exam data
            $exam->title = $request->input('title', $exam->title);
            $exam->duration = $request->input('duration', $exam->duration);
            $exam->teacher_id = $request->input('teacher_id', $exam->teacher_id);
            $exam->class_id = $request->input('class_id', $exam->class_id);
            $exam->time_start = $timeStart;
            $exam->time_end = $timeEnd;

            $exam->save();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Exam updated successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'No such exam found',
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
        $exam = Exam::find($id);
        if ($exam) {
            $exam->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Exam deleted successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such exam found!',
                ],
                404,
            );
        }
    }
}
