<?php

//namespace App\Helpers;

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use App\Models\UserDevice;


// app/Helpers/PushNotification.php

// プッシュ通知関数
if (! function_exists('push_send')) {
    //$send_info(title,body)
    function push_send($user_id, $send_info){
        return PushNotification::sendNotification($user_id, $send_info);
    }
}

function urlSafeBase64Decode($base64Url) {
    $base64 = strtr($base64Url, '-_', '+/');
    return base64_decode($base64);
}
class PushNotification
{
    
    public static function sendNotification($user_id, $send_info)
    {
        $user_devices = UserDevice::getUserDevices($user_id);
    
        // URL-safe Base64 を通常の Base64 に変換してからデコード
        $publicKey = strtr($user_devices['public_key'], '-_', '+/');
        $authToken = strtr($user_devices['auth_token'], '-_', '+/');
    
        $subscription = Subscription::create([
            'endpoint' => $user_devices['endpoint'],
            //'publicKey' => base64_decode($publicKey), // デコードしたデータを使用
            //'authToken' => base64_decode($authToken), // デコードしたデータを使用
            'publicKey' => $user_devices['public_key'], 
            'authToken' => $user_devices['auth_token'], 
        ]);
    
        $vapidPublicKey = strtr(config('webpush.vapid.public_key'), '-_', '+/');
        $vapidPrivateKey = strtr(config('webpush.vapid.private_key'), '-_', '+/');
    
        $auth = [
            'VAPID' => [
                'subject' => config('webpush.vapid.subject'), // 管理者のメールアドレスを設定ファイルから取得
                //'publicKey' => base64_decode($vapidPublicKey), // URL-safe Base64 からデコード
                //'privateKey' => base64_decode($vapidPrivateKey), // URL-safe Base64 からデコード
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ];
    
        $envPublicKey = base64_decode(strtr(config('webpush.vapid.public_key'), '-_', '+/'));
        $dbPublicKey = base64_decode(strtr($user_devices['public_key'], '-_', '+/'));
        
        make_error_log("sendNotification.log", "Decoded envPublicKey=" . $envPublicKey);
        make_error_log("sendNotification.log", "Decoded dbPublicKey=" . $dbPublicKey);


        $publicKeyFromEnv = urlSafeBase64Decode(config('webpush.vapid.public_key'));
        $privateFromEnv = urlSafeBase64Decode(config('webpush.vapid.private_key'));
        $publicKeyFromDB = base64_decode($user_devices['public_key']);
        $authTokenFromDB = base64_decode($user_devices['auth_token']);

        //dd(bin2hex($publicKeyFromEnv),bin2hex($privateFromEnv),bin2hex($publicKeyFromDB),bin2hex($authTokenFromDB));
        // デコード後の結果をログに出力
    



        $send_info = [
            'title' => 'New Message',
            'body' => 'You have received a new message.',
            'icon' => '/path/to/icon.png',
            'url' => 'https://example.com/messages',
            'badge' => '/path/to/badge.png',
            'data' => [
                'some_key' => 'some_value',
                'another_key' => 'another_value'
            ]
        ];

        //dd($auth);
        //dd($subscription,$auth);
        $webPush = new WebPush($auth);
        try {
            $report = $webPush->sendOneNotification(
                $subscription,
                'push通知の本文'
                //json_encode($send_info)
            );
            make_error_log("sendNotification.log", "report:".$report);
        } catch (\Exception $e) {
            // 例外の詳細をログに出力
            Log::error('Push通知送信エラー: ' . $e->getMessage());
            // 例外のスタックトレースも出力する場合
            Log::error($e->getTraceAsString());
        }
        dd($report);
        dd($report->isSuccess());

        return $report->isSuccess();
    }
}

