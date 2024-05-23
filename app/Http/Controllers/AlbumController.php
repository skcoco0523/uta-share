<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Album;

class AlbumController extends Controller
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
    //曲詳細
    public function album_show(Request $request)
    {
        $album = Album::getAlbum_detail($request->only(['id']));  //mus_id
        $msg = null;
        //dd($album);
        if($album){
            return view('album_show', compact('album', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のアルバムが存在しません');
        }
    }

    
}
