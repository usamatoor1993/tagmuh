<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
