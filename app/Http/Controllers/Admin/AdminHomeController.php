<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//use App\Models\Artist;
//use App\Models\Album;
//use App\Models\FileTmp;

//ホームコントローラー
class AdminHomeController extends Controller
{
    public function home(Request $request)
    {
        $tab_name = request('tab_name');
        return view('admin.adminhome', compact('tab_name'));
    }
}
