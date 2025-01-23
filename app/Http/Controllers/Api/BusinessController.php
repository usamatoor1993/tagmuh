<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessPermission;
use App\Models\Company;
use App\Models\Event;
use App\Models\EventReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Report;

class BusinessController extends Controller
{
    public function givePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $permission = BusinessPermission::where('user_id', $request->user_id)->where('company_id', $request->company_id)->first();
        if ($permission) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Permission already given'], 422);
        }
        $permission =BusinessPermission::create([
            'user_id' => $request->user_id,
            'company_id' => $request->company_id ,
            'post' => $request->post ?? 0,
            'chat' => $request->chat ?? 0,
            'group_chat' => $request->group_chat ?? 0,
            'group_create' => $request->group_create ?? 0,
            'ads' => $request->ads ?? 0,
        ]);
        if (!$permission) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Permission not given'], 422);
        }else{
            return response(['status' => 'success', 'code' => 200, 'message' => 'Permission given successfully', 'data' => $permission], 200);
        }
    }

    public function addEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        
    }
        
            

}
