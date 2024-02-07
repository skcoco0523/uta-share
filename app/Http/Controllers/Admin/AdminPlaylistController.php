<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Artist;
use App\Models\Album;
//use App\Models\FileTmp;

//プレイリストコントローラー
class AdminPlaylistController extends Controller
{
    public function home()
    {
        return view('admin.adminhome');
    }

    public function playlist_regit()
    {
        $tab_name="プレイリスト";
        $ope_type="playlist_reg";
        return view('admin.adminhome', compact('tab_name', 'ope_type'));
    }

}
