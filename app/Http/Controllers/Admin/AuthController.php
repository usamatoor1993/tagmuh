<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 403);
        }
        $check = User::where('email', $request->email)->first();
        if (!$check) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'User not registered'], 403);
        }
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
            
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'data' => $success, 'message' => 'Admin logged in successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Wrong email or password'], 403);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(['status' => 'success', 'code' => 200, 'user' => null, 'data' => null, 'message' => 'Admin logged out successfully'], 200);
    }
}
