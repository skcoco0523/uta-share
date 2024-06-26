<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Music;

class MusicController extends Controller
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
    //曲詳細
    public function music_show(Request $request)
    {
        $music = Music::getMusic_detail($request->only(['id']));  //mus_id
        $msg = null;
        //dd($music);
        if($music){
            return view('music_show', compact('music', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当の曲が存在しません');
        }
    }
    
}
