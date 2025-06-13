<?php

namespace App\Classes\AgoraDynamicKey;

use DateTime;
use DateTimeZone;

class AccessToken
{
    const PRIVILEGES = [
        'kJoinChannel' => 1,
        'kPublishAudioStream' => 2,
        'kPublishVideoStream' => 3,
        'kPublishDataStream' => 4,
        'kRtmLogin' => 1000,
    ];

    public $appID;
    public $appCertificate;
    public $channelName;
    public $uid;
    public $message;

    public function __construct()
    {
        $this->message = new Message();
    }

    public static function init($appID, $appCertificate, $channelName, $uid)
    {
        $service = new self();

        if (!$service->isNonEmptyString('appID', $appID) ||
            !$service->isNonEmptyString('appCertificate', $appCertificate) ||
            !$service->isNonEmptyString('channelName', $channelName)) {
            return null;
        }

        $service->appID = $appID;
        $service->appCertificate = $appCertificate;
        $service->channelName = $channelName;
        $service->setUid($uid);
        $service->message = new Message();

        return $service;
    }

    public function setUid($uid)
    {
        $this->uid = $uid === 0 ? '' : (string) $uid;
    }

    public function addPrivilege($key, $expireTimestamp)
    {
        $this->message->privileges[$key] = $expireTimestamp;
        return $this;
    }

    public function build()
    {
        $msg = $this->message->packContent();
        $val = array_merge(
unpack('C*', $this->appID),
unpack('C*', $this->channelName),
unpack('C*', $this->uid),
$msg
        );

        $sig = hash_hmac('sha256', implode(array_map('chr', $val)), $this->appCertificate, true);
        $crc_channel_name = crc32($this->channelName) & 0xffffffff;
        $crc_uid = crc32($this->uid) & 0xffffffff;

        $content = array_merge(
            unpack('C*', $this->packString($sig)),
            unpack('C*', pack('V', $crc_channel_name)),
            unpack('C*', pack('V', $crc_uid)),
            unpack('C*', pack('v', count($msg))),
            $msg
        );

        $version = '006';
        return $version . $this->appID . base64_encode(implode(array_map('chr', $content)));
    }

    private function isNonEmptyString($name, $str)
    {
        if (is_string($str) && $str !== '') {
            return true;
        }
        throw new \InvalidArgumentException($name . ' should be a non-empty string.');
    }

    private function packString($value)
    {
        return pack('v', strlen($value)) . $value;
    }
}

class Message
{
    public $salt;
    public $ts;
    public $privileges;

    public function __construct()
    {
        $this->salt = rand(0, 100000);
        $date = new DateTime('now', new DateTimeZone('UTC'));
        $this->ts = $date->getTimestamp() + 24 * 3600;
        $this->privileges = [];
    }

    public function packContent()
    {
        $buffer = unpack('C*', pack('V', $this->salt));
        $buffer = array_merge($buffer, unpack('C*', pack('V', $this->ts)));
        $buffer = array_merge($buffer, unpack('C*', pack('v', count($this->privileges))));
        foreach ($this->privileges as $key => $value) {
            $buffer = array_merge($buffer, unpack('C*', pack('v', $key)));
            $buffer = array_merge($buffer, unpack('C*', pack('V', $value)));
        }
        return $buffer;
    }

    public function unpackContent($msg)
    {
        $pos = 0;
        $this->salt = unpack('V', substr($msg, $pos, 4))[1];
        $pos += 4;
        $this->ts = unpack('V', substr($msg, $pos, 4))[1];
        $pos += 4;
        $size = unpack('v', substr($msg, $pos, 2))[1];
        $pos += 2;

        $this->privileges = [];
        for ($i = 0; $i < $size; $i++) {
            $key = unpack('v', substr($msg, $pos, 2))[1];
            $pos += 2;
            $value = unpack('V', substr($msg, $pos, 4))[1];
            $pos += 4;
            $this->privileges[$key] = $value;
        }
    }
}
