<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Playlist;
use App\Models\Album;
use App\Models\Music;
//use App\Models\FileTmp;

//プレイリストコントローラー
class AdminPlaylistController extends Controller
{
    public function home()
    {
        return view('admin.adminhome');
    }

    public function playlist_regist(Request $request)
    {
        $tab_name="プレイリスト";
        $ope_type="playlist_reg";
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['name']);
        
        $playlist = Playlist::getPlaylist_list(5,false,null,1);  //5件,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,admin_flag
        $msg = request('msg');
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'playlist', 'input', 'msg'));
    }
    //追加
    public function playlist_reg(Request $request)
    {
        $input = $request->only(['name', 'user_id', 'admin_flag']);
        $ret = Playlist::createPlaylist($input);
        if($ret['error_code']==0){
             $msg = "プレイリスト：{$input['name']} を追加しました。";
             $input=null;   //データ登録成功時 初期化
        }
        if($ret['error_code']==1) $msg = "プレイリスト名を入力してください。";
        if($ret['error_code']==-1) $msg = "プレイリスト：{$input['name']} の追加に失敗しました。";
        return redirect()->route('admin-playlist-reg', ['input' => $input, 'msg' => $msg]);
    }
    //削除
    public function playlist_del(Request $request)
    {
        $input = $request->only(['pl_id','pl_name','keyword','admin_flag']);
        $ret = Playlist::delPlaylist($input);
        if($ret['error_code']==0)   $msg = "プレイリスト：{$input['pl_name']} を削除しました。";
        if($ret['error_code']==-1)  $msg = "プレイリスト：{$input['pl_name']} の削除に失敗しました。";
        return redirect()->route('admin-playlist-search', ['input' => $input, 'msg' => $msg]);
    }
    //検索
    public function playlist_search(Request $request)
    {
        $tab_name="プレイリスト";
        $ope_type="playlist_search";
        
        //変更 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['keyword', 'admin_flag']);
        if (empty($input['keyword']))           $input['keyword']=null;
        //0がはじかれてしまうためemptyは使わない
        //if (empty($input['admin_flag']))         $input['admin_flg']=null;
        if (!isset($input['admin_flag']))                 $input['admin_flag'] = 1;
        elseif ($input['admin_flag'] === '')              $input['admin_flag'] = 1;

        $input['chg_flg'] = 0;
        //dd($input);
        $playlist = Playlist::getPlaylist_list(5,true,$input['keyword'],$input['admin_flag']);  //5件,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,admin_flag
        $msg = request('msg');
        $msg = ($msg==NULL && $input['keyword'] !==null && count($playlist) === 0) ? "検索結果が0件です。" : $msg;
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'playlist', 'input', 'msg'));
    }
    //変更
    public function playlist_chg(Request $request)
    {
        $input = $request->only(['id', 'name']);
        $ret = Playlist::chgPlaylist($input);


        if($ret['error_code']==1) $msg = "テーブルから変更対象を選択してください。";
        if($ret['error_code']==2) $msg = "プレイリスト名を入力してください。";
        if($ret['error_code']==-1) $msg = "プレイリスト：{$input['name']} の変更に失敗しました。";
        if($ret['error_code']==0) $msg = "プレイリスト：{$input['name']} に更新しました。";

        $input = $request->only(['keyword','admin_flag']);

        return redirect()->route('admin-playlist-search', ['input' => $input, 'msg' => $msg]);
    }
    //詳細変更
    public function playlist_chg_detail(Request $request)
    {
        $tab_name="プレイリスト";
        $ope_type="playlist_search";    //同一テンプレート内で分岐する
        //$ope_type="playlist_chg_detail";
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['id', 'keyword', 'admin_flag']);
        if (empty($input['id']))                $input['id']=null;
        if (empty($input['keyword']))           $input['keyword']=null;
        //0がはじかれてしまうためemptyは使わない
        //if (empty($input['admin_flag']))         $input['admin_flag']=null;
        if (!isset($input['admin_flag']))                 $input['admin_flag'] = null;
        elseif ($input['admin_flag'] === '')              $input['admin_flag'] = null;

        //収録曲変更
        $playlist_detail = Playlist::getPlaylist_detail($input['id']);  //全件,なし,ｷｰﾜｰﾄﾞ　リスト用
        $playlist = null;
            
        $msg = request('msg');
        $msg = ($msg===NULL && $input['keyword'] !==null && $playlist_detail === null) ? "検索結果が0件です。" : $msg;

        return view('admin.adminhome', compact('tab_name', 'ope_type', 'playlist_detail', 'playlist', 'input', 'msg'));
    }
    //詳細変更用　楽曲検索
    public function playlist_detail_search(Request $request)
    {
        $tab_name="プレイリスト";
        $ope_type="playlist_search";    //同一テンプレート内で分岐する
        //$ope_type="playlist_chg_detail";
        //ﾌﾟﾚｲﾘｽﾄ追加用楽曲の検索　リダイレクト無し
        $input = $request->only(['id', 'mus_keyword']);
        if (empty($input['id']))                $input['id']=null;      //ﾌﾟﾚｲﾘｽﾄID
        if (empty($input['mus_keyword']))           $input['mus_keyword']=null;

        //収録曲変更　現在の収録曲
        $playlist_detail = Playlist::getPlaylist_detail($input['id']);  //全件,なし,ｷｰﾜｰﾄﾞ　リスト用
        $playlist = null;
        
        //楽曲検索
        $music = Music::getMusic_list(5,true,$input['mus_keyword']);  //全件,なし,ｷｰﾜｰﾄﾞ　リスト用

        $msg = request('msg');
        $msg = ($msg===NULL && $input['mus_keyword'] !==null && $music === null) ? "検索結果が0件です。" : $msg;

        return view('admin.adminhome', compact('tab_name', 'ope_type', 'playlist_detail', 'playlist', 'music', 'input', 'msg'));
    }
    //詳細変更用　関数(変更・削除・追加)
    public function playlist_chg_detail_fnc(Request $request)
    {
        make_error_log("album_chg_detail_fnc.log","-----start-----");
        $input = $request->only(['fnc', 'pl_id','detail_id']);
        $msg=null;
        make_error_log("album_chg_detail_fnc.log","fnc=".$input['fnc']." pl_id=".$input['pl_id']." detail_id=".$input['detail_id']);
        
        $ret = Playlist::chgPlaylist_detail($input);
        switch($input['fnc']){
            case "del":
                if($ret['error_code']==-1)    $msg = "収録曲の削除に失敗しました。";
                if($ret['error_code']==0)    $msg = "収録曲を削除しました。";
                break;
            case "add":
                if($ret['error_code']==-1)    $msg = "ﾌﾟﾚｲﾘｽﾄへの追加に失敗しました。";
                if($ret['error_code']==0)    $msg = "ﾌﾟﾚｲﾘｽﾄに追加しました。";
                break;
            default:
        }
        $input['id'] = $input['pl_id'];
        return redirect()->route('admin-playlist-chgdetail', ['input' => $input, 'msg' => $msg]);
    }
}
