<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Artist;
use App\Models\Album;
use App\Models\Music;
use App\Models\Affiliate;
//use App\Models\FileTmp;

//ミュージックコントローラー
class AdminMusicController extends Controller
{
    public function home()
    {
        return view('admin.adminhome');
    }
    //追加
    public function music_regist(Request $request)
    {
        $tab_name="音楽";
        $ope_type="music_reg";
        $artists = Artist::getArtist();  //全件　リスト用
        $musics = Music::getMusic_list(5);  //5件
        $msg = request('msg');
        
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['name', 'art_name', 'release_date', 'link']);
        
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'musics', 'artists', 'input', 'msg'));
    }
    //追加
    public function music_reg(Request $request)
    {
        $input = $request->only(['name', 'alb_id', 'art_id', 'art_name', 'release_date', 'link', 'aff_link']);
        //dd($input);
        $msg=null;
        //Affiliate,Musicを一括で登録するため、事前にデータ確認
        //if(!$input['aff_link'])     $msg =  "アフィリエイトリンクを入力してください。";
        if(!$input['art_id'])       $msg =  "登録されていないアーティストは選択できません。";
        if(!$input['art_name'])     $msg =  "アーティストを選択してください。";
        if(!$input['name'])         $msg =  "曲名を入力してください。";
        if($msg!==null)         return redirect()->route('admin-music-reg', ['input' => $input, 'msg' => $msg]);

        //affiliate登録
        $ret = Affiliate::createAffiliate($input);

        if($ret['error_code']==1)     $msg = "アフィリエイトリンクを入力してください。";
        if($ret['error_code']==2)     $msg = "アフィリエイトリンクが不正です。(URLと画像情報が必要)";
        if($ret['error_code']==-1)    $msg = "アフィリエイト情報の登録に失敗しました。";
        if($msg!==null) return redirect()->route('admin-music-reg', ['input' => $input, 'msg' => $msg]);
        $input['aff_id']=$ret['id']; //追加したAffiliateID

        //曲登録
        $ret = Music::createMusic($input);
        if($ret['error_code']==0){
             $msg = "曲：{$input['name']} を追加しました。";
             $input=null;   //データ登録成功時 初期化
        }

        if($ret==1) $msg = "曲名を入力してください。";
        if($ret==-1) $msg = "曲：{$input['name']} の追加に失敗しました。";
        return redirect()->route('admin-music-reg', ['input' => $input, 'msg' => $msg]);
    }

    //検索
    public function music_search(Request $request)
    {
        $tab_name="音楽";
        $ope_type="music_search";
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['keyword']);
        if (empty($input['keyword']))           $input['keyword']=null;

        $musics = Music::getMusic_list(10,true,$input['keyword']);  //件数,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ
        $artists = Artist::getArtist();  //全件　リスト用
        $msg = request('msg');
        $msg = ($msg===NULL && $input['keyword'] !==null && $musics === null) ? "検索結果が0件です。" : $msg;
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'artists', 'musics', 'input', 'msg'));
    }
    //削除
    public function music_del(Request $request)
    {
        $input = $request->only(['id','name']);
        //$msg = Music::delMusic($data['id']);
        $ret = Music::delMusic($input['id']);
        if($ret['error_code']==0)     $msg = "曲：". $input['name']. "を削除しました。";
        if($ret['error_code']==-1)    $msg = "曲の削除に失敗しました。";

        $input = $request->only(['keyword']);
        return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);
    }
    //変更
    public function music_chg(Request $request)
    {
        $input = $request->only(['id', 'alb_name', 'art_id', 'alb_id', 'art_name', 'release_date', 'link', 'aff_id', 'aff_link', 'keyword']);
        $msg=null;
        //music,Affiliate,Musicを一括で登録するため、事前にデータ確認
        //if(!$input['aff_link'])     $msg =  "アフィリエイトリンクを入力してください。";
        if(!$input['art_id'])       $msg =  "登録されていないアーティストは選択できません。";
        if(!$input['art_name'])     $msg =  "アーティストを選択してください。";
        if(!$input['alb_name'])     $msg =  "アルバム名を入力してください。";
        if(!$input['id'])           $msg =  "テーブルから選択してください。";
        if($msg!==null)         return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);

        //affiliate変更
        $input = $request->only(['aff_link', 'aff_id']);
        if($input['aff_link']){
            $ret = Affiliate::chgAffiliate($input);
            if($ret['error_code']==1)     $msg = "アフィリエイトリンクを入力してください。";
            if($ret['error_code']==2)     $msg = "アフィリエイトリンクが不正です。(URLと画像情報が必要)";
            if($ret['error_code']==-1)    $msg = "アフィリエイト情報の変更に失敗しました。";
            if($msg!==null) return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);

        }
        
        //music変更
        $input = $request->only(['id', 'alb_name', 'art_id','release_date', 'link']);
        $input['name'] = $input['alb_name'];    //musicのカラム名に合わせる
        if($input){
            $ret = Music::chgMusic($input);
            //最初にデータ不足の判定済み
            if($ret['error_code']==-1)    $msg = "アルバム情報の更新に失敗しました。";
            if($msg!==null) return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);
            $alb_id=$ret['id']; //変更したAlbimID
        
        }
        //収録曲は詳細変更でのみ可能
        $input = $request->only(['keyword']);
        $msg = "アルバム：" . $request->input('alb_name') . " を更新しました。";
        return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);
    }
}
