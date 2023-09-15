<?php

namespace App\Http\Controllers\API;

use App\Models\ChosenOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChosenOptionController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|integer',
            'exam_id' => 'required|integer',
            'question_id' => 'required|integer',
            'chosen_option_id' => 'required|integer',
            'is_correct' => 'required|boolean',
        ]);

        // Create a new ChosenOption record
        $chosenOption = ChosenOption::create($validatedData);

        // You can perform further actions or return a response as needed
        return response()->json([
            'message' => 'Chosen option stored successfully',
            'data' => $chosenOption,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $chosenOption = ChosenOption::findOrFail($id);

        $validatedData = $request->validate([
            'chosen_option_id' => 'required|integer',
            'is_correct' => 'required|boolean',
        ]);

        // Update the chosen option with the new data
        $chosenOption->update($validatedData);

        // You can perform further actions or return a response as needed
        return response()->json([
            'message' => 'Chosen option updated successfully',
            'data' => $chosenOption,
        ]);
    }

    public function show(Request $request)
    {
        $studentId = $request->input('student_id');
        $examId = $request->input('exam_id');
        $questionId = $request->input('question_id');

        $chosenOption = ChosenOption::where([
            'student_id' => $studentId,
            'exam_id' => $examId,
            'question_id' => $questionId,
        ])->first();

        return response()->json($chosenOption);
    }


    public function getCorrectOptionsCount(Request $request)
    {
        $studentId = $request->query('student_id');
        $examId = $request->query('exam_id');

        $correctOptionsCount = ChosenOption::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->where('is_correct', 1)
            ->count();

        return response()->json(['correct_count' => $correctOptionsCount]);
    }
}
