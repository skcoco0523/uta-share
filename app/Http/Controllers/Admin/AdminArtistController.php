<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Artist;

//アーティストコントローラー
class AdminArtistController extends Controller
{
    public function artist_regist(Request $request)
    {
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['name', 'name2', 'debut', 'sex']);

        $artists = Artist::getArtist_list(5);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $msg = request('msg');
        return view('admin.admin_home', compact('artists', 'input', 'msg'));
    }
    //追加
    public function artist_reg(Request $request)
    {
        $input = $request->only(['name', 'name2', 'debut', 'sex']);
        $ret = Artist::createArtist($input);
        if($ret==0){
             $msg = "アーティスト：{$input['name']} を追加しました。";
             $input=null;   //データ登録成功時 初期化
        }
        if($ret==1) $msg = "アーティスト名を入力してください。";
        if($ret==-1) $msg = "アーティスト：{$input['name']} の追加に失敗しました。";
        return redirect()->route('admin-artist-reg', ['input' => $input, 'msg' => $msg]);
    }
    //検索
    public function artist_search(Request $request)
    {        
        //変更 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['keyword']))           $input['keyword']=null;
        // 現在のページ番号を取得。指定がない場合は1を使用
        if (empty($input['page']))              $input['page'] = 1;

        $artists = Artist::getArtist_list(10,true,$input['page'],$input['keyword']);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $msg = request('msg');
        $msg = ($msg==NULL && $input['keyword'] !==null && count($artists) === 0) ? "検索結果が0件です。" : $msg;
        return view('admin.admin_home', compact('artists', 'input', 'msg'));
    }
    //変更
    public function artist_chg(Request $request)
    {
        $data = $request->only(['id', 'name', 'name2', 'debut', 'sex']);
        $msg = Artist::chgArtist($data);
        //$input = $request->only(['keyword']);
        $input = $request->all();
        return redirect()->route('admin-artist-search', ['input' => $input, 'msg' => $msg]);
    }
    //削除
    public function artist_del(Request $request)
    {
        $id = $request->only(['id']);
        $msg = Artist::delArtist($id);
        //$input = $request->only(['keyword']);
        $input = $request->all();
        return redirect()->route('admin-artist-search', ['input' => $input, 'msg' => $msg]);
    }
}
