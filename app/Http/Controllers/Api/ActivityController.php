<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankDetails;
use App\Models\Company;
use App\Models\CompanyAd;
use App\Models\CompanySubAd;
use App\Models\Employee;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function addCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'userId' => 'required|numeric|exists:users,id',
            'email' => 'required|unique:companies,email',
            'address' => 'required',
            'store_hours' => 'required',
            'category' => 'required',
            // 'reel' => 'required',
            'webLink' => 'required',
            'image' => 'required',
            'coverPhoto' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
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
                return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Cover should be file'], 422);
            }
        } else {
            $coverName = null;
        }
        if (isset($request->reel)) {
            if ($request->hasFile('reel')) {
                $reelName = rand() . time() . '.' . $request->reel->extension();
                $request->reel->move(public_path('reels'), $reelName);
                $reelName = asset('reels') . '/' . $reelName;
            } else {
                return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Reel should be file'], 422);
            }
        } else {
            $reelName = null;
        }
        $data = [
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'store_hours' => $request->store_hours,
            'category' => $request->category,
            'reels' => $reelName,
            'webLink' => $request->webLink,
            'profilePhoto' => $imageName,
            'coverPhoto' => $coverName,
        ];
        $company = Company::create($data);
        if (isset($company)) {
            return response(['status' => 'success', 'code' => 200, 'company' => $company, 'message' => 'Add Company Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'company' => null, 'data' => null, 'message' => 'Add Company Failed']);
        }
    }

    public function updateCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:companies,id',
            'name' => 'unique:companies,name',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $getCompany = Company::where('id', $request->id)->first();
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
                return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Cover should be file'], 422);
            }
        } else {
            $coverName = null;
        }
        if (isset($request->reel)) {
            if ($request->hasFile('reel')) {
                $reelName = rand() . time() . '.' . $request->reel->extension();
                $request->reel->move(public_path('reel'), $reelName);
                $reelName = asset('reels') . '/' . $reelName;
            } else {
                return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Reel should be file'], 422);
            }
        } else {
            $reelName = null;
        }
        $data = [
            'name' => $request->name ? $request->name  : $getCompany['name'],
            // 'email' => $request->email ? $request->email  : $getCompany['email'],
            'address' => $request->address ? $request->address  : $getCompany['address'],
            'store_hours' => $request->store_hours ? $request->store_hours  : $getCompany['store_hours'],
            'category' => $request->category ? $request->category  : $getCompany['category'],
            'reels' =>  $request->reels ? $reelName  : $getCompany['reels'],
            'webLink' => $request->webLink ? $request->webLink  : $getCompany['webLink'],
            'profilePhoto' =>  $request->profilePhoto ? $imageName  : $getCompany['profilePhoto'],
            'coverPhoto' => $request->coverPhoto ? $coverName : $getCompany['coverPhoto'],

        ];
        $company = Company::where('id', $request->id)->update($data);
        if ($company == 1) {
            return response(['status' => 'success', 'code' => 200,  'message' => 'Update Company Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'company' => null, 'data' => null, 'message' => 'Update Company Failed']);
        }
    }

    public function deleteCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:companies,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $company = Company::where('id', $request->id)->delete();
        if ($company == 1) {
            return response(['status' => 'success', 'code' => 200,  'message' => 'Delete Company Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Delete Company Failed']);
        }
    }
    public function getVerifiedCompany()
    {
        $user = Company::where(['isVerified' => 1])->get();
        if ($user->count() > 0) {
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'message' => 'Get Company Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company Not Found']);
        }
    }
    public function getCompanies()
    {
        $user = Company::with('user', 'employee', 'portfolio')->get();
        if ($user->count() > 0) {
            for ($i = 0; $i < $user->count(); $i++) {
                if (!empty($user[$i]['likes'])) {
                    $json = json_decode($user[$i]['likes'], true);
                    $user[$i]['likes'] = $json;
                    $user[$i]['likesCount'] = count($json);
                } else {
                    $user[$i]['likes'] = [];
                    $user[$i]['likesCount'] = 0;
                }
                if (!empty($user[$i]['dislikes'])) {
                    $json = json_decode($user[$i]['dislikes'], true);
                    $user[$i]['dislikes'] = $json;
                    $user[$i]['dislikesCount'] = count($json);
                } else {
                    $user[$i]['dislikes'] = [];
                    $user[$i]['dislikesCount'] = 0;
                }
            }
            return response(['status' => 'success', 'code' => 200, 'data' => $user, 'message' => 'Get Company Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Get Company Failed']);
        }
    }

    public function likes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = User::where('id', $request->userId)->first();
        if (!$user) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'user not found'], 403);
        }
        $user = User::where('id', $request->id)->first();

        if ($user) {
            if ($user['likes'] == null) {

                $newLike = array($request->userId);

                $jsonLike = json_encode($newLike);

                $newData = User::where('id', $request->id)->update(['likes' => $jsonLike]);

                $user = User::where('id', $request->id)->first();
                if (isset($user['dislikes']) && $user['dislikes'] != null) {
                    $jsonDislike = json_decode($user['dislikes'], true);
                    $user['dislikes'] = $jsonDislike;
                }
                $json = json_decode($user['likes'], true);
                $user['likes'] = $json;
                $count = count($json);
                return response(['status' => 'success', 'code' => 200, 'data' => $user, 'likescount' => $count, 'message' => 'User'], 200);
            } else {

                if ($user['dislikes'] != null) {
                    $jsondisLike = $user['dislikes'];
                    $dislikes = json_decode($jsondisLike);
                    if (in_array($request->userId, $dislikes)) {
                        $key = array_search($request->userId, $dislikes);
                        unset($dislikes[$key]);
                        $newdisArray = array_values($dislikes);
                        $newdisLike = json_encode($newdisArray);
                        User::where('id', $request->id)->update(['dislikes' => $newdisLike]);
                    }
                }

                $jsonLike = $user['likes'];
                $likes = json_decode($jsonLike);

                if (in_array($request->userId, $likes)) {

                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already liked'], 409);
                }
                array_push($likes, $request->userId);

                $newlikes = json_encode($likes);
                // print_r($newlikes);
                // exit;
                $newData = User::where('id', $request->id)->update(['likes' => $newlikes]);
                $user = User::where('id', $request->id)->first();
                if (isset($user['dislikes']) && $user['dislikes'] != null) {
                    $jsonDislike = json_decode($user['dislikes'], true);
                    $user['dislikes'] = $jsonDislike;
                }
                $json = json_decode($user['likes'], true);
                $user['likes'] = $json;

                $count = count($json);

                // $count=count($user['likes']);
                return response(['status' => 'success', 'code' => 200, 'data' => $user, 'likescount' => $count, 'message' => "User"], 200);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'User not found'], 403);
        }
    }


    public function dislikes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = User::where('id', $request->userId)->first();
        if (!$user) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'user not found'], 403);
        }
        $user = User::where('id', $request->id)->first();
        if ($user) {
            if ($user['dislikes'] == null) {
                $newLike = array($request->userId);

                $jsonLike = json_encode($newLike);

                $newData = User::where('id', $request->id)->update(['dislikes' => $jsonLike]);
                $user = User::where('id', $request->id)->first();
                if (isset($user['likes']) && $user['likes'] != null) {
                    $jsonlike = json_decode($user['likes'], true);
                    $user['likes'] = $jsonlike;
                }
                $json = json_decode($user['dislikes'], true);
                $user['dislikes'] = $json;
                $count = count($json);
                return response(['status' => 'success', 'code' => 200, 'data' => $user, 'dislikescount' => $count, 'message' => 'User'], 200);
            } else {

                if ($user['likes'] != null) {
                    $jsondisLike = $user['likes'];
                    $dislikes = json_decode($jsondisLike);
                    if (in_array($request->userId, $dislikes)) {
                        $key = array_search($request->userId, $dislikes);
                        unset($dislikes[$key]);
                        $newdisArray = array_values($dislikes);
                        $newdisLike = json_encode($newdisArray);
                        User::where('id', $request->id)->update(['likes' => $newdisLike]);
                    }
                }


                $jsonLike = $user['dislikes'];
                $likes = json_decode($jsonLike);

                if (in_array($request->userId, $likes)) {
                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already disliked'], 409);
                }
                array_push($likes, $request->userId);

                $newlikes = json_encode($likes);
                // print_r($newlikes);
                // exit;
                $newData = User::where('id', $request->id)->update(['dislikes' => $newlikes]);
                $user = User::where('id', $request->id)->first();
                if (isset($user['likes']) && $user['likes'] != null) {
                    $jsonlike = json_decode($user['likes'], true);
                    $user['likes'] = $jsonlike;
                }
                $json = json_decode($user['dislikes'], true);
                $user['dislikes'] = $json;
                $count = count($json);
                return response(['status' => 'success', 'code' => 200, 'data' => $user, 'dislikescount' => $count, 'message' => 'User'], 200);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'User not found'], 403);
        }
    }


    public function unlike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = User::where('id', $request->userId)->first();
        if (!$user) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'user not found'], 403);
        }
        $user = User::where('id', $request->id)->first();
        if ($user) {
            if ($user['likes'] != null) {
                $jsonLike = $user['likes'];
                // print_r($jsonLike);
                // exit;
                $likes = json_decode($jsonLike);

                if (in_array($request->userId, $likes)) {
                    $key = array_search($request->userId, $likes);
                    unset($likes[$key]);
                    $newArray = array_values($likes);
                    $newLike = json_encode($newArray);
                    $newData = User::where('id', $request->id)->update(['likes' => $newLike]);
                    $user = User::where('id', $request->id)->first();
                    if (isset($user['dislikes']) && $user['dislikes'] != null) {
                        $jsonDislike = json_decode($user['dislikes'], true);
                        $user['dislikes'] = $jsonDislike;
                    }
                    if (!empty($user['likes']) && $user['likes'] != null) {
                        $json = json_decode($user['likes'], true);
                        $user['likes'] = $json;
                        $count = count($json);
                    }

                    return response(['status' => 'success', 'code' => 200, 'data' => $user, 'likescount' => $count ? $count : null, 'message' => 'User'], 200);
                } else {
                    return response(['status' => 'success', 'code' => 403, 'data' => $likes, 'message' => 'like not found'], 403);
                }
            } else {
                return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'like not found'], 403);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'User not found'], 403);
        }
    }

    public function removeDislike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = User::where('id', $request->userId)->first();
        if (!$user) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'user not found'], 403);
        }
        $user = User::where('id', $request->id)->first();
        if ($user) {
            if ($user['dislikes'] != null) {
                $jsonLike = $user['dislikes'];
                // print_r($jsonLike);
                // exit;
                $likes = json_decode($jsonLike);

                if (in_array($request->userId, $likes)) {
                    $key = array_search($request->userId, $likes);
                    unset($likes[$key]);
                    $newArray = array_values($likes);
                    $newLike = json_encode($newArray);
                    $newData = User::where('id', $request->id)->update(['dislikes' => $newLike]);
                    $user = User::where('id', $request->id)->first();
                    if (isset($user['likes']) && $user['likes'] != null) {
                        $jsonlike = json_decode($user['likes'], true);
                        $user['likes'] = $jsonlike;
                    }
                    if (!empty($user['dislikes']) && $user['dislikes'] != null) {
                        $json = json_decode($user['dislikes'], true);
                        $user['dislikes'] = $json;
                        $count = count($json);
                    }
                    return response(['status' => 'success', 'code' => 200, 'data' => $user, 'dislikescount' => $count ? $count : null, 'message' => 'User'], 200);
                } else {
                    return response(['status' => 'success', 'code' => 403, 'data' => $likes, 'message' => 'dislike not found'], 403);
                }
            } else {
                return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'like not found'], 403);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'User not found'], 403);
        }
    }

    public function addEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'companyId' => 'required|numeric|exists:companies,id',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }

        if (isset($request->image)) {
            if ($request->hasFile('image')) {
                $imageName = rand() . time() . '.' . $request->image->extension();
                $request->image->move(public_path('employee'), $imageName);
                $imageName = asset('employee') . '/' . $imageName;
            } else {
                return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
            }
        } else {
            $imageName = null;
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'image' => $imageName,
            'userId' => auth()->user()->id,
            'companyId' => $request->companyId,
        ];
        $employee = Employee::create($data);
        if (isset($employee)) {
            return response(['status' => 'success', 'code' => 200, 'data' => $employee, 'message' => 'Add Employee Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Add Employee Failed']);
        }
    }

    public function updateEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:employees,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $getEmployee = Employee::where('id', $request->id)->first();
        if (isset($request->image)) {
            if ($request->hasFile('image')) {
                $imageName = rand() . time() . '.' . $request->image->extension();
                $request->image->move(public_path('employee'), $imageName);
                $imageName = asset('employee') . '/' . $imageName;
            } else {
                return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
            }
        } else {
            $imageName = null;
        }
        $data = [
            'name' => $request->name ? $request->name  : $getEmployee['name'],
            'image' =>  $request->image ? $imageName  : $getEmployee['image'],
        ];
        $employee = Employee::where('id', $request->id)->update($data);
        if ($employee == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Update Employee Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Update Employee Failed']);
        }
    }

    public function deleteEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:employees,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $employee = Employee::where('id', $request->id)->delete();
        if ($employee == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Delete Employee Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Delete Employee Failed']);
        }
    }

    public function addPortfolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
            'companyId' => 'required|numeric|exists:companies,id',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }

        if (isset($request->image)) {
            if ($request->hasFile('image')) {
                $imageName = rand() . time() . '.' . $request->image->extension();
                $request->image->move(public_path('portfolio'), $imageName);
                $imageName = asset('portfolio') . '/' . $imageName;
            } else {
                return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
            }
        } else {
            $imageName = null;
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'image' => $imageName,
            'userId' => auth()->user()->id,
            'companyId' => $request->companyId,
            'description' => $request->description,
        ];
        $portfolio = Portfolio::create($data);
        if (isset($portfolio)) {
            return response(['status' => 'success', 'code' => 200, 'data' => $portfolio, 'message' => 'Add Portfolio Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Add Portfolio Failed']);
        }
    }

    public function updatePortfolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:portfolios,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $getPortfolio = Portfolio::where('id', $request->id)->first();
        if (isset($request->image)) {
            if ($request->hasFile('image')) {
                $imageName = rand() . time() . '.' . $request->image->extension();
                $request->image->move(public_path('portfolio'), $imageName);
                $imageName = asset('portfolio') . '/' . $imageName;
            } else {
                return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
            }
        } else {
            $imageName = null;
        }
        $data = [
            'name' => $request->name ? $request->name  : $getPortfolio['name'],
            'image' =>  $request->image ? $imageName  : $getPortfolio['image'],
            'description' => $request->description ? $request->description  : $getPortfolio['description'],

        ];
        $portfolio = Portfolio::where('id', $request->id)->update($data);
        if ($portfolio == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Update Portfolio Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Update Portfolio Failed']);
        }
    }

    public function deletePortfolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:portfolios,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $portfolio = Portfolio::where('id', $request->id)->delete();
        if ($portfolio == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Delete Portfolio Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Delete Portfolio Failed']);
        }
    }

    public function addBankDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bankName' => 'required',
            'accountName' => 'required',
            'accountNumber' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $data = [
            'bankName' => $request->bankName,
            'accountName' => $request->accountName,
            'accountNumber' => $request->accountNumber,
            'userId' => auth()->user()->id,
        ];
        $bank = BankDetails::create($data);
        if (isset($bank)) {
            return response(['status' => 'success', 'code' => 200, 'data' => $bank, 'message' => 'Add Bank Details Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Add Bank Details Failed']);
        }
    }

    public function updateBankDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:bank_details,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $getBankDetails = BankDetails::where('id', $request->id)->first();

        $data = [
            'bankName' => $request->bankName ? $request->bankName  : $getBankDetails['bankName'],
            'accountName' => $request->accountName ? $request->accountName  : $getBankDetails['accountName'],
            'accountNumber' => $request->accountNumber ? $request->accountNumber  : $getBankDetails['accountNumber'],
        ];
        $bank = BankDetails::where('id', $request->id)->update($data);
        if ($bank == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Update BankDetails Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Update BankDetails Failed']);
        }
    }

    public function deleteBankDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:bank_details,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $bank = BankDetails::where('id', $request->id)->delete();
        if ($bank == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Delete BankDetails Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Delete BankDetails Failed']);
        }
    }
    public function getBankDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $bank = BankDetails::where('userId', $request->id)->first();
        if ($bank) {
            return response(['status' => 'success', 'code' => 200, 'data' => $bank, 'message' => 'Get BankDetails Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Get BankDetails Failed']);
        }
    }
    public function likesCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:users,id',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $company = Company::where('user_id', $request->userId)->first();
        if (!$company) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company not found'], 403);
        }
        $company = Company::where('id', $request->id)->first();

        if ($company) {
            if ($company['likes'] == null) {

                $newLike = array($request->userId);

                $jsonLike = json_encode($newLike);

                $newData = Company::where('id', $request->id)->update(['likes' => $jsonLike]);

                $company = Company::where('id', $request->id)->first();
                if (isset($company['dislikes']) && $company['dislikes'] != null) {
                    $jsonDislike = json_decode($company['dislikes'], true);
                    $company['dislikes'] = $jsonDislike;
                }
                $json = json_decode($company['likes'], true);
                $company['likes'] = $json;
                $count = count($json);
                return response(['status' => 'success', 'code' => 200, 'data' => $company, 'likescount' => $count, 'message' => 'Company'], 200);
            } else {

                if ($company['dislikes'] != null) {
                    $jsondisLike = $company['dislikes'];
                    $dislikes = json_decode($jsondisLike);
                    if (in_array($request->userId, $dislikes)) {
                        $key = array_search($request->userId, $dislikes);
                        unset($dislikes[$key]);
                        $newdisArray = array_values($dislikes);
                        $newdisLike = json_encode($newdisArray);
                        Company::where('id', $request->id)->update(['dislikes' => $newdisLike]);
                    }
                }

                $jsonLike = $company['likes'];
                $likes = json_decode($jsonLike);

                if (in_array($request->userId, $likes)) {

                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already liked'], 409);
                }
                array_push($likes, $request->userId);

                $newlikes = json_encode($likes);
                // print_r($newlikes);
                // exit;
                $newData = Company::where('id', $request->id)->update(['likes' => $newlikes]);
                $company = Company::where('id', $request->id)->first();
                if (isset($company['dislikes']) && $company['dislikes'] != null) {
                    $jsonDislike = json_decode($company['dislikes'], true);
                    $company['dislikes'] = $jsonDislike;
                }
                $json = json_decode($company['likes'], true);
                $company['likes'] = $json;

                $count = count($json);

                // $count=count($company['likes']);
                return response(['status' => 'success', 'code' => 200, 'data' => $company, 'likescount' => $count, 'message' => "Company"], 200);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company not found'], 403);
        }
    }


    public function dislikesCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:users,id',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $company = Company::where('id', $request->id)->first();
        if (!$company) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company not found'], 403);
        }
        $company = Company::where('id', $request->id)->first();
        if ($company) {
            if ($company['dislikes'] == null) {
                $newLike = array($request->userId);

                $jsonLike = json_encode($newLike);

                $newData = Company::where('id', $request->id)->update(['dislikes' => $jsonLike]);
                $company = Company::where('id', $request->id)->first();
                if (isset($company['likes']) && $company['likes'] != null) {
                    $jsonlike = json_decode($company['likes'], true);
                    $company['likes'] = $jsonlike;
                }
                $json = json_decode($company['dislikes'], true);
                $company['dislikes'] = $json;
                $count = count($json);
                return response(['status' => 'success', 'code' => 200, 'data' => $company, 'dislikescount' => $count, 'message' => 'Company'], 200);
            } else {

                if ($company['likes'] != null) {
                    $jsondisLike = $company['likes'];
                    $dislikes = json_decode($jsondisLike);
                    if (in_array($request->userId, $dislikes)) {
                        $key = array_search($request->userId, $dislikes);
                        unset($dislikes[$key]);
                        $newdisArray = array_values($dislikes);
                        $newdisLike = json_encode($newdisArray);
                        Company::where('id', $request->id)->update(['likes' => $newdisLike]);
                    }
                }


                $jsonLike = $company['dislikes'];
                $likes = json_decode($jsonLike);

                if (in_array($request->userId, $likes)) {
                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already disliked'], 409);
                }
                array_push($likes, $request->userId);

                $newlikes = json_encode($likes);
                // print_r($newlikes);
                // exit;
                $newData = Company::where('id', $request->id)->update(['dislikes' => $newlikes]);
                $company = Company::where('id', $request->id)->first();
                if (isset($company['likes']) && $company['likes'] != null) {
                    $jsonlike = json_decode($company['likes'], true);
                    $company['likes'] = $jsonlike;
                }
                $json = json_decode($company['dislikes'], true);
                $company['dislikes'] = $json;
                $count = count($json);
                return response(['status' => 'success', 'code' => 200, 'data' => $company, 'dislikescount' => $count, 'message' => 'Company'], 200);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company not found'], 403);
        }
    }


    public function unlikeCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:users,id',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $company = Company::where('id', $request->id)->first();
        if (!$company) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company not found'], 403);
        }
        $company = Company::where('id', $request->id)->first();
        if ($company) {
            if ($company['likes'] != null) {
                $jsonLike = $company['likes'];
                // print_r($jsonLike);
                // exit;
                $likes = json_decode($jsonLike);

                if (in_array($request->userId, $likes)) {
                    $key = array_search($request->userId, $likes);
                    unset($likes[$key]);
                    $newArray = array_values($likes);
                    $newLike = json_encode($newArray);
                    $newData = Company::where('id', $request->id)->update(['likes' => $newLike]);
                    $company = Company::where('id', $request->id)->first();
                    if (isset($company['dislikes']) && $company['dislikes'] != null) {
                        $jsonDislike = json_decode($company['dislikes'], true);
                        $company['dislikes'] = $jsonDislike;
                    }
                    if (!empty($company['likes']) && $company['likes'] != null) {
                        $json = json_decode($company['likes'], true);
                        $company['likes'] = $json;
                        $count = count($json);
                    }

                    return response(['status' => 'success', 'code' => 200, 'data' => $company, 'likescount' => $count ? $count : null, 'message' => 'Company'], 200);
                } else {
                    return response(['status' => 'success', 'code' => 403, 'data' => $likes, 'message' => 'like not found'], 403);
                }
            } else {
                return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'like not found'], 403);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company not found'], 403);
        }
    }

    public function removeDislikeCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:users,id',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $company = Company::where('id', $request->id)->first();
        if (!$company) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company not found'], 403);
        }
        $company = Company::where('id', $request->id)->first();
        if ($company) {
            if ($company['dislikes'] != null) {
                $jsonLike = $company['dislikes'];

                $likes = json_decode($jsonLike);

                if (in_array($request->userId, $likes)) {
                    $key = array_search($request->userId, $likes);
                    unset($likes[$key]);
                    $newArray = array_values($likes);
                    $newLike = json_encode($newArray);
                    $newData = Company::where('id', $request->id)->update(['dislikes' => $newLike]);
                    $company = Company::where('id', $request->id)->first();
                    if (isset($company['likes']) && $company['likes'] != null) {
                        $jsonlike = json_decode($company['likes'], true);
                        $company['likes'] = $jsonlike;
                    }
                    if (!empty($company['dislikes']) && $company['dislikes'] != null) {
                        $json = json_decode($company['dislikes'], true);
                        $company['dislikes'] = $json;
                        $count = count($json);
                    }
                    return response(['status' => 'success', 'code' => 200, 'data' => $company, 'dislikescount' => $count ? $count : null, 'message' => 'Company'], 200);
                } else {
                    return response(['status' => 'success', 'code' => 403, 'data' => $likes, 'message' => 'dislike not found'], 403);
                }
            } else {
                return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'like not found'], 403);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company not found'], 403);
        }
    }

    public function addCompanyAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'companyId' => 'required|numeric|exists:companies,id',
            'acService' => 'required',
            'image' => 'required',
            'businessName' => 'required',
            'businessWebsite' => 'required',
            'businessLocation' => 'required',
            'businessPhoneNumber' => 'required',
            'businessEmail' => 'required',
            'businessDescription' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        if (isset($request->image)) {
            for ($i = 0; $i < count($request->image); $i++) {
                if ($request->hasFile('image')) {
                    $imageName = rand() . time() . '.' . $request->image[$i]->extension();
                    $request->image[$i]->move(public_path('companyAd'), $imageName);
                    $imageName = asset('companyAd') . '/' . $imageName;

                    $getimageName[$i] = $imageName;
                }
            }
            $imageName = json_encode($getimageName);
        } else {
            $imageName = null;
        }
        $data = [
            'acService' => $request->acService,
            'images' => $imageName,
            'businessName' => $request->businessName,
            'businessWebsite' => $request->businessWebsite,
            'businessLocation' => $request->businessLocation,
            'businessPhoneNumber' => $request->businessPhoneNumber,
            'businessEmail' => $request->businessEmail,
            'businessDescription' => $request->businessDescription,
            'companyId' => $request->companyId,
            'userId' => auth()->user()->id,
        ];
        $companyAd = CompanyAd::create($data);
        if (isset($companyAd)) {
            $companyAd['images'] = json_decode($companyAd['images'], true);
            return response(['status' => 'success', 'code' => 200, 'data' => $companyAd, 'message' => 'Add Company Ad Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Add Company Ad Failed'], 403);
        }
    }

    public function updateCompanyAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:companies,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        if (!empty($request->image)) {
            if (isset($request->image)) {
                for ($i = 0; $i < count($request->image); $i++) {
                    if ($request->hasFile('image')) {
                        $imageName = rand() . time() . '.' . $request->image[$i]->extension();
                        $request->image[$i]->move(public_path('companyAd'), $imageName);
                        $imageName = asset('companyAd') . '/' . $imageName;
                        $getimageName[$i] = $imageName;
                    }
                }
                $imageName = json_encode($getimageName);
            } else {
                $imageName = null;
            }
        }
        $comAd = CompanyAd::where('id', $request->id)->first();

        $data = [
            'acService' => $request->acService ? $request->acService  : $comAd['acService'],
            'images' => $request->image ? $imageName : $comAd['images'],
            'businessName' => $request->businessName ? $request->businessName  : $comAd['businessName'],
            'businessWebsite' => $request->businessWebsite ? $request->businessWebsite  : $comAd['businessWebsite'],
            'businessLocation' => $request->businessLocation ? $request->businessLocation  : $comAd['businessLocation'],
            'businessPhoneNumber' => $request->businessPhoneNumber ? $request->businessPhoneNumber  : $comAd['businessPhoneNumber'],
            'businessEmail' => $request->businessEmail ? $request->businessEmail  : $comAd['businessEmail'],
            'businessDescription' => $request->businessDescription ? $request->businessDescription  : $comAd['businessDescription'],
        ];
        $companyAd = CompanyAd::where('id', $request->id)->update($data);
        if ($companyAd == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Update Company Ad Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Update Company Ad Failed'], 403);
        }
    }

    public function deleteCompanyAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:company_ads,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $companyAd = CompanyAd::where('id', $request->id)->delete();
        if ($companyAd == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Delete Company Ad Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Delete Company Ad Failed'], 403);
        }
    }
    public function getCompanyAd()
    {
        $companyAd = CompanyAd::with('subAd')->get();
        if ($companyAd->count() > 0) {
            for ($i = 0; $i < count($companyAd); $i++) {
                $companyAd[$i]['images'] = json_decode($companyAd[$i]['images'], true);

                for ($j = 0; $j < count($companyAd[$i]['subAd']); $j++) {
                    $companyAd[$i]['subAd'][$j]['images'] = json_decode($companyAd[$i]['subAd'][$j]['images'], true);
                }
            }
            return response(['status' => 'success', 'code' => 200, 'data' => $companyAd, 'message' => 'Get Company Ad Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Get Company Ad Failed'], 403);
        }
    }

    public function addSubAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'companyId' => 'required|numeric|exists:companies,id',
            'productName' => 'required',
            'image' => 'required',
            'totalProduct' => 'required',
            'description' => 'required',
            'companyAdId' => 'required|numeric|exists:company_ads,id',

        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        if (isset($request->image)) {
            for ($i = 0; $i < count($request->image); $i++) {
                if ($request->hasFile('image')) {
                    $imageName = rand() . time() . '.' . $request->image[$i]->extension();
                    $request->image[$i]->move(public_path('companySubAds'), $imageName);
                    $imageName = asset('companySubAds') . '/' . $imageName;
                    $getimageName[$i] = $imageName;
                }
            }
            $imageName = json_encode($getimageName);
        } else {
            $imageName = null;
        }
        $data = [
            'images' => $imageName,
            'productName' => $request->productName,
            'totalProduct' => $request->totalProduct,
            'description' => $request->description,
            'companyId' => $request->companyId,
            'companyAdId' => $request->companyAdId,
            'userId' => auth()->user()->id,
        ];
        $subAd = CompanySubAd::create($data);
        if (isset($subAd)) {
            $subAd['images'] = json_decode($subAd['images'], true);
            return response(['status' => 'success', 'code' => 200, 'data' => $subAd, 'message' => 'Add Sub Ad Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Add Sub Ad Failed'], 403);
        }
    }

    public function updateSubAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:company_sub_ads,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        if (!empty($request->image)) {
            if (isset($request->image)) {
                for ($i = 0; $i < count($request->image); $i++) {
                    if ($request->hasFile('image')) {
                        $imageName = rand() . time() . '.' . $request->image[$i]->extension();
                        $request->image[$i]->move(public_path('companySubAds'), $imageName);
                        $imageName = asset('companySubAds') . '/' . $imageName;
                        $getimageName[$i] = $imageName;
                    }
                }
                $imageName = json_encode($getimageName);
            } else {
                $imageName = null;
            }
        }
        $comSubAd = CompanySubAd::where('id', $request->id)->first();

        $data = [
            'productName' => $request->productName ? $request->productName  : $comSubAd['productName'],
            'images' => $request->image ? $imageName : $comSubAd['images'],
            'totalProduct' => $request->totalProduct ? $request->totalProduct  : $comSubAd['totalProduct'],
            'description' => $request->description ? $request->description  : $comSubAd['description'],
        ];
        $companySubAd = CompanySubAd::where('id', $request->id)->update($data);
        if ($companySubAd == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Update Company Sub Ad Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Update Company Sub Ad Failed'], 403);
        }
    }


    public function deleteCompanySubAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:company_sub_ads,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $companySubAd = CompanySubAd::where('id', $request->id)->delete();
        if ($companySubAd == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Delete Company Sub Ad Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Delete Company Sub Ad Failed'], 403);
        }
    }
}
