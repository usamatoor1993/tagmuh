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

        $appID = config('services.agora.app_id');
        $appCertificate = config('services.agora.app_certificate');
        $channelName = $request->channelName;
        // $user = Auth::user()->first_name;
        $uid= 0;
        $role = RtcTokenBuilder::ROLE_ATTENDEE; // or RolePublisher, RoleSubscriber, etc.
        // $role="user";
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);

        return response(['token'=> $token]);
    }
}
