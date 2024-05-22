<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//HomeModelを追加 20240121 kanno
use App\Models\Home;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $homeModel = new Home();
        //ランキング
        $ranking = $homeModel->getRankingData("test");
        //プレイリスト
        //$playlist = $homeModel->getPlaylistData(auth()->id());              //追加開発必須
        //$playlist = $homeModel->getRankingData(NULL,"test");
        //$playlist = null;
        //おすすめ
        $recommend_mus = $homeModel->getRecommendList(0);//カテゴリ指定
        //$recommend_art = $homeModel->getRecommendList(1);//カテゴリ指定   アーティストは現在画像情報なし
        $recommend_alb = $homeModel->getRecommendList(2);//カテゴリ指定
        $recommend_pl = $homeModel->getRecommendList(3);//カテゴリ指定
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
