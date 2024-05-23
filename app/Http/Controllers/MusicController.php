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
        $this->middleware('auth');
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
    //曲一覧                                                        修正必須
    public function music_list_show(Request $request)
    {
        //$music = Music::getMusic_detail($request->only(['id']));  //mus_id
        $music = $request->only(['list']);
        $msg = null;
        //dd($music);
        if($music['list']['count']){
            return view('music_list_show', compact('music', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当の曲が存在しません');
        }
    }

    
}
