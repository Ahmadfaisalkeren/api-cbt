<?php

namespace App\Http\Controllers\API;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\ChosenOption;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::orderBy('id', 'desc')->get();

        if ($questions->count() > 0) {
            return response()->json(
                [
                    'status' => 200,
                    'questions' => $questions,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'questions' => 'No records found',
                ],
                404,
            );
        }
    }

    public function fetchQuestionsByExamId($exam_id)
    {
        $questions = Question::where('exam_id', $exam_id)->get();

        return response()->json([
            'status' => 200,
            'questions' => $questions,
        ], 200);
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
            'question' => 'required',
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
            $question = Question::create([
                'exam_id' => $request->exam_id,
                'question' => $request->question,
            ]);

            if ($question) {
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'question created successfully',
                        'question' => $question, // Include the created question in the response
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

    public function storeChosenOption(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
            'question' => 'required',
            // Add any other validation rules you have
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
            $question = Question::create([
                'exam_id' => $request->exam_id,
                'question' => $request->question,
            ]);

            if ($question) {
                // Get the correct option for the newly created question
                $correctOption = $question->correctOption;

                // Compare the chosen option with the correct option
                $chosenOptionId = $request->chosen_option_id; // Adjust this based on your actual input
                $isCorrect = $chosenOptionId === $correctOption->id;

                // Create a new ChosenOption record
                $chosenOption = ChosenOption::create([
                    'student_id' => $request->student_id,
                    'exam_id' => $request->exam_id,
                    'question_id' => $question->id,
                    'chosen_option_id' => $chosenOptionId,
                    'is_correct' => $isCorrect,
                ]);

                if ($chosenOption) {
                    return response()->json(
                        [
                            'status' => 200,
                            'message' => 'Chosen option stored successfully',
                            'chosen_option' => $chosenOption, // Include the created chosen option in the response
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
        $question = Question::find($id);
        if ($question) {
            return response()->json(
                [
                    'status' => 200,
                    'question' => $question,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such question found',
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
        $question = Question::find($id);
        if ($question) {
            return response()->json(
                [
                    'status' => 200,
                    'question' => $question,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such question found',
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
            'question' => 'nullable'
        ]);

        $question = Question::find($id);

        if ($question) {
            // Update the question data
            $question->exam_id = $request->input('exam_id', $question->exam_id);
            $question->question = $request->input('question', $question->question);

            $question->save();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Question updated successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'No such question found',
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
        $question = Question::find($id);
        if ($question) {
            $question->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Question deleted successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such question found!',
                ],
                404,
            );
        }
    }
}
