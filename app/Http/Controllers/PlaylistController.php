<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Playlist;

class PlaylistController extends Controller
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
    //プレイリスト詳細
    public function playlist_show(Request $request)
    {
        $playlist = Playlist::getplaylist_detail($request->only(['id']));  //mus_id
        $msg = null;
        //dd($playlist);
        if($playlist){
            return view('playlist_show', compact('playlist', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のプレイリストが存在しません');
        }
    }
    
    //マイプレイリストは分ける

    
}
