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
    public static function getRequest_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null) 
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

                if (isset($keyword['search_mess'])) 
                    $sql_cmd = $sql_cmd->where('user_requests.message', 'like', '%'. $keyword['search_mess']. '%');

                if (isset($keyword['search_reply'])) 
                    $sql_cmd = $sql_cmd->where('user_requests.reply', 'like', '%'. $keyword['search_reply']. '%');
                
                $sql_cmd = $sql_cmd->select('user_requests.*','users.name');
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
        //dd($request);
        return $request;
    }
    //ユーザーリクエスト情報登録
    public static function createRequest($data) 
    {
        make_error_log("createRequest.log","-------start-------");
        try {
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
    //ユーザーリクエスト情報変更
    public static function chgRequeste($data)
    {
        make_error_log("chgRequeste.log","-------start-------");
        try {
            
            // 更新対象となるカラムと値を連想配列に追加
            $updateData = [];
            //if(isset($data['type']))            $updateData['type']     = $data['type'];      ユーザーからの内容は修正不可にする
            //if(isset($data['message']))         $updateData['message']  = $data['message'];   ユーザーからの内容は修正不可にする
            if(isset($data['reply']))           $updateData['reply']    = $data['reply'];
            if(isset($data['status']))          $updateData['status']   = $data['status'];

            make_error_log("chgRequeste.log","after_data=".print_r($data,1));
            self::where('id', $data['id'])->update($updateData);

            $user_id = self::where('id', $data['id'])->value('user_id');
            
            make_error_log("chgRequeste.log","success");
            return ['error_code' => 0, 'user_id' => $user_id];   //更新成功

        } catch (\Exception $e) {
            make_error_log("chgRequeste.log","failure");
            return ['error_code' => -1];   //更新失敗
        }
    }
}

