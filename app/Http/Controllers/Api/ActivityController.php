<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function getVerifiedCompany()
    {
        $user = User::where(['isVerified'=> 1,'userType'=>'Company'])->get();
        if ($user->count() > 0) {
            foreach ($user as  $users) {
                $users['idCard'] = json_decode($users['idCard'], true);
            }
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'message' => 'Get User Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Get User Failed']);
        }
    }
    public function getCompanies()
    {
        $user = User::where(['userType'=>'Company'])->get();
        if ($user->count() > 0) {
            foreach ($user as  $users) {
                $users['idCard'] = json_decode($users['idCard'], true);
            }
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'message' => 'Get User Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Get User Failed']);
        }
    }
}
