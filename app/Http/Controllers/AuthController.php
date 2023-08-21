<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\support\facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\validate;
use App\Models\PasswordResetTokens;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function demo(Request $request)
    {
        echo "hello";
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'age' => 'required|integer',
            'password' => 'required| string|max:100|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(), 400]);
        }

        $user = User::Create([

            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'registered succesfully', 'user' => $user
        ]);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials '], 401);
        }
        $token = Auth::fromUser(Auth::user());
        return response()->json(compact('token'));
    }

    public function logout()
    {
        if (Auth::check()) {

            Auth::logout();

            return response()->json(['message' => 'Logout successful']);
        }

        return response()->json(['message' => 'User is not logged in']);
    }

    public function userDetails()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'user is Unauthorised']);
        }
        return response()->json(['user' => $user], 200);
    }


    public function verifyEmail()
    {
        $id = request('id');
        $token = request('token');
        $user = User::where("verifytoken", $token)->first();
        //    / $user = User::where("email", $email)->first();
        if (!$user) {
            return response()->json(['message' => "Not a Registered Email"], 200);
        } else if ($user->email_verified_at === null) {
            $user->email_verified_at = now();
            $user->save();
            return response()->json(['message' => "Email Successfully Verified"], 201);
        } else {
            return response()->json(['message' => "Email Already Verified"], 202);
        }
    }


    public function forgotPassword(Request $request)
    {

        $request->validate([

            'user_id' => 'required|integer',
            //'email' => 'required |email'
        ]);

        $user = User::find($request->input('user_id'));

        if (!$user) {

            return response()->json(['message' => 'User not found'], 404);
        }

        $status = Password::sendResetLink(['email' => $user->email]);
        if ($status === Password::RESET_LINK_SENT) {

            DB::table('password_reset_tokens')
                ->where('email', $user->email)->update(['user_id' => $user->id]);

            return response()->json(['message' => 'Password reset link sent to your email'], 200);
        } elseif ($status === Password::INVALID_USER) {
            return response()->json(['message' => 'Invalid email address'], 400);
        } elseif ($status === Password::RESET_THROTTLED) {
            return response()->json(['message' => 'Password reset request throttled'], 429);
        } else
            return response()->json(['message' => 'Unable to send reset link'], 400);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            //'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        //$user_id = $request->user_id;

        $resetToken = PasswordResetTokens::where('user_id', $request->user_id)->first();

        echo $resetToken;

        if (!$resetToken) {
            return response()->json(['message' => 'Invalid token'], 400);
        }
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['message' => 'Unauthroized User'], 404);
        }
        $user->update(['password' => bcrypt($request->password)]);

        $resetToken->delete();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }
}
