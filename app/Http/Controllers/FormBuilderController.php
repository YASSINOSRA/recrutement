<?php

namespace App\Http\Controllers;

use App\Models\FormBuilder;
use App\Models\Forms; // ✅ Import the Forms model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ✅ Import DB for raw queries

class FormBuilderController extends Controller
{
    public function index()
    {
        $forms = FormBuilder::all();

        // ✅ Fetch quiz scores
        $scores = Forms::select(
                'form_id',
                DB::raw('AVG(score) as avg_score'),
                DB::raw('COUNT(id) as attempts')
            )
            ->groupBy('form_id')
            ->get();

        return view('formBuilder.index', compact('forms', 'scores'));
    }

    public function create(Request $request)
    {
        $item = new FormBuilder();
        $item->name = $request->name;
        $item->content = $request->form;
        $item->save();

        return response()->json(['message' => 'Form added successfully'], 201);
    }

    public function editData(Request $request)
    {
        return FormBuilder::find($request->id);
    }

    public function update(Request $request)
    {
        $item = FormBuilder::findOrFail($request->id);
        $item->name = $request->name;
        $item->content = $request->form;
        $item->save();

        return response()->json(['message' => 'Form updated successfully']);
    }

    public function destroy($id)
    {
        \Log::info("Delete request received for form ID: $id");  // ✅ Log request for debugging
    
        try {
            // Find the form by ID and delete it
            $form = FormBuilder::findOrFail($id);
            $form->delete();
    
            return response()->json(['success' => true, 'message' => 'Form deleted successfully!']);
        } catch (\Exception $e) {
            \Log::error("Error deleting form ID: $id, Exception: " . $e->getMessage()); // ✅ Log error
            return response()->json(['success' => false, 'error' => 'Failed to delete form.'], 500);
        }
    }
}
