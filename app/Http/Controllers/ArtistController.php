<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Artist;

class ArtistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //アーティスト情報はゲストも表示可能に
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //アーチスト詳細
    public function artist_show(Request $request)
    {
        $artist = Artist::getartist_detail($request->only(['id']));  //mus_id
        $msg = null;
        if($artist){
            return view('artist_show', compact('artist', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のアーティストが存在しません');
        }
    }
}
