<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

//ユーザーコントローラー
class AdminUserController extends Controller
{
    //検索
    public function user_list(Request $request)
    {
        $tab_name="ユーザー";
        $ope_type="user_list";
        //変更 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)         $input = request('input');
        else                                        $input = $request->all();
        if (empty($input['keyword']))               $input['keyword']=null;
        // 現在のページ番号を取得。指定がない場合は1を使用
        if (empty($input['page']))              $input['page'] = 1;

        $user_list = User::getUser_list(15,true,$input['page'],$input['keyword']);    //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,sort(1:,2:,3:,4:)
        //dd($user_list);
        $msg = request('msg');
        
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'user_list', 'input', 'msg'));
    }
    //削除
    public function user_del(Request $request)
    {
        $input = $request->all();
        //$msg = Music::delMusic($data['id']);
        $ret = Music::delMusic($input['id']);
        if($ret['error_code']==0)     $msg = "曲：". $input['name']. "を削除しました。";
        if($ret['error_code']==-1)    $msg = "曲の削除に失敗しました。";

        return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);
    }
    //変更
    public function user_chg(Request $request)
    {
        $input = $request->all();
        
        $ret = User::chgProfile($input);
        $msg = null;
        

        $input = $request->all();
        $msg=null;
        //music,Affiliate,Musicを一括で登録するため、事前にデータ確認
        //if(!$input['aff_link'])     $msg =  "アフィリエイトリンクを入力してください。";
        if(!$input['name'])         $msg =  "ユーザー名は必須情報です。";
        if(!$input['email'])        $msg =  "emailは必須情報です。";
        if(!$input['性別'])         $msg =  "性別は必須情報です。";
        if(!$input['公開状態'])     $msg =  "公開状態は必須情報です。";
        if(!$input['ﾒｰﾙ送信ﾌﾗｸﾞ'])   $msg =  "ﾒｰﾙ送信ﾌﾗｸﾞは必須情報です。";
        

        $ret = User::chgProfile($input);

        if($ret['error_code'] == 0)     $msg = "プロフィール情報を更新しました。";
        else                            $msg = "プロフィール情報の更新に失敗しました。";
        if($msg!==null)         return redirect()->route('admin-user-list', ['input' => $input, 'msg' => $msg]);


        return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);
    }
}
