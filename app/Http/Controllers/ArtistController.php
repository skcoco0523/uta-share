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
            $music      = Music::getMusic_list(10,false,null,null,$artist->id);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
            $playlist   = Playlist::getPlaylist_list(10,false,null,$artist->name,1);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ  
            // art_idが一致するデータのみに加工 念のため
            $filter_Albums = [];
            $filter_Music = [];
            foreach ($album as $alb) {
                if ($alb->art_id == $artist->art_id) $filter_Albums[] = $alb;
            }
            foreach ($music as $mus) {
                if ($mus->art_id == $artist->art_id) $filter_Music[] = $mus;
            }
            $album = $filter_Albums;
            $music = $filter_Music;
        }

        $msg = null;
        if($artist){
            return view('artist_show', compact('artist', 'album', 'music', 'playlist', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のアーティストが存在しません');
        }
    }
}
