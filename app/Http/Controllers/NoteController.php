<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note; // Import the Note model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{

    // public function __construct()
    // {
    // }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'index' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $note = new Note([
            'title' => $request->title,
            'content' => $request->content,
            'index' => $request->index,
        ]);
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not found, Please Sign up '], 404);
        }
        $note->user_id = Auth::user()->id;
        if ($note->save()) {
            return response()->json([
                'message' => 'Note Created Successfully'
            ], 201);
        } else {
            return response()->json([
                'message' => ' Note creation failed '
            ]);
        }
    }

    public function getNotes()
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $notes = $user->notes;
        // relationship set up in the User model

        return response()->json(['notes' => $notes], 200);
    }




    public function updateNote(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Find the note belonging to the authenticated user
        $note = Note::find($id);
        //id is also mentioned in url 
        //and used in primary also but if we use title we have to write in different phase

        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        // Validate the incoming request data for update
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(), 400]);
        }

        // Update the note attributes
        $note->title = $request->title;
        $note->content = $request->content;


        if ($note->save()) {
            return response()->json(['message' => 'Note updated successfully'], 200);
        } else {
            return response()->json(['error' => 'Unable to update note'], 401);
        }
    }

    public function deleteNotes($title)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $note = Note::where('title', $title)->first();
        //id can also be used in url because of primary key relation
        //but if we use title we have to write in different phase
        if (!$note) {
            return response()->json(['message' => 'Note not found']);
        }
        $note->delete();

        return response()->json(['message' => 'Note deleted successfully']);
    }
}
