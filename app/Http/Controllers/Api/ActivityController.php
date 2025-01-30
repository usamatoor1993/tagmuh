<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\QuoteMail;
use App\Models\BankDetails;
use App\Models\Company;
use App\Models\CompanyAd;
use App\Models\CompanyAdReview;
use App\Models\CompanySubAd;
use App\Models\CompanySubAdReview;
use App\Models\Employee;
use App\Models\Event;
use App\Models\EventReview;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function addCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required|numeric|exists:users,id',
            'email' => 'required|unique:companies,email',
            'address' => 'required',
            'store_hours' => 'required',
            'category' => 'required',
            // 'reel' => 'required',
            'web_link' => 'required',
            'image' => 'required',
            'cover_photo' => 'required',
            'is_selected' => 'required',
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

        if (isset($request->cover_photo)) {
            if ($request->hasFile('cover_photo')) {
                $coverName = rand() . time() . '.' . $request->cover_photo->extension();
                $request->cover_photo->move(public_path('cover_photo'), $coverName);
                $coverName = asset('cover_photo') . '/' . $coverName;
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
            'web_link' => $request->web_link,
            'profile_photo' => $imageName,
            'cover_photo' => $coverName,
            'is_selected' => $request->is_selected,
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

        if (isset($request->cover_photo)) {
            if ($request->hasFile('cover_photo')) {
                $coverName = rand() . time() . '.' . $request->cover_photo->extension();
                $request->cover_photo->move(public_path('cover_photo'), $coverName);
                $coverName = asset('cover_photo') . '/' . $coverName;
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
            'web_link' => $request->web_link ? $request->web_link  : $getCompany['web_link'],
            'profile_photo' =>  $request->profile_photo ? $imageName  : $getCompany['profile_photo'],
            'cover_photo' => $request->cover_photo ? $coverName : $getCompany['cover_photo'],
            'is_selected' => $request->is_selected ? $request->is_selected  : $getCompany['is_selected'],


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
        $user = Company::where(['is_verified' => 1])->get();
        if ($user->count() > 0) {
            return response(['status' => 'success', 'code' => 200, 'user' => $user, 'message' => 'Get Company Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company Not Found']);
        }
    }

    public function getCompanyByServiceId(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:services,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = Company::where('category', $request->id)->get();

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
    public function getCompanyDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:companies,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = Company::where('id', $request->id)->with('user', 'employee', 'portfolio')->first();
        if ($user) {
            if (!empty($user['likes'])) {
                $json = json_decode($user['likes'], true);
                $user['likes'] = $json;
                $user['likesCount'] = count($json);
            } else {
                $user['likes'] = [];
                $user['likesCount'] = 0;
            }
            if (!empty($user['dislikes'])) {
                $json = json_decode($user['dislikes'], true);
                $user['dislikes'] = $json;
                $user['dislikesCount'] = count($json);
            } else {
                $user['dislikes'] = [];
                $user['dislikesCount'] = 0;
            }

            return response(['status' => 'success', 'code' => 200, 'data' => $user, 'message' => 'Get Company Detail Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Get Company Detail Failed']);
        }
    }
    public function likes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'user not found'], 403);
        }
        $user = User::where('id', $request->id)->first();

        if ($user) {
            if ($user['likes'] == null) {

                $newLike = array($request->user_id);

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
                    if (in_array($request->user_id, $dislikes)) {
                        $key = array_search($request->user_id, $dislikes);
                        unset($dislikes[$key]);
                        $newdisArray = array_values($dislikes);
                        $newdisLike = json_encode($newdisArray);
                        User::where('id', $request->id)->update(['dislikes' => $newdisLike]);
                    }
                }

                $jsonLike = $user['likes'];
                $likes = json_decode($jsonLike);

                if (in_array($request->user_id, $likes)) {

                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already liked'], 409);
                }
                array_push($likes, $request->user_id);

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
            'user_id' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'user not found'], 403);
        }
        $user = User::where('id', $request->id)->first();
        if ($user) {
            if ($user['dislikes'] == null) {
                $newLike = array($request->user_id);

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
                    if (in_array($request->user_id, $dislikes)) {
                        $key = array_search($request->user_id, $dislikes);
                        unset($dislikes[$key]);
                        $newdisArray = array_values($dislikes);
                        $newdisLike = json_encode($newdisArray);
                        User::where('id', $request->id)->update(['likes' => $newdisLike]);
                    }
                }


                $jsonLike = $user['dislikes'];
                $likes = json_decode($jsonLike);

                if (in_array($request->user_id, $likes)) {
                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already disliked'], 409);
                }
                array_push($likes, $request->user_id);

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
            'user_id' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = User::where('id', $request->user_id)->first();
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

                if (in_array($request->user_id, $likes)) {
                    $key = array_search($request->user_id, $likes);
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
            'user_id' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $user = User::where('id', $request->user_id)->first();
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

                if (in_array($request->user_id, $likes)) {
                    $key = array_search($request->user_id, $likes);
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

    // public function addEmployee(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'email' => 'required|email|unique:employees,email',
    //         'company_id' => 'required|numeric|exists:companies,id',
    //         'image' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
    //     }

    //     if (isset($request->image)) {
    //         if ($request->hasFile('image')) {
    //             $imageName = rand() . time() . '.' . $request->image->extension();
    //             $request->image->move(public_path('employee'), $imageName);
    //             $imageName = asset('employee') . '/' . $imageName;
    //         } else {
    //             return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
    //         }
    //     } else {
    //         $imageName = null;
    //     }
    //     $data = [
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'image' => $imageName,
    //         'user_id' => auth()->user()->id,
    //         'company_id' => $request->company_id,
    //     ];
    //     $employee = Employee::create($data);
    //     if (isset($employee)) {
    //         return response(['status' => 'success', 'code' => 200, 'data' => $employee, 'message' => 'Add Employee Successfully'], 200);
    //     } else {
    //         return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Add Employee Failed']);
    //     }
    // }

    // public function updateEmployee(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required|numeric|exists:employees,id',
    //     ]);
    //     if ($validator->fails()) {
    //         return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
    //     }
    //     $getEmployee = Employee::where('id', $request->id)->first();
    //     if (isset($request->image)) {
    //         if ($request->hasFile('image')) {
    //             $imageName = rand() . time() . '.' . $request->image->extension();
    //             $request->image->move(public_path('employee'), $imageName);
    //             $imageName = asset('employee') . '/' . $imageName;
    //         } else {
    //             return response(['status' => 'unsuccessful', 'code' => 422, 'message' => 'Image should be file'], 422);
    //         }
    //     } else {
    //         $imageName = null;
    //     }
    //     $data = [
    //         'name' => $request->name ? $request->name  : $getEmployee['name'],
    //         'image' =>  $request->image ? $imageName  : $getEmployee['image'],
    //     ];
    //     $employee = Employee::where('id', $request->id)->update($data);
    //     if ($employee == 1) {
    //         return response(['status' => 'success', 'code' => 200, 'message' => 'Update Employee Successfully'], 200);
    //     } else {
    //         return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Update Employee Failed']);
    //     }
    // }

    // public function deleteEmployee(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required|numeric|exists:employees,id',
    //     ]);
    //     if ($validator->fails()) {
    //         return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
    //     }
    //     $employee = Employee::where('id', $request->id)->delete();
    //     if ($employee == 1) {
    //         return response(['status' => 'success', 'code' => 200, 'message' => 'Delete Employee Successfully'], 200);
    //     } else {
    //         return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Delete Employee Failed']);
    //     }
    // }

    // public function getEmployeeByCompanyId(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required|numeric|exists:companies,id',
    //     ]);
    //     if ($validator->fails()) {
    //         return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
    //     }
    //     $employee = Employee::where('company_id', $request->id)->get();
    //     if ($employee->count() > 0) {
    //         return response(['status' => 'success', 'code' => 200, 'data' => $employee, 'message' => 'Get Employee Successfully'], 200);
    //     } else {
    //         return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Get Employee Failed']);
    //     }
    // }

    public function addPortfolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
            'company_id' => 'required|numeric|exists:companies,id',
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
            'user_id' => auth()->user()->id,
            'company_id' => $request->company_id,
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

    public function getPortfolioByCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:companies,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $portfolio = Portfolio::where('company_id', $request->id)->get();
        if ($portfolio->count() > 0) {
            return response(['status' => 'success', 'code' => 200, 'data' => $portfolio, 'message' => 'Get Portfolio Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Get Portfolio Failed']);
        }
    }
    public function getPortfolioById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:portfolios,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $portfolio = Portfolio::where('id', $request->id)->get();
        if ($portfolio->count() > 0) {
            return response(['status' => 'success', 'code' => 200, 'data' => $portfolio, 'message' => 'Get Portfolio Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Get Portfolio Failed']);
        }
    }
    public function addBankDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $data = [
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'user_id' => auth()->user()->id,
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
            'bank_name' => $request->bank_name ? $request->bank_name  : $getBankDetails['bank_name'],
            'account_name' => $request->account_name ? $request->account_name  : $getBankDetails['account_name'],
            'account_number' => $request->account_number ? $request->account_number  : $getBankDetails['account_number'],
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
        $bank = BankDetails::where('user_id', $request->id)->first();
        if ($bank) {
            return response(['status' => 'success', 'code' => 200, 'data' => $bank, 'message' => 'Get BankDetails Successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 403, 'data' => null, 'message' => 'Get BankDetails Failed']);
        }
    }
    public function likesCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        // $company = Company::where('user_id', $request->user_id)->first();
        // if (!$company) {
        //     return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Company not found'], 403);
        // }
        $company = Company::where('id', $request->id)->first();

        if ($company) {
            if ($company['likes'] == null) {

                $newLike = array($request->user_id);

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
                    if (in_array($request->user_id, $dislikes)) {
                        $key = array_search($request->user_id, $dislikes);
                        unset($dislikes[$key]);
                        $newdisArray = array_values($dislikes);
                        $newdisLike = json_encode($newdisArray);
                        Company::where('id', $request->id)->update(['dislikes' => $newdisLike]);
                    }
                }

                $jsonLike = $company['likes'];
                $likes = json_decode($jsonLike);

                if (in_array($request->user_id, $likes)) {

                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already liked'], 409);
                }
                array_push($likes, $request->user_id);

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
            'user_id' => 'required|numeric|exists:users,id',
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
                $newLike = array($request->user_id);

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
                    if (in_array($request->user_id, $dislikes)) {
                        $key = array_search($request->user_id, $dislikes);
                        unset($dislikes[$key]);
                        $newdisArray = array_values($dislikes);
                        $newdisLike = json_encode($newdisArray);
                        Company::where('id', $request->id)->update(['likes' => $newdisLike]);
                    }
                }


                $jsonLike = $company['dislikes'];
                $likes = json_decode($jsonLike);

                if (in_array($request->user_id, $likes)) {
                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already disliked'], 409);
                }
                array_push($likes, $request->user_id);

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
            'user_id' => 'required|numeric|exists:users,id',
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

                if (in_array($request->user_id, $likes)) {
                    $key = array_search($request->user_id, $likes);
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
            'user_id' => 'required|numeric|exists:users,id',
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

                if (in_array($request->user_id, $likes)) {
                    $key = array_search($request->user_id, $likes);
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
            'company_id' => 'required|numeric|exists:companies,id',
            'ac_service' => 'required',
            'image' => 'required',
            'business_name' => 'required',
            'business_website' => 'required',
            'business_location' => 'required',
            'business_phone_number' => 'required',
            'business_email' => 'required',
            'business_description' => 'required',
            'price' => 'required',
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
            'ac_service' => $request->ac_service,
            'images' => $imageName,
            'business_name' => $request->business_name,
            'business_website' => $request->business_website,
            'business_location' => $request->business_location,
            'business_phone_number' => $request->business_phone_number,
            'business_email' => $request->business_email,
            'business_description' => $request->business_description,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'user_id' => auth()->user()->id,
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
            'id' => 'required|numeric|exists:company_ads,id',
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
            'ac_service' => $request->ac_service ? $request->ac_service  : $comAd['ac_service'],
            'images' => $request->image ? $imageName : $comAd['images'],
            'business_name' => $request->business_name ? $request->business_name  : $comAd['business_name'],
            'business_website' => $request->business_website ? $request->business_website  : $comAd['business_website'],
            'business_location' => $request->business_location ? $request->business_location  : $comAd['business_location'],
            'business_phone_number' => $request->business_phone_number ? $request->business_phone_number  : $comAd['business_phone_number'],
            'business_email' => $request->business_email ? $request->business_email  : $comAd['business_email'],
            'business_description' => $request->business_description ? $request->business_description  : $comAd['business_description'],
            'price' => $request->price ? $request->price  : $comAd['price'],
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
    public function getCompanyAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|numeric|exists:companies,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $companyAd = CompanyAd::where('company_id',$request->company_id)->with('subAd', 'company')->get();
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
            'company_id' => 'required|numeric|exists:companies,id',
            'product_name' => 'required',
            'image' => 'required',
            'total_product' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'company_ad_id' => 'required|numeric|exists:company_ads,id',

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
            'product_name' => $request->product_name,
            'total_product' => $request->total_product,
            'description' => $request->description,
            'company_id' => $request->company_id,
            'company_ad_id' => $request->company_ad_id,
            'price' => $request->price,
            'user_id' => auth()->user()->id,
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
            'product_name' => $request->product_name ? $request->product_name  : $comSubAd['product_name'],
            'images' => $request->image ? $imageName : $comSubAd['images'],
            'total_product' => $request->total_product ? $request->total_product  : $comSubAd['total_product'],
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

    public function getCompanySubAdByCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:companies,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $companySubAd = CompanySubAd::where('company_ad_id', $request->id)->get();
        if ($companySubAd->count() > 0) {

            for ($j = 0; $j < count($companySubAd); $j++) {
                $companySubAd[$j]['images'] = json_decode($companySubAd[$j]['images'], true);
            }

            return response(['status' => 'success', 'code' => 200, 'data' => $companySubAd, 'message' => 'Get Company Sub Ad  Detail Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Get Company Sub Ad Detail Failed'], 403);
        }
    }
    public function getCompanyAdDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:company_ads,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $companyAd = CompanyAd::where('id', $request->id)->with('subAd')->first();
        if ($companyAd->count() > 0) {
            $companyAd['images'] = json_decode($companyAd['images'], true);

            for ($j = 0; $j < count($companyAd['subAd']); $j++) {
                $companyAd['subAd'][$j]['images'] = json_decode($companyAd['subAd'][$j]['images'], true);
            }

            return response(['status' => 'success', 'code' => 200, 'data' => $companyAd, 'message' => 'Get Company Ad  Detail Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Get Company Ad Detail Failed'], 403);
        }
    }
    public function CompanyAdReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_ad_id' => 'required|exists:company_ads,id',
            // 'comment' => 'required',
            'stars' => 'required|numeric|min:1|max:5',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $checkVendor = CompanyAd::where('id', $request->company_ad_id)->first();
        if (!$checkVendor) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'Comoany Ad not found'], 403);
        }
        $user = auth()->user();
        $checkReviews = CompanyAdReview::where('user_id', $user['id'])->where('company_ad_id', $request->company_ad_id)->get();
        if ($checkReviews->count() > 0) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'already reviewed']);
        }
        CompanyAdReview::create(
            [
                'user_id' => $user['id'],
                'company_ad_id' => $request->company_ad_id,
                'comment' => $request->comment,
                'stars' => $request->stars ?  $request->stars : 0,
            ]
        );
        $ratings = $checkVendor['rating'];
        if ($ratings == 0) {
            CompanyAd::where('id', $request->company_ad_id)->update(['rating' => $request->stars]);
        } else {
            $getTotalRatings = CompanyAdReview::where('company_ad_id', $request->company_ad_id)->get();
            $totalRatings = $getTotalRatings->count() * 5;
            $total = CompanyAdReview::where('company_ad_id', $request->company_ad_id)->sum('stars');
            $getmultiply = $total * 5;
            $average = $getmultiply / $totalRatings;
            CompanyAd::where('id', $request->company_ad_id)->update(['rating' => $average]);
        }

        return response(['status' => 'success', 'code' => 200, 'message' => 'Company reviewed successfully'], 200);
    }


    public function CompanySubAdReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_sub_ad_id' => 'required|exists:company_sub_ads,id',
            // 'comment' => 'required',
            'stars' => 'required|numeric|min:1|max:5',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $checkVendor = CompanySubAd::where('id', $request->company_sub_ad_id)->first();
        if (!$checkVendor) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'Comoany Ad not found'], 403);
        }
        $user = auth()->user();
        $checkReviews = CompanySubAdReview::where('user_id', $user['id'])->where('company_sub_ad_id', $request->company_sub_ad_id)->get();
        if ($checkReviews->count() > 0) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'already reviewed']);
        }
        CompanySubAdReview::create(
            [
                'user_id' => $user['id'],
                'company_sub_ad_id' => $request->company_sub_ad_id,
                'comment' => $request->comment,
                'stars' => $request->stars ?  $request->stars : 0,
            ]
        );
        $ratings = $checkVendor['rating'];
        if ($ratings == 0) {
            CompanySubAd::where('id', $request->company_sub_ad_id)->update(['rating' => $request->stars]);
        } else {
            $getTotalRatings = CompanySubAdReview::where('company_sub_ad_id', $request->company_sub_ad_id)->get();
            $totalRatings = $getTotalRatings->count() * 5;
            $total = CompanySubAdReview::where('company_sub_ad_id', $request->company_sub_ad_id)->sum('stars');
            $getmultiply = $total * 5;
            $average = $getmultiply / $totalRatings;
            CompanySubAd::where('id', $request->company_sub_ad_id)->update(['rating' => $average]);
        }

        return response(['status' => 'success', 'code' => 200, 'message' => 'Company Sub Ad reviewed successfully'], 200);
    }

    public function quoteMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required',
            'location' => 'required',
            'description' => 'required',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $data = [
            'time' => $request->time,
            'location' => $request->location,
            'description' => $request->description,
            'body' => 'This is for testing email using smtp.'
        ];
 
        Mail::to($request->email)->send(new QuoteMail($data));
        return response(['status' => 'success', 'code' => 200, 'message' => "Email is sent successfully."], 200);
    }

    public function addEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
            'description' => 'required',
            'date' => 'required',
            'time' => 'required',
            'email' => 'required|email',
            'event_by' => 'required',
            'ticket' => 'required',
            'location' => 'required',
            'company_id' => 'required|exists:companies,id',

        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        if (isset($request->image)) {
            for ($i = 0; $i < count($request->image); $i++) {
                if ($request->hasFile('image')) {
                    $imageName = rand() . time() . '.' . $request->image[$i]->extension();
                    $request->image[$i]->move(public_path('events'), $imageName);
                    $imageName = asset('events') . '/' . $imageName;

                    $getimageName[$i] = $imageName;
                }
            }
            $imageName = json_encode($getimageName);
        } else {
            $imageName = null;
        }
        $data = [
            'name' => $request->name,
            'image' => $imageName,
            'description' => $request->description,
            'date' => $request->date,
            'time' => $request->time,
            'event_by' => $request->event_by,
            'email' => $request->email,
            'ticket' => $request->ticket,
            'location' => $request->location,
            'company_id' => $request->company_id,
            'user_id' => auth()->user()->id,
        ];
        $event = Event::create($data);
        if (isset($event)) {
            $event['image'] = json_decode($event['image'], true);
            $event['going'] = json_decode($event['going'], true);
            $event['interested'] = json_decode($event['interested'], true);
            return response(['status' => 'success', 'code' => 200, 'data' => $event, 'message' => 'Add Event Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Add Event Failed'], 403);
        }
    }

    public function updateEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:events,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $event = Event::where('id', $request->id)->first();
        $event['image'] = json_decode($event['image'], true);

        if (!empty($request->images)) {

            for ($i = 0; $i < count($request->images); $i++) {
                if ($request->hasFile('images')) {
                    $imageName = rand() . time() . '.' . $request->images[$i]->extension();
                    $request->images[$i]->move(public_path('events'), $imageName);
                    $getimageName[$i] = url('events') . '/' . $imageName;
                    if (file_exists($getimageName[$i])) {
                        unlink($getimageName[$i]);
                    }

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
        // if (isset($request->image)) {
        //     for ($i = 0; $i < count($request->image); $i++) {
        //         if ($request->hasFile('image')) {
        //             $imageName = rand() . time() . '.' . $request->image[$i]->extension();
        //             $request->image[$i]->move(public_path('events'), $imageName);
        //             $imageName = asset('events') . '/' . $imageName;
        //             $getimageName[$i] = $imageName;
        //         }
        //     }
        //     $imageName = json_encode($getimageName);
        // } else {
        //     $imageName = null;
        // }
        $data = [
            'name' => $request->name ? $request->name : $event['name'],
            'image' => $request->image ? $imageName : $event['image'],
            'description' => $request->description ? $request->description : $event['description'],
            'date' => $request->date ? $request->date : $event['date'],
            'time' => $request->time ? $request->time : $event['time'],
            'event_by' => $request->event_by ? $request->event_by : $event['event_by'],
            'email' => $request->email ? $request->email : $event['email'],
            'ticket' => $request->ticket ? $request->ticket : $event['ticket'],
            'location' => $request->location ? $request->location : $event['location'],
        ];
        $event = Event::where('id', $request->id)->update($data);
        if ($event == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Update Event Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Update Event Failed'], 403);
        }
    }

    public function deleteEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:events,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $event = Event::where('id', $request->id)->delete();
        if ($event == 1) {
            return response(['status' => 'success', 'code' => 200, 'message' => 'Delete Event Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Delete Event Failed'], 403);
        }
    }

    public function getAllEvents()
    {
        $event = Event::with('user','company')->get();
        if ($event->count() > 0) {
            foreach ($event as $events) {
        
                $events['image'] = json_decode($events['image'], true);
                $events['going'] = json_decode($events['going'], true);
                $events['interested'] = json_decode($events['interested'], true);
                
                    $events['user']['id_card']=null;
                // if(!empty($idcard) || $idcard!=null ||  $idcard!=""){
                // $idcard = json_decode($idcard, true);
                // $events['user']['id_card']=$idcard;
                // }
                
                $going = $events['going'];
                $interested = $events['interested'];
                if (!empty($going)) {
                    $events['goingCount'] = count($going);
                } else {
                    $events['goingCount'] = 0;
                }
                if (!empty($interested)) {
                    $events['interestedCount'] = count($interested);
                } else {
                    $events['interestedCount'] = 0;
                }
               
            }
            return response(['status' => 'success', 'code' => 200, 'data' => $event, 'message' => 'Get Event Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Get Event Failed'], 403);
        }
    }

    public function getAllMyEvents()
    {
        $event = Event::where('user_id',auth()->user()->id)->with('company')->get();
        if ($event->count() > 0) {
            foreach ($event as $events) {
        
                $events['image'] = json_decode($events['image'], true);
                $events['going'] = json_decode($events['going'], true);
                $events['interested'] = json_decode($events['interested'], true);
                
                    $events['user']['id_card']=null;
                // if(!empty($idcard) || $idcard!=null ||  $idcard!=""){
                // $idcard = json_decode($idcard, true);
                // $events['user']['id_card']=$idcard;
                // }
                
                $going = $events['going'];
                $interested = $events['interested'];
                if (!empty($going)) {
                    $events['goingCount'] = count($going);
                } else {
                    $events['goingCount'] = 0;
                }
                if (!empty($interested)) {
                    $events['interestedCount'] = count($interested);
                } else {
                    $events['interestedCount'] = 0;
                }
                // dd($events['user']);
            }
            return response(['status' => 'success', 'code' => 200, 'data' => $event, 'message' => 'Get Event Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Get Event Failed'], 403);
        }
    }
    public function getEventDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:events,id',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $event = Event::where('id', $request->id)->with('user','company')->first();
        if ($event) {

            $event['image'] = json_decode($event['image'], true);
            $event['going'] = json_decode($event['going'], true);
            $event['interested'] = json_decode($event['interested'], true);
            $going = $event['going'];
            $interested = $event['interested'];
            if (!empty($going)) {
                $event['goingCount'] = count($going);
            } else {
                $event['goingCount'] = 0;
            }
            if (!empty($interested)) {
                $event['interestedCount'] = count($interested);
            } else {
                $event['interestedCount'] = 0;
            }
            return response(['status' => 'success', 'code' => 200, 'data' => $event, 'message' => 'Get Event Detail Successfully'], 200);
        } else {
            return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Get Event Detail Failed'], 403);
        }
    }



    public function addReviewEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            // 'comment' => 'required',
            'stars' => 'required|numeric|min:1|max:5',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 422, 'message' => 'missing or wrong params', 'errors' => $validator->errors()->all()], 422);
        }
        $checkVendor = Event::where('id', $request->event_id)->first();
        if (!$checkVendor) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'Event not found'], 403);
        }
        $user = auth()->user();
        $checkReviews = EventReview::where('user_id', $user['id'])->where('event_id', $request->event_id)->get();
        if ($checkReviews->count() > 0) {
            return response(['status' => 'error', 'code' => 403, 'message' => 'already reviewed']);
        }
        EventReview::create(
            [
                'user_id' => $user['id'],
                'event_id' => $request->event_id,
                'comment' => $request->comment,
                'stars' => $request->stars ?  $request->stars : 0,
            ]
        );
        $ratings = $checkVendor['rating'];
        if ($ratings == 0) {
            Event::where('id', $request->event_id)->update(['rating' => $request->stars]);
        } else {
            $getTotalRatings = EventReview::where('event_id', $request->event_id)->get();
            $totalRatings = $getTotalRatings->count() * 5;
            $total = EventReview::where('event_id', $request->event_id)->sum('stars');
            $getmultiply = $total * 5;
            $average = $getmultiply / $totalRatings;
            Event::where('id', $request->event_id)->update(['rating' => $average]);
        }
        return response(['status' => 'success', 'code' => 200, 'message' => 'Vendor reviewed successfully'], 200);
    }

    public function addGoingEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }

        $event = Event::where('id', $request->id)->first();

        if ($event) {
            if ($event['going'] == null) {

                $newLike = array($request->user_id);

                $jsonGoing = json_encode($newLike);

                $newData = Event::where('id', $request->id)->update(['going' => $jsonGoing]);

                $event = Event::where('id', $request->id)->first();

                $json = json_decode($event['going'], true);
                $event['going'] = $json;
                $count = count($json);
                return response(['status' => 'success', 'code' => 200, 'data' => $event, 'likescount' => $count, 'message' => 'Event'], 200);
            } else {

                $jsonGoing = $event['going'];
                $likes = json_decode($jsonGoing);

                if (in_array($request->user_id, $likes)) {

                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already Going'], 409);
                }
                array_push($likes, $request->user_id);

                $newlikes = json_encode($likes);

                $newData = Event::where('id', $request->id)->update(['going' => $newlikes]);
                $event = Event::where('id', $request->id)->first();
                $json = json_decode($event['going'], true);
                $event['image'] = json_decode($event['image'], true);
                $event['going'] = $json;

                $count = count($json);

                return response(['status' => 'success', 'code' => 200, 'data' => $event, 'goingCount' => $count, 'message' => "Event"], 200);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Event not found'], 403);
        }
    }

    public function unGoingEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $event = Event::where('id', $request->id)->first();
        if (!$event) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Event not found'], 403);
        }
        $event = Event::where('id', $request->id)->first();
        if ($event) {
            if ($event['going'] != null) {
                $jsonGoing = $event['going'];

                $likes = json_decode($jsonGoing);

                if (in_array($request->user_id, $likes)) {
                    $key = array_search($request->user_id, $likes);
                    unset($likes[$key]);
                    $newArray = array_values($likes);
                    $newLike = json_encode($newArray);
                    $newData = Event::where('id', $request->id)->update(['going' => $newLike]);
                    $event = Event::where('id', $request->id)->first();

                    if (!empty($event['going']) && $event['going'] != null) {
                        $json = json_decode($event['going'], true);
                        $event['image'] = json_decode($event['image'], true);
                        $event['going'] = $json;

                        $count = count($json);
                    }

                    return response(['status' => 'success', 'code' => 200, 'data' => $event, 'goingsCount' => $count ? $count : null, 'message' => 'Event'], 200);
                } else {
                    return response(['status' => 'success', 'code' => 403, 'data' => $likes, 'message' => 'Going not found'], 403);
                }
            } else {
                return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Going not found'], 403);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Event not found'], 403);
        }
    }

    public function addInterestEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }

        $event = Event::where('id', $request->id)->first();

        if ($event) {
            if ($event['interested'] == null) {

                $newLike = array($request->user_id);

                $jsonInterest = json_encode($newLike);

                $newData = Event::where('id', $request->id)->update(['interested' => $jsonInterest]);

                $event = Event::where('id', $request->id)->first();

                $json = json_decode($event['interested'], true);
                $event['interested'] = $json;
                $count = count($json);
                return response(['status' => 'success', 'code' => 200, 'data' => $event, 'interestCount' => $count, 'message' => 'Event'], 200);
            } else {

                $jsonInterest = $event['interested'];
                $likes = json_decode($jsonInterest);

                if (in_array($request->user_id, $likes)) {

                    return response(['status' => 'error', 'code' => 409, 'data' => null, 'message' => 'already Interested'], 409);
                }
                array_push($likes, $request->user_id);

                $newlikes = json_encode($likes);

                $newData = Event::where('id', $request->id)->update(['interested' => $newlikes]);
                $event = Event::where('id', $request->id)->first();
                $json = json_decode($event['interested'], true);
                $event['image'] = json_decode($event['image'], true);
                $event['interested'] = $json;

                $count = count($json);

                return response(['status' => 'success', 'code' => 200, 'data' => $event, 'interestCount' => $count, 'message' => "Event"], 200);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Event not found'], 403);
        }
    }

    public function unInterestEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => $validator->errors()], 403);
        }
        $event = Event::where('id', $request->id)->first();
        if (!$event) {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Event not found'], 403);
        }
        $event = Event::where('id', $request->id)->first();
        if ($event) {
            if ($event['interested'] != null) {
                $jsonInterest = $event['interested'];

                $likes = json_decode($jsonInterest);

                if (in_array($request->user_id, $likes)) {
                    $key = array_search($request->user_id, $likes);
                    unset($likes[$key]);
                    $newArray = array_values($likes);
                    $newLike = json_encode($newArray);
                    $newData = Event::where('id', $request->id)->update(['interested' => $newLike]);
                    $event = Event::where('id', $request->id)->first();

                    if (!empty($event['interested']) && $event['interested'] != null) {
                        $json = json_decode($event['interested'], true);
                        $event['image'] = json_decode($event['image'], true);
                        $event['interested'] = $json;
                        $count = count($json);
                    }

                    return response(['status' => 'success', 'code' => 200, 'data' => $event, 'interestCount' => $count ? $count : null, 'message' => 'Event'], 200);
                } else {
                    return response(['status' => 'success', 'code' => 403, 'data' => $event, 'message' => 'Interested not found'], 403);
                }
            } else {
                return response(['status' => 'success', 'code' => 403, 'data' => null, 'message' => 'Interest not found'], 403);
            }
        } else {
            return response(['status' => 'error', 'code' => 403, 'user' => null, 'data' => null, 'message' => 'Event not found'], 403);
        }
    }
}
