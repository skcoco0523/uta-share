<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class UserController extends Controller
{
    public function password_reset_mailsend(Request $request)
    {
        //echo "start";
        $log_file = __FUNCTION__ . '.log';
        $mail = $request->email;
        make_error_log($log_file,"-------start-------");
        make_error_log($log_file,"chk_address=".$mail);
        // 一致するユーザーを検索
        $user = DB::table('users')->where('email', $mail)->first();
        //echo $send_addr;
        
        if($user){

            // パスワードリセットトークンを生成してDBに保存する
            $token = Str::random(60);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $mail],
                ['email' => $mail, 'token' => Hash::make($token), 'created_at' => now()]
                //['email' => $mail, 'token' => $token, 'created_at' => now()]
            );
            $send_info = new \stdClass();
            $send_info->name = $user->name;
            $send_info->mail = $mail;
            $send_info->token = $token;
            //$mail = $request->email; 取得済み
            
            $tmpl='password_reset';//  送信内容
            

            //パスワードリセットメール送信
            mail_send($send_info, $mail, $tmpl);
            // メール送信成功メッセージをセッションに設定
            Session::flash('status', __('passwords.sent'));
        } else {
            // ユーザーが見つからない場合のメッセージをセッションに設定
            Session::flash('status', __('passwords.user'));
        }

        // リダイレクト
        return redirect()->back();
    }
}
