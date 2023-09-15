<?php

namespace App\Http\Controllers\API;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $options = Option::orderBy('id', 'desc')->get();

        if ($options->count() > 0) {
            return response()->json(
                [
                    'status' => 200,
                    'options' => $options,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'options' => 'No records found',
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
            'options.*.question_id' => 'required',
            'options.*.option_text' => 'required',
            'options.*.is_correct' => 'required',
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
            // Extract the options from the request
            $options = $request->input('options', []);

            // Use a loop to create each option
            foreach ($options as $optionData) {
                Option::create([
                    'question_id' => $optionData['question_id'],
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $optionData['is_correct'],
                ]);
            }

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Options created successfully',
                ],
                200
            );
        }
    }

    public function getOptionsByQuestionId($questionId)
    {
        $options = Option::where('question_id', $questionId)->orderBy('id', 'desc')->get();

        if ($options->count() > 0) {
            return response()->json(
                [
                    'status' => 200,
                    'options' => $options,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'options' => 'Raono Su',
                ],
                404,
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $option = Option::find($id);
        if ($option) {
            return response()->json(
                [
                    'status' => 200,
                    'option' => $option,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such option found',
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
        $option = Option::find($id);
        if ($option) {
            return response()->json(
                [
                    'status' => 200,
                    'option' => $option,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such option found',
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
            'question_id' => 'nullable',
            'option_text' => 'nullable',
            'is_correct' => 'nullable',
        ]);

        $option = Option::find($id);

        if ($option) {
            // Update the option data
            $option->question_id = $request->input('question_id', $option->question_id);
            $option->option_text = $request->input('option_text', $option->option_text);
            $option->is_correct = $request->input('is_correct', $option->is_correct);

            $option->save();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Option updated successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'No such option found',
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
        $option = Option::find($id);
        if ($option) {
            $option->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Option deleted successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No such option found!',
                ],
                404,
            );
        }
    }
}
