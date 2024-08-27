<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

//ユーザーコントローラー
class AdminUserController extends Controller
{
    //検索
    public function user_search(Request $request)
    {
        //変更 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)         $input = request('input');
        else                                        $input = $request->all();
        
        $input['search_name']           = get_input($input,"search_name");
        $input['search_email']          = get_input($input,"search_email");
        $input['search_friendcode']     = get_input($input,"search_friendcode");
        $input['search_gender']         = get_input($input,"search_gender");
        $input['search_release_flag']   = get_input($input,"search_release_flag");
        $input['search_mail_flag']      = get_input($input,"search_mail_flag");
        $input['search_admin_flag']     = get_input($input,"search_admin_flag");

        $input['page']              = get_input($input,"page");

        $user_list = User::getUser_list(15,true,$input['page'],$input);    //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,sort(1:,2:,3:,4:)
        //dd($user_list);
        $msg = request('msg');
        
        return view('admin.admin_home', compact('user_list', 'input', 'msg'));
    }
    //削除
    /*
    public function user_del(Request $request)
    {
        $input = $request->all();
        //$msg = Music::delMusic($data['id']);
        $ret = Music::delMusic($input['id']);
        if($ret['error_code']==0)     $msg = "曲：". $input['name']. "を削除しました。";
        if($ret['error_code']==-1)    $msg = "曲の削除に失敗しました。";

        return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);
    }
    */
    //変更　開発必須
    public function user_chg(Request $request)
    {
        $input = $request->all();
        $input['name']              = get_input($input,"name");
        $input['email']             = get_input($input,"email");
        $input['birthdate']         = get_input($input,"birthdate");
        $input['gender']            = get_input($input,"gender");
        $input['release_flag']      = get_input($input,"release_flag");
        $input['mail_flag']         = get_input($input,"mail_flag");

        $msg = null;
        if(!isset($input['name']))                $msg =  "ユーザー名は必須情報です。";
        if(!isset($input['email']))              $msg =  "emailは必須情報です。";
        if(!isset($input['birthdate']))          $msg =  "性別は必須情報です。";
        if(!isset($input['gender']))             $msg =  "性別は必須情報です。";
        if(!isset($input['release_flag']))       $msg =  "公開状態は必須情報です。";
        if(!isset($input['mail_flag']))          $msg =  "ﾒｰﾙ送信ﾌﾗｸﾞは必須情報です。";
        
        if(!$msg){
            $ret = User::chgProfile($input);
            if($ret['error_code'] == 0)     $msg = "プロフィール情報を更新しました。";
            else                            $msg = "プロフィール情報の更新に失敗しました。";
        }else{
            return redirect()->route('admin-user-search', ['input' => $input, 'msg' => $msg]);
        }

        return redirect()->route('admin-user-search', ['input' => $input, 'msg' => $msg]);
    }
}
