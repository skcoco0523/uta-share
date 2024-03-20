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
        $sort_flag = 0;
        $recommend = Recommend::getRecommend_list(5,false,null,$sort_flag);  //5件,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,sort_flag
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

}
