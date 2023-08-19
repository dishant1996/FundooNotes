<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Label;
use Illuminate\Support\Facades\Validator;
use App\Models\Note;
use App\Models\LabelNote;


class Labelscontroller extends Controller

{
    public function makeLabel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 400);
        }
        $user = auth()->user();

        $label = new Label();
        $label->label = $request->input('label');
        $label->user_id = $user->id;
        $label->save();
        return response()->json([
            'message' => 'Label created successfully',
            'label' => $label,
        ], 201);
    }
    public function addNoteToLabel(Request $request, $label_id, $note_id)
    {
        $user = auth()->user();
        $label = Label::findOrFail($label_id);
        $note = Note::findOrFail($note_id);
        $labelNote = new LabelNote();
        $labelNote->user_id = $user->id;
        $label->notes()->attach($note, ['user_id' => $user->id]);

        return response()->json([
            'message' => 'Note added to label successfully'
        ], 200);
    }
    public function delNoteFromLabel(Request $request, $label_id, $note_id)
    {

        $label = Label::find($label_id);
        $note = Note::find($note_id);

        if (!$label || !$note) {
            return response()->json(['error' => 'Label or note not found'], 404);
        }

        $label->notes()->detach($note);
        return response()->json(['message' => 'Note removed from label successfully'], 200);
    }
    public function delLabel(Request $request, $id)
    {
        $label = Label::find($id);
        if (!$label) {
            return response()->json(['Message' => 'Label Not found'], 404);
        }
        $label->delete();
        return response()->json(['message' => 'Label deleted successfully'], 200);
    }

    public function updateLabel(Request $request, $id)
    {

        $label = Label::find($id);
        if (!$label) {
            return response()->json(['message' => 'Label not found'], 401);
        }
        $request->validate([
            'name' => 'required|string| max:250'
        ]);
        $label->name = $request->input('name');
        $label->save();
        return response()->json(['message' => 'Label Updated Succesfully'], 200);
    }
}
