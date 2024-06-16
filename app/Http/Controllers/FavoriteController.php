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
        $favorite_list = array();
        for( $i=0; $i<4; $i++){
            $favorite_list[$i] = Favorite::getFavorite(Auth::id(),$i);
        }
        $msg = null;

        //dd($favorite_list);
        if($favorite_list){
            return view('favorite_show', compact('favorite_list', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のデータが存在しません');
        }
    }
    //お気に入り登録
    public function favorite_chg(Request $request)
    {
        $category = $request->input('category');
        $detail_id = $request->input('detail_id');
        $type = $request->input('type');    //add or del
        $ret = Favorite::chgFavorite(Auth::id(),$category,$detail_id,$type);
        
        return $ret;
        }
}
