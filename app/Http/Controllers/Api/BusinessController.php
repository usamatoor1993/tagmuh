<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function createParentAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'eventId' => 'required',
            'comment' => 'required',
            'stars' => 'required|numeric|min:1|max:5',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
    }


    
}
