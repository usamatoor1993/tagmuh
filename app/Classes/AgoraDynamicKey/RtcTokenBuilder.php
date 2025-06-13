<?php

namespace App\Classes\AgoraDynamicKey;

use DateTime;
use DateTimeZone;
use App\Classes\AgoraDynamicKey\AccessToken;

class RtcTokenBuilder
{
    const ROLE_ATTENDEE = 0;
    const ROLE_PUBLISHER = 1;
    const ROLE_SUBSCRIBER = 2;
    const ROLE_ADMIN = 101;

    /**
     * Build a token using a UID.
     *
     * @param string $appID The App ID issued by Agora.
     * @param string $appCertificate Certificate of the application registered in the Agora Dashboard.
     * @param string $channelName Unique channel name for the AgoraRTC session.
     * @param int $uid User ID. A 32-bit unsigned integer.
     * @param int $role The user role.
     * @param int $privilegeExpireTs Expiration timestamp in seconds since 1/1/1970.
     * @return string The generated token.
     */
    public static function buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpireTs)
    {
        return self::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpireTs);
    }

    /**
     * Build a token using a User Account.
     *
     * @param string $appID The App ID issued by Agora.
     * @param string $appCertificate Certificate of the application registered in the Agora Dashboard.
     * @param string $channelName Unique channel name for the AgoraRTC session.
     * @param string $userAccount The user account.
     * @param int $role The user role.
     * @param int $privilegeExpireTs Expiration timestamp in seconds since 1/1/1970.
     * @return string The generated token.
     */
    public static function buildTokenWithUserAccount($appID, $appCertificate, $channelName, $userAccount, $role, $privilegeExpireTs)
    {
        $token = AccessToken::init($appID, $appCertificate, $channelName, $userAccount);
        $privileges = AccessToken::PRIVILEGES;

        $token->addPrivilege($privileges["kJoinChannel"], $privilegeExpireTs);

        if (
            $role === self::ROLE_ATTENDEE ||
            $role === self::ROLE_PUBLISHER ||
            $role === self::ROLE_ADMIN
        ) {
            $token->addPrivilege($privileges["kPublishVideoStream"], $privilegeExpireTs);
            $token->addPrivilege($privileges["kPublishAudioStream"], $privilegeExpireTs);
            $token->addPrivilege($privileges["kPublishDataStream"], $privilegeExpireTs);
        }

        return $token->build();
    }
}

