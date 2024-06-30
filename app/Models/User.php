<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Auth;
use App\Models\Friendlist;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'friend_code',          //会員情報追加
        'gender',               //会員情報追加
        'birthdate',            //会員情報追加
        'release_flag',         //フレンドへの公開規制、
        'mail_flag',            //メール送信規制
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        //会員情報追加
        'birthdate' => 'date',
    ];
    //プロフィール情報変更
    public static function chgProfile($data)
    {
        make_error_log("chgProfile.log","-------start-------");
        try {
            
            // 更新対象となるカラムと値を連想配列に追加
            $updateData = [];
            if(isset($data['name']))            $updateData['name']         = $data['name'];
            if(isset($data['email']))           $updateData['email']        = $data['email'];
            if(isset($data['birthdate']))       $updateData['birthdate']    = $data['birthdate'];
            if(isset($data['release_flag']))    $updateData['release_flag'] = $data['release_flag'];
            if(isset($data['mail_flag']))       $updateData['mail_flag']    = $data['mail_flag'];
            
            make_error_log("chgProfile.log","after_data=".print_r($data,1));
            User::where('id', $data['id'])->update($updateData);

            make_error_log("chgProfile.log","success");
            return ['error_code' => 0];   //更新成功

        } catch (\Exception $e) {
            make_error_log("chgProfile.log","failure");
            return ['error_code' => -1];   //更新失敗
        }
    }


}
