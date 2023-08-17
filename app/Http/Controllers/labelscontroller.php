<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Label;
use App\Models\Note;
use App\Models\LabelNote;

class Labelscontroller extends Controller
{
    public function createLabel(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'label' => 'required|string'

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
            'message' => 'label created succesfully',
            'label' => $label
        ], 201);
    }
}