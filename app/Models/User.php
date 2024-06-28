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
        //会員情報追加
        'friend_code',
        'gender',
        'birthdate',
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
            if(isset($data['name']))        $updateData['name']         = $data['name'];
            if(isset($data['email']))       $updateData['email']        = $data['email'];
            if(isset($data['birthdate']))   $updateData['birthdate']    = $data['birthdate'];
            if(isset($data['name']))        $updateData['name']         = $data['name'];
            
            make_error_log("chgProfile.log","after_data=".print_r($data,1));
            User::where('id', $data['id'])->update($updateData);

            make_error_log("chgProfile.log","success");
            return ['error_code' => 0];   //更新成功

        } catch (\Exception $e) {
            make_error_log("chgProfile.log","failure");
            return ['error_code' => -1];   //更新失敗
        }
    }
    public static function findByFriendCode($friendCode,$user_id)
    {
        $user = self::where('friend_code', $friendCode)->select('id', 'name')->first();
        if($user && ($user_id != $user->id)){
            //フレンド申請状態を確認
            $user->status = Friendlist::getFriendStatus(Auth::id(), $user->id);
            return $user;

        }else{
            return null;
        }
    }


}
