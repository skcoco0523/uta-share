<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Artist;

//アーティストコントローラー
class AdminArtistController extends Controller
{
    public function home()
    {
        return view('admin.adminhome');
    }

    public function artist_regist(Request $request)
    {
        $tab_name="アーティスト";
        $ope_type="artist_reg";
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['name', 'name2', 'debut', 'sex']);

        $artists = Artist::getArtist(5);  //5件
        $msg = request('msg');
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'artists', 'input', 'msg'));
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
        $tab_name="アーティスト";
        $ope_type="artist_search";
        
        //変更 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['keyword']);
        if (empty($input['keyword']))           $input['keyword']=null;

        $artists = Artist::getArtist(10,true,$input['keyword']);  //件数,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ
        $msg = request('msg');
        $msg = ($msg==NULL && $input['keyword'] !==null && count($artists) === 0) ? "検索結果が0件です。" : $msg;
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'artists', 'input', 'msg'));
    }
    //変更
    public function artist_chg(Request $request)
    {
        $data = $request->only(['id', 'name', 'name2', 'debut', 'sex']);
        $msg = Artist::chgArtist($data);
        $input = $request->only(['keyword']);
        return redirect()->route('admin-artist-search', ['input' => $input, 'msg' => $msg]);
    }
    //削除
    public function artist_del(Request $request)
    {
        $id = $request->only(['id']);
        $msg = Artist::delArtist($id);
        $input = $request->only(['keyword']);
        return redirect()->route('admin-artist-search', ['input' => $input, 'msg' => $msg]);
    }
}
