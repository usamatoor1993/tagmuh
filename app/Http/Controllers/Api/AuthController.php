<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'phNumber' => 'required|numeric',
            'password' => 'required|confirmed|min:6',
            'userType' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'error' => $validator->errors(), 'message' => 'wrong or missing params'], 403);
        }
        $checkNumber = User::where('phoneNumber', $request->phoneNumber)->first();
        if ($checkNumber) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'phone number already exists'], 403);
        }
        if ($request->userType == "Company") {
            $validator = Validator::make($request->all(), [
                'lastName' => 'required',
                'Bemail' => 'required|email|unique:users,email',
                'phoneNumber' => 'required|unique:users,phoneNumber',
                'image' => 'required',
                'coverPhoto' => 'required',
                'location' => 'required',
                'BLocation' => 'required',
                'category' => 'required',
                'cardIssueDate' => 'required',
                'cardExpireDate' => 'required',
            ]);
            if ($validator->fails()) {
                return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'error' => $validator->errors(), 'message' => 'wrong or missing params'], 403);
            }

            if (isset($request->image)) {
                if ($request->hasFile('image')) {
                    $imageName = rand() . time() . '.' . $request->image->extension();

                    $request->image->move(public_path('Profile_Images'), $imageName);
                    // $imageName=asset('Profile_Images').'/'.$imageName;

                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $imageName = null;
            }

            if (isset($request->cover)) {
                if ($request->hasFile('cover')) {
                    $coverName = rand() . time() . '.' . $request->cover->extension();
                    $request->image->move(public_path('coverPhoto'), $coverName);
                    // $imageName=asset('coverPhoto').'/'.$imageName;

                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $imageName = null;
            }

            $data = [
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'Bemail' => $request->Bemail,
                'phNumber' => $request->phNumber,
                'userType' => 'Company',
                'image' => $request->image ?  $imageName : null,
                'coverPhoto' => $request->cover ?  $coverName : null,
            ];

            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['email'] = $user->email;
            $user = User::where('email', $request->email)->first();
            $user['image'] = url('Profile_Images') . '/' . $user['image'];

            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'data' => $success, 'message' => 'User registered successfully as driver'], 200);
        }
        if ($request->userType == "Guest") {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',

            ]);
            if ($validator->fails()) {
                return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'error' => $validator->errors(), 'message' => 'wrong or missing params'], 403);
            }

            if (isset($request->image)) {
                if ($request->hasFile('image')) {
                    $imageName = rand() . time() . '.' . $request->image->extension();

                    $request->image->move(public_path('Profile_Images'), $imageName);
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $imageName = null;
            }
            $data = [
                'firstName' => $request->firstName,
                'userName' => $request->userName,
                'email' => $request->email,
                'phNumber' => $request->phNumber,
                'userType' => 'Guest',
                'image' => $request->image ?  $imageName : null,
            ];
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['email'] = $user->email;
            $user = User::where('email', $request->email)->first();
            $user['image'] = url('Profile_Images') . '/' . $user['image'];
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'data' => $success, 'message' => 'User registered successfully as grage successfully'], 200);
        }
    }
}
