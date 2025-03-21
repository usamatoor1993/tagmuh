<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user_id = Auth::user()->id;
        if (count($request->all()) > 0) {
            $getUser = User::where('id', $user_id)->first();

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
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Cover Image should be file'], 422);
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
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Business licesnse Image should be file'], 422);
                }
            } else {
                $coverName = null;
            }
            if (isset($request->id_card_front)) {
                if ($request->hasFile('id_card_front')) {
                    $idFrontName = rand() . time() . '.' . $request->id_card_front->extension();
                    $request->id_card_front->move(public_path('idCard'), $idFrontName);
                    $idFrontName = url('idCard') . '/' . $idFrontName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Id card front Image should be file'], 422);
                }
            } else {
                $idFrontName = null;
            }

            if (isset($request->id_card_back)) {
                if ($request->hasFile('id_card_back')) {
                    $idBackName = rand() . time() . '.' . $request->id_card_back->extension();
                    $request->id_card_back->move(public_path('idCard'), $idBackName);
                    $idBackName = url('idCard') . '/' . $idBackName;
                } else {
                    return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Id card back Image should be file'], 422);
                }
            } else {
                $idBackName = null;
            }
            if ($getUser['idCard'] != null && isset($request->id_card_back) && isset($request->id_card_front)) {
                $idCardDecode = json_decode($getUser['idCard'], true);
                $idCard = [
                    'front' => $request->id_card_front ? $idFrontName : $idCardDecode['front'],
                    'back' => $request->id_card_back ? $idBackName : $idCardDecode['back'],
                ];
            } elseif (isset($request->id_card_back) && isset($request->id_card_front) && $getUser['idCard'] == null) {
                $idCard = [
                    'front' => $request->id_card_front ? $idFrontName : null,
                    'back' => $request->id_card_back ? $idBackName : null
                ];
            }

            $data = [
                'first_name' => $request->first_name ? $request->first_name  : $getUser['firstName'],
                'last_name' => $request->last_name ? $request->last_name  : $getUser['last_name'],
                'business_name' => $request->business_name ? $request->business_name  : $getUser['business_name'],
                // 'email' => $request->email,
                // 'Bemail' => $request->Bemail,
                'phone_number' => $request->phone_number ? $request->phone_number  : $getUser['phone_number'],
                'location' =>  $request->location ? $request->location  : $getUser['location'],
                'business_location' => $request->business_location ? $request->business_location  : $getUser['location'],
                'password' => $request->password ? bcrypt($request->password) : $getUser['password'],
                // 'category' => $request->category,
                // 'cardIssueDate' => $request->cardIssueDate,
                // 'cardExpireDate' => $request->cardExpireDate,
                'description' => $request->description ? $request->description : $getUser['description'],
                'id_card' => $request->id_card_front ?  $idCard : $getUser['idCard'],
                // 'userType' => 'Company',
                'image' =>   $request->image ? $imageName :  $getUser['image'],
                'cover_photo' => $request->coverPhoto ? $coverName : $getUser['coverPhoto'],
                'business_model' =>  $request->BModel ?  $request->BModel : $getUser['BModel'],
                'business_license' => $request->BLicense ? $licenseName : $getUser['BLicense'],
                'timings' => $request->timings ? $request->timings : $getUser['timings'],
            ];

            User::where('id', $user_id)->update($data);
            $user = User::where('id', $user_id)->first();
            $user['idCard'] = json_decode($user['idCard'], true);
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'message' => 'Profile updated successfully'], 200);
        } else {
            return response(['status' => 'unsuccessful', 'code' => 403, 'message' => 'there should be params in body to access this end-point'], 403);
        }
    }
    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'error' => $validator->errors(), 'message' => 'wrong or missing params'], 403);
        }
        $sender_id = Auth::user()->id;
        $check = Report::where('user_id', $request->user_id)->where('sender_id', $sender_id)->orderBy('created_at', 'desc')->first();
        if ($check && $check->status == 0) {
            return response(['status' => 'unsuccessful', 'code' => 403, 'message' => 'Your last report against this account is still in review by admin'], 403);
        }
        Report::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'sender_id' => $sender_id,
        ]);

        return response(['status' => 'success', 'code' => 200, 'message' => 'Report sent successfully'], 200);
    }
    public function getReports()
    {
        $sender_id = Auth::user()->id;
        $reports = Report::where('sender_id', $sender_id)->orderBy('created_at', 'desc')->get();
        if ($reports->count() == 0) {
            return response(['status' => 'success', 'code' => 403, 'message' => 'No reports found'], 403);
        }
        return response(['status' => 'success', 'code' => 200, 'data' => $reports, 'message' => 'Report list'], 200);
    }

    public function getAllCategories()
    {
        $categories = Category::get();
        if ($categories->count() == 0) {
            return response(['status' => 'success', 'message' => 'No categories found'], 403);
        } else {
            return response(['status' => 'success', 'data' => $categories, 'message' => 'Categories list'], 200);
        }
    }
}
