<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if($playlist){
            //他ユーザーのﾌﾟﾚｲﾘｽﾄは閲覧不可
            $user = Auth::user();
            if($playlist->user_id == $user->id || $playlist->admin_flag == 1){
                return view('playlist_show', compact('playlist', 'msg'));
            }else{
                return redirect()->route('home')->with('error', '該当のプレイリストが存在しません');
            }
        }else{
            return redirect()->route('home')->with('error', '該当のプレイリストが存在しません');
        }
    }
    //マイプレイリスト取得　モーダルで使用する
    public function myplaylist_get()
    {
        $playlists = Playlist::getPlaylist_list(999, false, null, ['user_id' => true]);

        //make_error_log("myplaylist_get.log","playlists=".print_r($playlists,1));
        // JSON形式でプレイリストを返す
        return response()->json($playlists);
    }
    //追加
    public function myplaylist_reg(Request $request)
    {
        $this->middleware('auth');
        $input = $request->all();
        $ret = Playlist::createPlaylist($input);
        if($ret['error_code']==0)   $msg = "プレイリストを追加しました。";
        else                        $msg = "プレイリストの追加に失敗しました。";

        $message = ['message' => $msg, 'type' => 'mypl_create', 'sec' => '2000'];
        return redirect()->route('favorite-show', ['table' => 'mypl'])->with($message);
        
    }
    //変更
    public function myplaylist_chg(Request $request)
    {
        $this->middleware('auth');
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
        $this->middleware('auth');
        $input = $request->all();
        $ret = Playlist::delPlaylist($input);
        if($ret['error_code']==0)   $msg = "プレイリストを削除しました。";
        if($ret['error_code']==-1)  $msg = "プレイリストの削除に失敗しました。";
        
        $message = ['message' => $msg, 'type' => 'mypl_del', 'sec' => '2000'];
        return redirect()->route('favorite-show', ['table' => 'mypl'])->with($message);
    }
    //詳細変更用　関数(追加・削除)
    public function myplaylist_detail_fnc(Request $request)
    {
        $this->middleware('auth');
        make_error_log("myplaylist_detail_fnc.log","-----start-----");
        $input = $request->all();
        $msg=null;
        $url = get_proc_data($input,"url");
              
        $ret = Playlist::chgPlaylist_detail($input);
        if($input['fnc'] == "add"){
            if($ret['error_code']==-1)      $msg = "プレイリストへの追加に失敗しました。";
            if($ret['error_code']==0)       $msg = "プレイリストに追加しました。";

        }elseif($input['fnc'] == "del"){
            if($ret['error_code']==-1)      $msg = "プレイリストからの削除に失敗しました。";
            if($ret['error_code']==0)       $msg = "プレイリストから削除しました。";
            
        }

        // 取得したURLにリダイレクトしながら、メッセージを渡す
        $message = ['message' => $msg, 'type' => 'mypl_del', 'sec' => '2000'];
        return redirect()->to($url)->with($message);

        }
    
}
