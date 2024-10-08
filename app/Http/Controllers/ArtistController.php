<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Artist;
use App\Models\Album;
use App\Models\Music;
use App\Models\Playlist;

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
    //アーティスト詳細
    public function artist_show(Request $request)
    {
        $artist     = Artist::getartist_detail($request->only(['id']));  //mus_id
        if($artist){
            $album      = Album::getAlbum_list(10,false,null,null,$artist->id);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,art_id
            $music      = Music::getMusic_list(10,false,null,null,$artist->id);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,art_id
            //画像取得のためuser_serchを引き渡す
            $keyword['keyword'] = $artist->name;
            $keyword['user_serch'] = 1;
            $playlist   = Playlist::getPlaylist_list(10,false,null,$keyword,1);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,ﾕｰｻﾞｰ
            
        }

        $msg = null;
        if($artist){
            return view('artist_show', compact('artist', 'album', 'music', 'playlist', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のアーティストが存在しません');
        }
    }
}
