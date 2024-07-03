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
    public function favorite_show(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['table']))             $input['table']=null;
        //選択しているタブのﾍﾟｰｼﾞｬｰのみページを指定する
        //$art_page = ($input['table'] == "art") ? $input['page'] :1;
        $mus_page = ($input['table'] == "mus") ? $input['page'] :1;
        $alb_page = ($input['table'] == "alb") ? $input['page'] :1;
        $pl_page  = ($input['table'] == "pl")  ? $input['page'] :1;

        $favorite_list = array();
        $favorite_list["mus"] = Favorite::getFavorite(10,true,$mus_page,Auth::id(),"mus");  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
        $favorite_list["alb"] = Favorite::getFavorite(10,true,$alb_page,Auth::id(),"alb");  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
        $favorite_list["pl"]  = Favorite::getFavorite(10,true,$pl_page ,Auth::id(),"pl" );  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
        

        //dd($favorite_list);
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
        $table = $request->input('table');
        $detail_id = $request->input('detail_id');
        $type = $request->input('type');    //add or del
        $ret = Favorite::chgFavorite(Auth::id(),$table,$detail_id,$type);
        
        return $ret;
    }
}
