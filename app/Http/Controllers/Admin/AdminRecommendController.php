<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Artist;
use App\Models\Album;
//use App\Models\FileTmp;

//おすすめコントローラー
class AdminRecommendController extends Controller
{
    public function home()
    {
        return view('admin.adminhome');
    }
    public function recommend_regit()
    {
        $tab_name="おすすめ";
        $ope_type="recommend_reg";
        return view('admin.adminhome', compact('tab_name', 'ope_type'));
    }
}
