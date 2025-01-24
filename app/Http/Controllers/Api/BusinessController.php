<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessPermission;
use App\Models\Company;
use App\Models\Event;
use App\Models\EventReview;
use App\Models\PortfolioAd;
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
        $permission = BusinessPermission::create([
            'user_id' => $request->user_id,
            'company_id' => $request->company_id,
            'post' => $request->post ?? 0,
            'chat' => $request->chat ?? 0,
            'group_chat' => $request->group_chat ?? 0,
            'group_create' => $request->group_create ?? 0,
            'ads' => $request->ads ?? 0,
        ]);
        if (!$permission) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Permission not given'], 422);
        } else {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Permission given successfully', 'data' => $permission], 200);
        }
    }

    public function addEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'email' => 'required|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user->user_type == 'Employee') {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Employee already added'], 422);
        }
        $user = User::where('id', $user->id)->update(['company_id' => $request->company_id, 'user_type' => 'Employee']);
        if ($user == 1) {
            $user = User::where('id', $request->user_id)->first();
            return response(['status' => 'success', 'code' => 200, 'data' => $user, 'message' => 'Employee added successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Employee not added'], 422);
        }
    }

    public function removeEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'email' => 'required|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $user = User::where(['email' => $request->email, 'company_id' => $request->company_id])->first();
        if (!$user) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Employee not found'], 422);
        }
        $user = User::where('id', $user->id)->update(['company_id' => null, 'user_type' => 'Guest']);
        if ($user == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Employee removed successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Employee not removed'], 422);
        }
    }
    public function getEmployeeByCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $users = User::where('company_id', $request->company_id)->get();
        if ($users->count() > 0) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Employee list', 'data' => $users], 200);
        } else {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Employee not found'], 422);
        }
    }
    public function addPortfolioAd(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'description' => 'required',
            'images' => 'required',
            'price' => 'required',
            'total_quantity' => 'required',
            'portfolio_id' => 'required|exists:portfolios,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        if (!empty($request->images)) {
            if (isset($request->images)) {
                for ($i = 0; $i < count($request->images); $i++) {
                    if ($request->hasFile('images')) {
                        $imageName = rand() . time() . '.' . $request->images[$i]->extension();
                        $request->images[$i]->move(public_path('portfolioAdImages'), $imageName);
                        $getimageName[$i] = url('portfolioAdImages') . '/' . $imageName;
                    }
                }
            } else {
                $getimageName = null;
            }
        } else {
            $getimageName = null;
        }
        $portfolioAd = PortfolioAd::create([

            'portfolio_id' => $request->portfolio_id,
            'name' => $request->name,
            'description' => $request->description,
            'images' => $getimageName,
            'price' => $request->price,
            'total_quantity' => $request->total_quantity,
        ]);
        if (!$portfolioAd) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Portfolio Ad not added'], 422);
        } else {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Portfolio Ad added successfully', 'data' => $portfolioAd], 200);
        }
    }
    public function updatePortfolioAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portfolio_ad_id' => 'required|exists:portfolio_ads,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $portfolioAd = PortfolioAd::where('id', $request->portfolio_ad_id)->first();
        if (!empty($request->images)) {

            for ($i = 0; $i < count($request->images); $i++) {
                if ($request->hasFile('images')) {
                    $imageName = rand() . time() . '.' . $request->images[$i]->extension();
                    $request->images[$i]->move(public_path('portfolioAdImages'), $imageName);
                    $getimageName[$i] = url('portfolioAdImages') . '/' . $imageName;
                }
            }
            if (empty($request->imagesUrl)) {
                $imageName = $getimageName;
            } else {
                $imageName = array_merge($getimageName, $request->imagesUrl);
            }

        } else {
            $imageName = $request->imagesUrl;
        }
        $portfolioAd = PortfolioAd::where('id', $request->portfolio_ad_id)->update([
            'name' => $request->name ? $request->name : $portfolioAd->name,
            'description' => $request->description ? $request->description : $portfolioAd->description,
            'images' => $request->images ? $imageName : $portfolioAd->images,
            'price' => $request->price ? $request->price : $portfolioAd->price,
            'total_quantity' => $request->total_quantity ? $request->total_quantity : $portfolioAd->total_quantity,
        ]);
        if ($portfolioAd == 1) {
            $portfolioAd = PortfolioAd::where('id', $request->portfolio_ad_id)->first();
            return response(['status' => 'success', 'code' => 200, 'message' => 'Portfolio Ad updated successfully', 'data' => $portfolioAd], 200);
        } else {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Portfolio Ad not updated'], 422);
        }
    }
    public function deletePortfolioAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portfolio_ad_id' => 'required|exists:portfolio_ads,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $portfolioAd = PortfolioAd::where('id', $request->portfolio_ad_id)->delete();
        if ($portfolioAd == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Portfolio Ad deleted successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Portfolio Ad not deleted'], 422);
        }
    }
    public function getPortfolioAdByPortfolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portfolio_id' => 'required|exists:portfolios,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $portfolioAds = PortfolioAd::where('portfolio_id', $request->portfolio_id)->get();
        if ($portfolioAds->count() > 0) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Portfolio Ad list', 'data' => $portfolioAds], 200);
        } else {
            return response(['status' => 'error', 'code' => 422, 'message' => 'Portfolio Ad not found'], 422);
        }
    }
}
