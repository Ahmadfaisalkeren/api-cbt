<?php

namespace App\Http\Controllers\API;

use App\Models\Exam;
use App\Events\SubmitExam;
use App\Models\ExamResult;
use App\Models\ChosenOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamResultController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'exam_id' => 'required|exists:exams,id',
            'score' => 'required|integer',
        ]);

        // Fetch the exam associated with the submitted exam_id
        $exam = Exam::find($request->exam_id);

        if (!$exam) {
            return response()->json([
                'message' => 'Exam not found',
            ], 404);
        }

        $examResult = ExamResult::create([
            'student_id' => $request->student_id,
            'exam_id' => $request->exam_id,
            'score' => $request->score,
        ]);

        if ($examResult) {
            // Now, you have the $exam object, which contains teacher_id
            event(new SubmitExam([
                'student_id' => $examResult->student_id,
                'exam_id' => $examResult->exam_id,
                'score' => $examResult->score,
                'teacher_id' => $exam->teacher_id, // Associate the teacher_id with the event
            ]));
        }

        return response()->json([
            'message' => 'Exam result stored successfully',
            'data' => $examResult
        ]);
    }

    public function getSubmissionStatus(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        $examResult = ExamResult::where('student_id', $request->student_id)
            ->where('exam_id', $request->exam_id)
            ->first();

        $submitted = $examResult !== null;
        $score = $submitted ? $examResult->score : null;

        return response()->json(['submitted' => $submitted, 'score' => $score]);
    }

    public function showExamResults($examId)
    {
        $examResults = ExamResult::with('student')->where('exam_id', $examId)->get();

        return response()->json(['data' => $examResults]);
    }

}
