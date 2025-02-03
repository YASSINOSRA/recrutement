<?php

namespace App\Http\Controllers;

use App\Models\FormBuilder;
use App\Models\Forms;
use Illuminate\Http\Request;

class FormsController extends Controller
{
    // Method to save form data and calculate score
    public function create(Request $request)
    {
        // Get form ID and remove unnecessary fields
        $formID = $request->form_id;
        $request->request->remove('_token');
        $request->request->remove('form_id');

        // Capture the user name and all form data
        $userName = $request->user_name;
        $allData = $request->all();

        // Fetch the form builder content (question definitions)
        $formBuilder = FormBuilder::findOrFail($formID);
        $formContent = json_decode($formBuilder->content, true);

        // Define correct answers and their values based on form content
        $correctAnswers = [];

        // Loop through each form field and set correct answer values
        foreach ($formContent as $field) {
            if (isset($field['values']) && is_array($field['values'])) {
                foreach ($field['values'] as $option) {
                    // Store the correct answers based on predefined value (e.g., 1 for correct)
                    if (isset($option['value']) && $option['value'] === "1") {
                        $correctAnswers[$field['name']] = $option['value']; // Correct answer key-value
                    }
                }
            }
        }

        // Debugging: Log the correct answers for verification

        // Calculate score by comparing user selections with the correct answers
        $score = 0;

        // Loop through the user's submitted answers
        foreach ($allData as $key => $value) {
            // If the answer is an array (select with multiple values), compare each value
            if (is_array($value)) {
                foreach ($value as $v) {
                    // Debugging: Log user answers and check for matches

                    // Check if the user answer matches the correct answer
                    if (isset($correctAnswers[$key]) && $correctAnswers[$key] == $v) {
                        $score++;  // Increment score for correct answer
                    }
                }
            } else {
                // For single values (radio-group, etc.), just compare directly
                // Debugging: Log user answers and check for matches

                // Check if the user answer matches the correct answer
                if (isset($correctAnswers[$key]) && $correctAnswers[$key] == $value) {
                    $score++;  // Increment score for correct answer
                }
            }
        }

        // Save the form response, including the calculated score and user_name
        \DB::table('forms')->insert([
            'form_id' => $formID,
            'form' => json_encode($allData),
            'score' => $score,
            'user_name' => $userName,  // Save the user's name
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return a success message with the score
        return redirect()->route('thank');
        }

    // Method to get user scores
    public function getUserScores()
    {
        // Join the forms and form_builders tables to get quiz name and user score
        $scores = \DB::table('forms')
            ->join('form_builders', 'forms.form_id', '=', 'form_builders.id')  // Join with form_builders to get quiz name
            ->select(
                'form_builders.name as quiz_name',  // Fetch quiz name
                'forms.score',                      // Fetch individual score
                'forms.user_name'                   // Fetch user_name
            )
            ->groupBy('forms.form_id', 'forms.user_name', 'quiz_name', 'forms.score')
            ->get();

        // Debugging: Log the retrieved scores

        return view('scores.index', compact('scores'));
    }
    public function read(Request $request)
    {
        $item = FormBuilder::find($request->id);  // Using find() instead of findOrFail() to prevent errors.
        if (!$item) {
            return response()->json(['error' => 'Form not found'], 404);
        }
        return response()->json($item);
    }
}
