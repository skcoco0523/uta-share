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
        $recom_id = $request->input('recom_id');
        $page = $request->input('page', 1);
        //$recommnd = Recommend::getRecommend_detail($recom_id);
        $recommnd = Recommend::getRecommend_detail(10,true,$page,$recom_id);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        //dd($recommnd);
        $msg = null;
        //dd($music);
        if($recommnd){
            return view('recommend_list_show', compact('recommnd', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のデータが存在しません');
        }
    }
    
}
