<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgoraVideoController extends Controller
{
    public function token(Request $request)
    {

        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = Auth::user()->first_name;
        $role = 0;
        // $role="user";
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        return response(['token'=> $token]);
    }
}
