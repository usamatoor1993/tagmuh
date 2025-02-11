<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|numeric',
            'password' => 'required|confirmed|min:6',
            'user_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'error' => $validator->errors(), 'message' => 'wrong or missing params'], 403);
        }

        $checkNumber = User::where('phone_number', $request->phone_number)->first();
        if ($checkNumber) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'phone number already exists'], 403);
        }
        if ($request->user_type == "Business") {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                // 'business_email' => 'required|email|unique:users,email',
                'phone_number' => 'required|unique:users,phone_number',
                'image' => 'required',
                'cover_photo' => 'required',
                'location' => 'required',
                'business_location' => 'required',
                'category' => 'required',
                'card_issue_date' => 'required',
                'card_expire_date' => 'required',
                'business_name' => 'required',
                'description' => 'required',
                'id_card_front' => 'required',
                'id_card_back' => 'required',
                'business_model' => 'required',
                'business_license' => 'required',
            ]);
            if ($validator->fails()) {
                return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'error' => $validator->errors(), 'message' => 'wrong or missing params'], 403);
            }
           

            if (isset($request->image)) {
                if ($request->hasFile('image')) {
                    $imageName = rand() . time() . '.' . $request->image->extension();

                    $request->image->move(public_path('Profile_Images'), $imageName);
                    $imageName = asset('Profile_Images') . '/' . $imageName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $imageName = null;
            }
            
            if (isset($request->cover_photo)) {
                if ($request->hasFile('cover_photo')) {
                    $coverName = rand() . time() . '.' . $request->cover_photo->extension();
                    $request->cover_photo->move(public_path('cover_photo'), $coverName);
                    $coverName = asset('cover_photo') . '/' . $coverName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $coverName = null;
            }
            if (isset($request->business_license)) {
                if ($request->hasFile('business_license')) {
                    $licenseName = rand() . time() . '.' . $request->business_license->extension();
                    $request->business_license->move(public_path('business_license'), $licenseName);
                    $licenseName = asset('business_license') . '/' . $licenseName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $coverName = null;
            }
            if (isset($request->id_card_front)) {
                if ($request->hasFile('id_card_front')) {
                    $idFrontName = rand() . time() . '.' . $request->id_card_front->extension();
                    $request->id_card_front->move(public_path('id_card'), $idFrontName);
                    $idFrontName = url('id_card') . '/' . $idFrontName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $idFrontName = null;
            }

            if (isset($request->id_card_back)) {
                if ($request->hasFile('id_card_back')) {
                    $idBackName = rand() . time() . '.' . $request->id_card_back->extension();
                    $request->id_card_back->move(public_path('id_card'), $idBackName);
                    $idBackName = url('id_card') . '/' . $idBackName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $idBackName = null;
            }
            $id_card = [
                'front' => $idFrontName,
                'back' => $idBackName,
            ];
            $category_verified=1;
            $id_card = json_encode($id_card, true);
            if(!is_numeric($request->category)){
                $checkCategory=Service::where('name',$request->category)->first();
                if($checkCategory){
                    $category=$checkCategory->id;
                  
                }else{
                    $category=Service::create(['name'=>$request->category]);
                    $category=$category->id;
                    $category_verified=0;
                }
            }else{
                $category=$request->category;
            }
          
            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'business_name' => $request->business_name,
                'email' => $request->email,
                'business_email' => $request->business_email,
                'phone_number' => $request->phone_number,
                'location' => $request->location,
                'business_location' => $request->business_location,
                'category' => $category,
                'card_issue_date' => $request->card_issue_date,
                'card_expire_date' => $request->card_expire_date,
                'description' => $request->description,
                'id_card' => $id_card,
                'user_type' => 'Business',
                'image' =>   $imageName,
                'cover_photo' =>  $coverName,
                'business_model' =>  $request->business_model,
                'business_license' =>  $licenseName,
                'category_verified'=>$category_verified,
                'timings'=>$request->timings,
            ];
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            if(!is_numeric($request->category)){
                Service::where('id',$category)->update([
                    'user_id'=>$user->id,
                ]);
            }
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['email'] = $user->email;
            $user = User::where('email', $request->email)->first();
            // $user['image'] = url('Profile_Images') . '/' . $user['image'];
            $user['id_card'] = json_decode($user['id_card'], true);
            // foreach($user['id_card'] as $id_card){
            //     $id_card['front']=url('id_card') . '/' . $id_card['front'];
            //     $id_card['back']=url('id_card') . '/' . $id_card['back'];
            // }
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'data' => $success, 'message' => 'User registered successfully as driver'], 200);
        }
        if ($request->user_type == "Guest") {
            if (isset($request->image)) {
                if ($request->hasFile('image')) {
                    $imageName = rand() . time() . '.' . $request->image->extension();
                    $request->image->move(public_path('Profile_Images'), $imageName);
                    $imageName = asset('Profile_Images') . '/' . $imageName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $imageName = null;
            }
            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'user_type' => 'Guest',
                'image' => $imageName,
            ];
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['email'] = $user->email;
            $user = User::where('email', $request->email)->first();
            // $user['image'] = url('Profile_Images') . '/' . $user['image'];
            // $user['cover_photo'] = url('cover_photo') . '/' . $user['cover_photo'];
            $user['id_card'] = json_decode($user['id_card'], true);
            // foreach($user['id_card'] as $id_card){
            //     $id_card=url('id_card') . '/' . $id_card;
            // }

            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'data' => $success, 'message' => 'User registered successfully as grage successfully'], 200);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'missing or wrong params', 'errors' => $validator->errors()], 403);
        }
        $check = User::where('email', $request->email)->first();
        if (!$check) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'User not registered'], 403);
        }
        if ($check->status == 1) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'User is  Blocked'], 403);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 0])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
            if($user['id_card']!=null){
            $user['id_card'] = json_decode($user['id_card'], true);
            }
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'data' => $success, 'message' => 'User logged in successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Wrong email or password'], 403);
        }
    }

    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()]);
        }
        $credentials = request()->validate(['email' => 'required|email']);

        $check = User::where('email', $request->email)->first();
        if (!$check) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'User not registered'], 403);
        }

        Password::sendResetLink($credentials);
        return response(['status' => 'success', 'code' => 200, 'message' => 'Reset password link sent on your email id.'], 200);

        // return response()->json(["msg" => 'Reset password link sent on your email id.']);
    }
    public function reset(Request $request)
    {
        $credentials = request()->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["msg" => "Invalid token provided"], 400);
        }

        return response()->json(["msg" => "Password has been successfully changed"]);
    }
    public function getUserById($id)
    {
        if (isset($id)) {
            $check = is_numeric($id);
            if ($check == 1) {
                $user = User::where('id', $id)->first();
                if ($user) {
                    if($user['id_card']!=null){
                        $user['id_card'] = json_decode($user['id_card'], true);
                        }
                    return response(['status' => 'success', 'code' => 200, 'user' => $user, 'message' => 'User details'], 200);
                } else {
                    return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'User not registered'], 403);
                }
            } else {
                return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'id should be numeric'], 403);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'id not set'], 403);
        }
    }
  
}
