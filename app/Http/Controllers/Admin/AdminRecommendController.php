<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Recommend;

//おすすめコントローラー
class AdminRecommendController extends Controller
{
    public function home()
    {
        return view('admin.adminhome');
    }
    public function recommend_regist(Request $request)
    {
        $tab_name="おすすめ";
        $ope_type="recommend_reg";
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['name']);
        //$sort_flag = 0;
        //$category = null;
        $recommend = Recommend::getRecommend_list(null,false,null);  //5件,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,カテゴリ,sort_flag
        $msg = request('msg');
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'recommend', 'input', 'msg'));
    }
    //追加
    public function recommend_reg(Request $request)
    {
        $input = $request->only(['name', 'user_id', 'category']);
        $ret = Recommend::createRecommend($input);
        if($ret['error_code']==0){
             $msg = "おすすめ：{$input['name']} を追加しました。";
             $input=null;   //データ登録成功時 初期化
        }
        if($ret['error_code']==1) $msg = "登録名を入力してください。";
        if($ret['error_code']==2) $msg = "カテゴリを選択してください。";
        if($ret['error_code']==-1) $msg = "おすすめ：{$input['name']} の追加に失敗しました。";
        return redirect()->route('admin-recommend-reg', ['input' => $input, 'msg' => $msg]);
    }
    //削除
    public function recommend_del(Request $request)
    {
        $input = $request->only(['recom_id','recom_name','keyword','category']);
        $ret = Recommend::delRecommend($input);
        if($ret['error_code']==0)   $msg = "おすすめ：{$input['recom_name']} を削除しました。";
        if($ret['error_code']==-1)  $msg = "おすすめ：{$input['recom_name']} の削除に失敗しました。";
        return redirect()->route('admin-recommend-search', ['input' => $input, 'msg' => $msg]);
    }
    //検索
    public function recommend_search(Request $request)
    {
        $tab_name="おすすめ";
        $ope_type="recommend_search";
        
        //変更 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)             $input = request('input');
        else                                            $input = $request->only(['keyword', 'category']);
        if (empty($input['keyword']))                   $input['keyword']=null;
        //0がはじかれてしまうためemptyは使わない
        if (!isset($input['category']))                 $input['category'] = null;
        elseif ($input['category'] === '')              $input['category'] = null;
        
        //$sort_flag = ($input['category']!=null) ?       1:0;
        if($input['category']!=null){
            //カテゴリ検索時は表示順を切り替えるため件数を15に増やす
            $recommend = Recommend::getRecommend_list(15,true,$input['keyword'],$input['category'],1);  //5件,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,category,表示順ソート
        }else{
            $recommend = Recommend::getRecommend_list(null,true,$input['keyword'],$input['category'],0);  //5件,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,category,
        }
        $msg = request('msg');
        $msg = ($msg==NULL && $input['keyword'] !==null && count($recommend) === 0) ? "検索結果が0件です。" : $msg;
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'recommend', 'input', 'msg'));
    }
    //変更
    public function recommend_chg(Request $request)
    {
        $input = $request->only(['id', 'name', "category",'disp_flag']);
        $ret = Recommend::chgRecommend($input);
        
        if($ret['error_code']==1) $msg = "テーブルから変更対象を選択してください。";
        if($ret['error_code']==2) $msg = "登録名を入力してください。";
        if($ret['error_code']==3) $msg = "表示有無を選択してください。";
        if($ret['error_code']==-1) $msg = "おすすめ：{$input['name']} の変更に失敗しました。";
        if($ret['error_code']==0) $msg = "おすすめ：{$input['name']} を更新しました。";

        $input = $request->only(['keyword','category']);

        return redirect()->route('admin-recommend-search', ['input' => $input, 'msg' => $msg]);
    }
    //表示順変更用
    public function recommend_chg_fnc(Request $request)
    {
        make_error_log("recommend_chg_fnc.log","-----start-----");
        $input = $request->only(['fnc', 'id', 'category']);
        //dd($input);
        $msg=null;
        make_error_log("recommend_chg_fnc.log","fnc=".$input['fnc']." recom_id=".$input['id']);
        $ret = Recommend::chgsortRecommend($input);
        if($ret['error_code']==1)    $msg = "必要な情報が不足しています。";
        if($ret['error_code']==-1)    $msg = "表示順の変更に失敗しました。";
        if($ret['error_code']==0)    $msg = "表示順を変更しました。";

        return redirect()->route('admin-recommend-search', ['input' => $input, 'msg' => $msg]);
    }
}
