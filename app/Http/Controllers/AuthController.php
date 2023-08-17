<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\support\facades\Validator;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\validate;
use App\Models\PasswordResetToken;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;


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
            'email' => 'required |email'
        ]);

        $status = Password::sendResetLink($request->only('email'));
        echo $status;
        if ($status === Password::RESET_LINK_SENT) {

            return response()->json(['message ' => 'Password reset link sent to your email'], 200);
        }
        return response()->json(['message' => 'Unable to send reset link'], 400);
    }


    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // $t= Hash::make($request->token);
        echo Hash::make($request->token);

        $passwordReset = PasswordResetToken::where('email', $request->email)->first();

        if (!$passwordReset || Hash::check($request->token, $passwordReset->token)) {
            return response()->json(['message' => "Please provide valid token"], 404);
        }
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return response()->json(['message' => "User not found"], 404);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $passwordReset->delete();
        return response()->json(['message' => "Password changed Succesfully"],200);
    }
}
