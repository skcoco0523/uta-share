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
        $ranking['fav_mus'] = Ranking::getFavoriteRanking(10,false,null,"mus");
        //おすすめ
        $recommend_mus = Recommend::getRecommend_list(10,false,null,['search_category'=>0]);//件数,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ
        $recommend_alb = Recommend::getRecommend_list(10,false,null,['search_category'=>2]);//件数,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ
        $recommend_pl  = Recommend::getRecommend_list(10,false,null,['search_category'=>3]);//件数,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｶﾃｺﾞﾘ

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
