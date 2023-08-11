<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note; // Import the Note model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class NotesController extends Controller
{
    public function __construct()
    {
    }
    public function create(Request $request)
    {

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'index' => 'required|int'
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(), 401]);
        }
        $note = new Note([
            'title' => $request->title,
            'body' => $request->body,
            'index' => $request->index
        ]);
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }
        $note->user_id = Auth::user()->id;
        if ($note->save()) {
            return response()->json(['message' => 'Note created succesfully'], 201);
        } else {
            return response()->json(['error' => 'Cant create this note']);
        }
    }



    //     public function getNotes(Request $request)
    //     {
    //         if (!auth()->check()) {
    //             return response()->json(['message' => 'Unauthorized user'], 401);
    //         }
    //         $user = auth()->user();
    //         if (!$user) {
    //             return response()->json(['message' => 'user not found'], 404);
    //         }
    //         $notes = $user->note;
    //         return response()->json(['Notes' => $notes]);
    //     }


    //     public function updateNote(Request $request, $id)
    //     {
    //         if (!auth()->check()) {
    //             return response()->json(['message' => 'Unauthrized'], 401);
    //         }
    //         $user = auth()->user();
    //         if (!$user) {
    //             return response()->json(['message' => 'user not found'], 404);
    //         }
    //         $note = Note::find($id);
    //         if (!$note) {
    //             return response()->json(['message' => 'Note not fond']);
    //         }
    //         if ($note->user_id !== $user->id) {
    //             return response()->json(['message' => 'Unauthorized to edit this note'], 403);
    //         }
    //         $validator = Validator::make($request->all(), [
    //             'title' => 'required|string',
    //             'body' => 'required|string',
    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json($validator->errors());
    //         }
    //         $note->update([
    //             'title' => $request->title,
    //             'body' => $request->content,
    //         ]);
    //         return response()->json(['message' => 'Note updated successfully']);
    //     }
    // }








    public function getNotes()
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $notes = $user->notes; // Assuming you have a relationship set up in the User model

        return response()->json(['notes' => $notes], 200);
    }
}
