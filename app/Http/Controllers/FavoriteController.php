<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
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

    //お気に入り表示
    public function favorite_show()
    {
        $table = ["mus","alb","pl"];
        $favorite_list = array();
        for( $i=0; $i<count($table); $i++){
            $favorite_list[$table[$i]] = Favorite::getFavorite(Auth::id(),$table[$i]);
        }
        //dd($favorite_list);
        $msg = null;
        
        if($favorite_list){
            return view('favorite_show', compact('favorite_list', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のデータが存在しません');
        }
    }
    //お気に入り登録
    public function favorite_chg(Request $request)
    {
        $table = $request->input('table');
        $detail_id = $request->input('detail_id');
        $type = $request->input('type');    //add or del
        $ret = Favorite::chgFavorite(Auth::id(),$table,$detail_id,$type);
        
        return $ret;
    }
}
