<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Str;
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
        'friend_code',          //フレンドコード
        'gender',               //性別
        'prefectures',          //都道府県
        'birthdate',            //生年月日
        'release_flag',         //フレンドへの公開規制、
        'mail_flag',            //メール送信規制
        'line_id',              //lineログインID
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'line_id',              //lineログインID
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
    
    //ユーザートークン生成
    public function createTokenForUser($name = 'Default Token Name')
    {
        return $this->createToken($name)->plainTextToken;
    }

    //ユーザーリスト取得
    public static function getUser_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null,$sort_flag=false)
    {
        $sql_cmd = DB::table('users');
        if($keyword){
            if (isset($keyword['search_name'])) 
                $sql_cmd = $sql_cmd->where('name', 'like', '%'. $keyword['search_name']. '%');

            if (isset($keyword['search_email'])) 
                $sql_cmd = $sql_cmd->where('email', 'like', '%'. $keyword['search_email']. '%');

            if (isset($keyword['search_friendcode'])) 
                $sql_cmd = $sql_cmd->where('friend_code', 'like', '%'. $keyword['search_friendcode']. '%');

            if (isset($keyword['search_gender'])) 
                $sql_cmd = $sql_cmd->where('gender',$keyword['search_gender']);

            if (isset($keyword['search_release_flag'])) 
                $sql_cmd = $sql_cmd->where('release_flag',$keyword['search_release_flag']);

            if (isset($keyword['search_mail_flag'])) 
                $sql_cmd = $sql_cmd->where('mail_flag',$keyword['search_mail_flag']);

            if (isset($keyword['search_admin_flag'])) 
                $sql_cmd = $sql_cmd->where('admin_flag',$keyword['search_admin_flag']);   
        }

        // ページング・取得件数指定・全件で分岐
        if ($pageing){
            if ($disp_cnt === null) $disp_cnt=5;
            $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        }                       
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $user_list = $sql_cmd;
        foreach($user_list as $key => $user){
            //ログイン回数、最終ﾛｸﾞｲﾝ日取得
            $login_data = DB::table('user_logs')
                            ->selectRaw('COUNT(*) as login_count, MAX(created_at) as last_login_date')
                            ->where('type', 'like', '%login%')->where('user_id', $user->id)->first();
            $user->login_cnt        = $login_data->login_count;
            $user->last_login_date  = $login_data->last_login_date;

            //その他情報取得
            $user->favorite_cnt = DB::table('favorite_mus')->where('user_id', $user->id)->count();
            $user->friend_cnt   = DB::table('friendlists')->where('user_id', $user->id)->where('status', 1)->count();
            $user->playlist_cnt = DB::table('playlist')->where('user_id', $user->id)->count();
        }

        return $user_list;
    }

    //プロフィール情報取得
    public static function profile_get($user_id)
    {
        $myprofile = Auth::user();          //ログインしているユーザー
        $profile = User::find($user_id);    //確認対象のユーザー

        if(!$profile) return null;
        //ログインユーザーと異なる場合、フレンド状態等を取得する
        if($myprofile->id != $profile->id){
            $profile->friend_status = Friendlist::getFriendStatus($myprofile->id, $user_id);
        }
        return $profile;
    }
    //プロフィール情報変更
    public static function chgProfile($data)
    {
        try {
            make_error_log("chgProfile.log","-------start-------");
            $user = User::where('id', $data['id'])->first();
            if($user){
                // 更新対象となるカラムと値を連想配列に追加
                $updateData = [];
                if ($user->name != $data['name'])                   $updateData['name']         = $data['name']; 
                if ($user->email != $data['email'])                 $updateData['email']        = $data['email']; 
                if ($user->birthdate->format('Y-m-d') != $data['birthdate'])
                                                                    $updateData['birthdate']    = $data['birthdate']; 
                if ($user->prefectures != $data['prefectures'])     $updateData['prefectures']  = $data['prefectures']; 
                if ($user->gender != $data['gender'])               $updateData['gender']       = $data['gender']; 
                if ($user->release_flag != $data['release_flag'])   $updateData['release_flag'] = $data['release_flag']; 
                if ($user->mail_flag != $data['mail_flag'])         $updateData['mail_flag']    = $data['mail_flag']; 

                make_error_log("chgProfile.log","chg_data=".print_r($updateData,1));
                if(count($updateData) > 0){
                    User::where('id', $data['id'])->update($updateData);
                    make_error_log("chgProfile.log","success");
                }
                
                return ['id' => $data['id'], 'error_code' => 0];   //更新成功

            } else {
                return ['id' => null, 'error_code' => -1];   //更新失敗
            }

        } catch (\Exception $e) {
            make_error_log("chgProfile.log","failure");
            return ['error_code' => -1];   //更新失敗
        }
    }

    //フレンドコード生成
    public static function generateUniqueFriendCode()
    {
        $friend_code = Str::random(8); // 8文字のランダムな文字列を生成
        // 重複確認
        while (User::where('friend_code', $friend_code)->exists()) {
            $friend_code = Str::random(8); // 重複した場合は再度生成
        }

        return $friend_code;
    }


}
