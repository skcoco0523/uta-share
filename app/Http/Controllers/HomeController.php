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
        //$playlist = $homeModel->getPlaylistData(auth()->id());
        //$playlist = $homeModel->getRankingData(NULL,"test");
        $playlist = null;
        //おすすめ
        $recommend = $homeModel->getRecommendData();
        //return view('home');
        //dd($ranking);
        
        return view('home', compact('ranking', 'playlist', 'recommend'));

    }
    public function dashboard()
    {
        return view('dashboard');
    }

    
}
