<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Models\UserLog; 

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // reCAPTCHAの検証
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 会員情報追加
            'gender' => ['required', 'in:0,1'],
            'birth_year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'prefectures' => ['required', 'string', 'min:2', 'max:10'],
            // reCAPTCHA 不正登録対応
            'g-recaptcha-response' => ['required', 'captcha'], 
        ]);

        // validatorが失敗した場合にログを記録
        
        if ($validator->fails()) {
            // reCAPTCHAに関連するエラーをチェック
            $recaptchaError = $validator->errors()->get('g-recaptcha-response');
            // reCAPTCHAエラーが含まれている場合のみログを記録
            if (!empty($recaptchaError)) {
                
                make_error_log("recaptcha.log", "=============================================");
                make_error_log("recaptcha.log", "ip=".request()->ip());
                make_error_log("recaptcha.log", "name=".$data['name']." email=".$data['email']);

                $send_info = [
                    'title' => '不正登録通知',
                    'body' => "ip：". request()->ip()."\nユーザー名：".$data['name']."\nemail：".$data['email'],
                    'url' => route('admin-user-search'),
                ];
                push_send(7,$send_info);
                push_send(13,$send_info);
            }
        }
        

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // 重複しないフレンドコードを生成する
        $friend_code = User::generateUniqueFriendCode();

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            //会員情報追加
            'friend_code' => $friend_code,
            'gender' => $data['gender'],
            'birthdate' => $data['birth_year'] . '-' . $data['birth_month'] . '-' . $data['birth_day'],
            'prefectures' => $data['prefectures'],
        ]);
    }

    protected function registered(Request $request, $user)
    {
        //ユーザーへ登録完了メール送信
        $send_info = new \stdClass();
        $send_info->name = $request->name;
        $mail = $request->email;//送信先
        $tmpl='user_reg';//  送信内容
        mail_send($send_info, $mail, $tmpl);


        UserLog::create_user_log("user_reg");

        //自身に通知する
        $now_user_cnt = User::count();

        $send_info = new \stdClass();
        $send_info->user_name = $request->name;
        $send_info->now_user_cnt = $now_user_cnt;
        $mail = "syunsuke.05.23.15@gmail.com";//送信先
        $tmpl='user_reg_notice';//  送信内容
        mail_send($send_info, $mail, $tmpl);

        $send_info = [
            'title' => '新規ユーザー登録',
            'body' => "ユーザー名：".$request->name."\n現在ユーザー数：". $now_user_cnt,
            'url' => route('admin-user-search'),
        ];
        push_send(7,$send_info);
        push_send(13,$send_info);
        
    }

}
