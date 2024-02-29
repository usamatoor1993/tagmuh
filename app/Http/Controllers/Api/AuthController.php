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
            'phoneNumber' => 'required|numeric',
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
                'firstName' => 'required',
                'lastName' => 'required',
                // 'Bemail' => 'required|email|unique:users,email',
                'phoneNumber' => 'required|unique:users,phoneNumber',
                'image' => 'required',
                'coverPhoto' => 'required',
                'location' => 'required',
                'BLocation' => 'required',
                'category' => 'required',
                'cardIssueDate' => 'required',
                'cardExpireDate' => 'required',
                'BName' => 'required',
                'description' => 'required',
                'idCardFront' => 'required',
                'idCardBack' => 'required',
                'BModel' => 'required',
                'BLicense' => 'required',
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

            if (isset($request->coverPhoto)) {
                if ($request->hasFile('coverPhoto')) {
                    $coverName = rand() . time() . '.' . $request->coverPhoto->extension();
                    $request->coverPhoto->move(public_path('coverPhoto'), $coverName);
                    $coverName = asset('coverPhoto') . '/' . $coverName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $coverName = null;
            }
            if (isset($request->BLicense)) {
                if ($request->hasFile('BLicense')) {
                    $licenseName = rand() . time() . '.' . $request->BLicense->extension();
                    $request->BLicense->move(public_path('BLicense'), $licenseName);
                    $licenseName = asset('BLicense') . '/' . $licenseName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $coverName = null;
            }
            if (isset($request->idCardFront)) {
                if ($request->hasFile('idCardFront')) {
                    $idFrontName = rand() . time() . '.' . $request->idCardFront->extension();
                    $request->idCardFront->move(public_path('idCard'), $idFrontName);
                    $idFrontName = url('idCard') . '/' . $idFrontName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $idFrontName = null;
            }

            if (isset($request->idCardBack)) {
                if ($request->hasFile('idCardBack')) {
                    $idBackName = rand() . time() . '.' . $request->idCardBack->extension();
                    $request->idCardBack->move(public_path('idCard'), $idBackName);
                    $idBackName = url('idCard') . '/' . $idBackName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
                }
            } else {
                $idBackName = null;
            }
            $idCard = [
                'front' => $idFrontName,
                'back' => $idBackName,
            ];
            $categoryVerified=1;
            $idCard = json_encode($idCard, true);
            if(!is_numeric($request->category)){
                $checkCategory=Service::where('name',$request->category)->first();
                if($checkCategory){
                    $category=$checkCategory->id;
                  
                }else{
                    $category=Service::create(['name'=>$request->category]);
                    $category=$category->id;
                    $categoryVerified=0;
                }
            }else{
                $category=$request->category;
            }
          
            $data = [
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'BName' => $request->BName,
                'email' => $request->email,
                'Bemail' => $request->Bemail,
                'phoneNumber' => $request->phoneNumber,
                'location' => $request->location,
                'BLocation' => $request->BLocation,
                'category' => $category,
                'cardIssueDate' => $request->cardIssueDate,
                'cardExpireDate' => $request->cardExpireDate,
                'description' => $request->description,
                'idCard' => $idCard,
                'userType' => 'Company',
                'image' =>   $imageName,
                'coverPhoto' =>  $coverName,
                'BModel' =>  $request->BModel,
                'BLicense' =>  $licenseName,
                'categoryVerified'=>$categoryVerified,
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
            $user['idCard'] = json_decode($user['idCard'], true);
            // foreach($user['idCard'] as $idCard){
            //     $idCard['front']=url('idCard') . '/' . $idCard['front'];
            //     $idCard['back']=url('idCard') . '/' . $idCard['back'];
            // }
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'data' => $success, 'message' => 'User registered successfully as driver'], 200);
        }
        if ($request->userType == "Guest") {
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
                'firstName' => $request->firstName,
                'email' => $request->email,
                'phoneNumber' => $request->phoneNumber,
                'userType' => 'Guest',
                'image' => $imageName,
            ];
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['email'] = $user->email;
            $user = User::where('email', $request->email)->first();
            // $user['image'] = url('Profile_Images') . '/' . $user['image'];
            // $user['coverPhoto'] = url('coverPhoto') . '/' . $user['coverPhoto'];
            $user['idCard'] = json_decode($user['idCard'], true);
            // foreach($user['idCard'] as $idCard){
            //     $idCard=url('idCard') . '/' . $idCard;
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
            if($user['idCard']!=null){
            $user['idCard'] = json_decode($user['idCard'], true);
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
