<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class UserRequest extends Model
{
    use HasFactory;
    
    protected $table = 'user_requests';
    protected $fillable = ['user_id', 'type', 'message'];     //一括代入の許可

    //ユーザーリクエスト情報取得
    public static function getRequest($disp_cnt=null,$pageing=false,$page=1,$keyword=null) 
    {
        $sql_cmd = DB::table('user_requests');
        if($keyword){
            //ユーザーによる検索
            if (isset($keyword['login_id'])) {
                $sql_cmd = $sql_cmd->where('user_id',$keyword['login_id']);
                
            //管理者による検索
            } else{
                $sql_cmd = $sql_cmd->leftJoin('users', 'user_requests.user_id', '=', 'users.id');
                if (isset($keyword['search_type'])) 
                    $sql_cmd = $sql_cmd->where('user_requests.type', $keyword['search_type']);

                if (isset($keyword['search_status'])) 
                    $sql_cmd = $sql_cmd->where('user_requests.status', $keyword['search_status']);
            }
        }
        // ページング・取得件数指定・全件で分岐
        if ($pageing){
            if ($disp_cnt === null) $disp_cnt=5;
            $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        }                       
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $request = $sql_cmd;

        return $request;
    }
    //ユーザーリクエスト情報登録
    public static function createRequest($data) 
    {
        make_error_log("createRequest.log","-------start-------");
        try {
            //データチェック
            $data['user_id']           = Auth::id();
            $data['type']              = get_input($data,"type");
            $data['message']           = get_input($data,"message");

            $error_code = 0;
            if(!isset($data['user_id']))   $error_code = 1;   //データ不足
            if(!isset($data['type']))      $error_code = 2;   //データ不足
            if(!isset($data['message']))   $error_code = 3;   //データ不足
            
            if($error_code){
                make_error_log("createRequest.log","error_code=".$error_code);
                return ['id' => null, 'error_code' => $error_code];
            }

            $request = self::create($data);
            $request_id = $request->id;
            make_error_log("createRequest.log","success");
            return ['id' => $request_id, 'error_code' => $error_code];   //追加成功

        } catch (\Exception $e) {
            make_error_log("createRequest.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }

    }
}

