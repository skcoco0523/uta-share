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
