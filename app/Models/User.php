<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Auth;

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
            //dd($data);
            
            if(isset($data['name']))        $updateData['name']         = $data['name'];
            if(isset($data['email']))       $updateData['email']        = $data['email'];
            if(isset($data['birthdate']))   $updateData['birthdate']    = $data['birthdate'];
            if(isset($data['name']))        $updateData['name']         = $data['name'];
            //dd($updateData);
        
            make_error_log("chgProfile.log","after_data=".print_r($data,1));
            // musicデータ更新
            /*  クエリビルダではupdated_atが自動更新されない
            DB::table('musics')->where('id', $updateData['id'])->update($updateData);
            */
            User::where('id', $data['id'])->update($updateData);

            make_error_log("chgProfile.log","success");
            return ['error_code' => 0];   //更新成功

        } catch (\Exception $e) {
            make_error_log("chgProfile.log","failure");
            return ['error_code' => -1];   //更新失敗
        }
    }
}
