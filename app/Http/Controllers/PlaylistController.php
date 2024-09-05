<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Playlist;

class PlaylistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //ホームはゲストも表示可能に
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //プレイリスト詳細
    public function playlist_show(Request $request)
    {
        $playlist = Playlist::getplaylist_detail($request->only(['id']));  //mus_id
        $msg = null;
        //dd($playlist);
        if($playlist){
            return view('playlist_show', compact('playlist', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のプレイリストが存在しません');
        }
    }
    

    //追加
    public function myplaylist_reg(Request $request)
    {
        $input = $request->all();
        $ret = Playlist::createPlaylist($input);
        if($ret['error_code']==0)   $msg = "プレイリストを追加しました。";
        else                        $msg = "プレイリストの追加に失敗しました。";

        $message = ['message' => $msg, 'type' => 'mypl_create', 'sec' => '2000'];
        return redirect()->route('favorite-show', ['table' => 'mypl'])->with($message);
        
    }
    //追加
    public function myplaylist_chg(Request $request)
    {
        $input = $request->all();
        $ret = Playlist::chgPlaylist($input);
        if($ret['error_code']==0)   $msg = "プレイリスト名を更新しました。";
        else                        $msg = "プレイリスト名の更新に失敗しました。";

        $message = ['message' => $msg, 'type' => 'mypl_chg', 'sec' => '2000'];
        return redirect()->route('favorite-show', ['table' => 'mypl'])->with($message);
        
    }
    //削除
    public function myplaylist_del(Request $request)
    {
        //$input = $request->only(['pl_id','pl_name','keyword','admin_flag']);
        $input = $request->all();
        $ret = Playlist::delPlaylist($input);
        if($ret['error_code']==0)   $msg = "プレイリストを削除しました。";
        if($ret['error_code']==-1)  $msg = "プレイリストの削除に失敗しました。";
        
        $message = ['message' => $msg, 'type' => 'mypl_del', 'sec' => '2000'];
        return redirect()->route('favorite-show', ['table' => 'mypl'])->with($message);
    }
    
}
