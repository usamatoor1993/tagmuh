<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{
    public function reviewCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'companyId' => 'required',
            'comment' => 'required',
            'stars' => 'required|numeric|min:1|max:5',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $checkVendor = User::where('id', $request->companyId)->first();
        if (!$checkVendor) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'vendor not found'], 403);
        }
        $user = auth()->user();
        $checkReviews = Review::where('userId', $user['id'])->where('companyId', $request->companyId)->get();
        if ($checkReviews->count() > 0) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'already reviewed']);
        }
        Review::create(
            [
                'userId' => $user['id'],
                'companyId' => $request->companyId,
                'comment' => $request->comment,
                'stars' => $request->stars,
            ]
        );
        $ratings = $checkVendor['rating'];
        if ($ratings == 0) {
            User::where('id', $request->companyId)->update(['rating' => $request->stars]);
        } else {
            $getTotalRatings = Review::where('companyId', $request->companyId)->get();
            $totalRatings = $getTotalRatings->count() * 5;
            $total = Review::where('companyId', $request->companyId)->sum('stars');
            $getmultiply = $total * 5;
            $average = $getmultiply / $totalRatings;
            User::where('id', $request->companyId)->update(['rating' => $average]);
        }

        return response(['status' => 'success', 'code' => 200, 'message' => 'Vendor reviewed successfully'], 200);
    }

    public function getService(Request $request)
    {
        $service = Service::get();
        if ($service->count() > 0) {
            return response(['status' => 'success', 'code' => 200, 'data' => $service, 'message' => 'Get Services Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403,'data' => null, 'message' => 'Get Services Failed']);
        }
    }
}
