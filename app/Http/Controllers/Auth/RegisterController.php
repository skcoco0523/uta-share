<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

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
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            //会員情報追加
            //'friend_code' => ['required', 'string', 'min:8', 'max:10'],   フレンドコードは後から生成するからここでは確認しない
            'gender' => ['required', 'in:0,1'],
            'birth_year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'prefectures' => ['required', 'string', 'min:2', 'max:10'],
        ]);
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
        $friend_code = $this->generateUniqueFriendCode();

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

    protected function generateUniqueFriendCode()
    {
        $friend_code = Str::random(8); // 8文字のランダムな文字列を生成
        // 重複確認
        while (User::where('friend_code', $friend_code)->exists()) {
            $friend_code = Str::random(8); // 重複した場合は再度生成
        }

        return $friend_code;
    }

    protected function registered(Request $request, $user)
    {
        //ユーザーへ登録完了メール送信
        $send_info = new \stdClass();
        $send_info->name = $request->name;
        $mail = $request->email;//送信先
        $tmpl='user_reg';//  送信内容
        mail_send($send_info, $mail, $tmpl);


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
        push_send(1,$send_info);
        
    }

}
