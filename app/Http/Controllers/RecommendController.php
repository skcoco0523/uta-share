<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Recommend;

class RecommendController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //ホームはゲストも表示可能に
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //おすすめ一覧
    public function recommend_list_show(Request $request)
    {
        $category = (int)$request->input('category');
        $page = $request->input('page', 1);
        
        $recommend_list = Recommend::getRecommend_list(10,true,$page,null,$category,false,true);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ、ｶﾃｺﾞﾘ,ｿｰﾄ,uﾕｰｻﾞｰ

        $msg = null;
        //dd($music);
        if($category==0) $recommend_list->name = "おすすめリスト：曲";
        if($category==1) $recommend_list->name = "おすすめリスト：アーティスト";
        if($category==2) $recommend_list->name = "おすすめリスト：アルバム";
        if($category==3) $recommend_list->name = "おすすめリスト：プレイリスト";

        //dd($recommend_list);

        if($recommend_list){
            return view('recommend_list_show', compact('recommend_list', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のデータが存在しません');
        }
    }
    //おすすめ詳細
    public function recommend_show(Request $request)
    {
        $recom_id = $request->input('id');
        $page = $request->input('page', 1);
        
        //$recommnd = Recommend::getRecommend_detail($recom_id);
        $recommend = Recommend::getRecommend_detail(10,true,$page,$recom_id);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        //dd($recommnd);
        $msg = null;
        //dd($music);
        if($recommend){
            return view('recommend_show', compact('recommend', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のデータが存在しません');
        }
    }
    
}
