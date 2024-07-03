<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Home;
use App\Models\Ranking;
use App\Models\Recommend;

class HomeController extends Controller
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
    public function index()
    {
        //ランキング
        $ranking['fav_mus'] = Ranking::getRanking("favorite", "mus");
        //プレイリスト
        //$playlist = $homeModel->getPlaylistData(auth()->id());              //追加開発必須
        //$playlist = $homeModel->getRankingData(NULL,"test");
        //$playlist = null;
        //おすすめ
        //$recommend_mus = Recommend::getUserRecommendList(10,false,null,0);//件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ
        //$recommend_art = $homeModel->getRecommendList(1);//件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ  アーティストは現在画像情報なし
        //$recommend_alb = Recommend::getUserRecommendList(10,false,null,2);//件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ
        //$recommend_pl = Recommend::getUserRecommendList(10,false,null,3);//件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ

        $recommend_mus = Recommend::getRecommend_list(10,false,null,null,0,true,true);//件数,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ,ｿｰﾄ,ユーザー用フラグ
        $recommend_alb = Recommend::getRecommend_list(10,false,null,null,2,true,true);//件数,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ,ｿｰﾄ,ユーザー用フラグ
        $recommend_pl = Recommend::getRecommend_list(10,false,null,null,3,true,true);//件数,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ,ｿｰﾄ,ユーザー用フラグ

        //return view('home');
        //dd($recommend_mus);
        
        //return view('home', compact('ranking', 'playlist', 'recommend_mus', 'recommend_art', 'recommend_alb', 'recommend_pl'));
        return view('home', compact('ranking', 'recommend_mus', 'recommend_alb', 'recommend_pl'));

    }
    public function dashboard()
    {
        return view('dashboard');
    }

    
}
