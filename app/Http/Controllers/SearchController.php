<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Search;
use App\Models\Artist;
use App\Models\Music;
use App\Models\Album;
use App\Models\Playlist;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //検索はゲストも表示可能に
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //検索
    public function search_show(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['keyword']))           $input['keyword']=null;

        //ログインしているユーザーは検索履歴を表示
        if(Auth::check()){
            $history = collect();
        }else{
            $history = collect();
        }
        //dd($history);
        $msg = null;
        
        return view('search_show', compact('history','input', 'msg'));
    }
    //検索結果一覧 
    public function search_list_show(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['keyword']))           $input['keyword']=null;

        //$table = ["art","mus","alb","pl"];
        $search_list["art"] = Artist::getArtist_list(5,false,1,$input['keyword']);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $search_list["mus"] = Music::getMusic_list(5,false,1,$input['keyword']);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $search_list["alb"] = Album::getAlbum_list(5,false,1,$input['keyword']);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $search_list["pl"] = Playlist::getPlaylist_list(5,false,1,$input['keyword'],1);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,管理者登録フラグ

        $msg = null;
        //dd($playlist);
        
        return view('search_list_show', compact('search_list','input', 'msg'));
    }
    //検索結果補足一覧取得
    public function search_suggestions(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['keyword']))           $input['keyword']=null;
        
        //$table = ["art","mus","alb","pl"];
        $search_list["art"] = Artist::getArtist_list(10,false,1,$input['keyword']);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $search_list["mus"] = Music::getMusic_list(10,false,1,$input['keyword']);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $search_list["alb"] = Album::getAlbum_list(10,false,1,$input['keyword']);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $search_list["pl"] = Playlist::getPlaylist_list(10,false,1,$input['keyword'],1);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ     

        $suggestions=array();
        foreach($search_list as $list){
            foreach($list as $data){
                $suggestions[] = $data->name;
            }
        }
        $suggestions = array_unique($suggestions);

        //検索補足データを返す
        return response()->json($suggestions);
    }
}