<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Artist;
use App\Models\Album;
use App\Models\Music;
use App\Models\Affiliate;
//use App\Models\FileTmp;

//アルバムコントローラー
class AdminAlbumController extends Controller
{
    public function home()
    {
        return view('admin.adminhome');
    }
    
    //追加
    public function album_regit(Request $request)
    {
        $tab_name="アルバム";
        $ope_type="album_reg";
        $artists = Artist::getArtist();  //全件　リスト用
        $albums = Album::getalbum(5);  //5件
        $msg = request('msg');
        
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['alb_name', 'art_name', 'release_date', 'sex']);
        
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'artists', 'albums', 'input', 'msg'));
    }
    //追加
    public function album_reg(Request $request)
    {
        $input = $request->only(['alb_name', 'art_id', 'art_name', 'release_date', 'music_list', 'aff_link']);
        $msg=null;
        //Album,Affiliate,Musicを一括で登録するため、事前にデータ確認
        if(!$input['aff_link'])     $msg =  "アフィリエイトリンクを入力してください。";
        if(!$input['art_id'])       $msg =  "登録されていないアーティストは選択できません。";
        if(!$input['art_name'])     $msg =  "アーティストを選択してください。";
        if(!$input['alb_name'])     $msg =  "アルバム名を入力してください。";
        if($msg!==null)         return redirect()->route('admin-album-reg', ['input' => $input, 'msg' => $msg]);
        //dd($data);
        
        //affiliate登録
        $input = $request->only(['aff_link']);

        $ret = Affiliate::createAffiliate($input);
        if($ret['error_code']==1)     $msg = "アフィリエイトリンクを入力してください。";
        if($ret['error_code']==2)     $msg = "アフィリエイトリンクが不正です。(URLと画像情報が必要)";
        if($ret['error_code']==-1)    $msg = "アフィリエイト情報の登録に失敗しました。";
        if($msg!==null) return redirect()->route('admin-album-reg', ['input' => $input, 'msg' => $msg]);
        $aff_id=$ret['id']; //追加したAffiliateID
        
        //Album登録
        $input = $request->only(['alb_name', 'art_id','release_date']);
        $input['name'] = $input['alb_name'];    //albumのカラム名に合わせる
        $input['aff_id'] = $aff_id;          //AffiliateID追加

        $ret = Album::createAlbum($input);      //二重のエラー判定だが念のため
        if($ret['error_code']==1)     $msg = "アルバム名を入力してください。";
        if($ret['error_code']==2)     $msg = "アーティストを選択してください。";
        if($ret['error_code']==3)     $msg = "アフィリエイト情報の登録に失敗しました。";
        if($ret['error_code']==-1)    $msg = "アルバム情報の登録に失敗しました。";
        if($msg!==null) return redirect()->route('admin-album-reg', ['input' => $input, 'msg' => $msg]);
        $alb_id=$ret['id']; //追加したAlbimID

        //Music登録　必須ではないためデータがない場合はここでリダイレクト
        // 改行で分割して配列に格納     空の要素を削除して配列を整理
        $input = $request->only(['art_id', 'release_date']);
        $input['alb_id'] = $alb_id;          //AlbumID追加

        $add_mus_list = array_filter(array_map('trim', preg_split('/\R/', $request->input('music_list'))));
        foreach ($add_mus_list as $music) {
            if($music !== ""){
                $input['name'] = $music;    //musicsの追加カラム
                $ret = Music::createMusic($input);
            }
        }


        //直近で登録したアルバム取得
        $msg = "アルバム：" . $request->input('alb_name') . " を追加しました。";
        return redirect()->route('admin-album-reg', ['input' => $input, 'msg' => $msg]);
    }
    //検索
    public function album_search(Request $request)
    {
        $tab_name="アルバム";
        $ope_type="album_search";
        $keyword = $request->input('keyword');

        $albums = Album::getAlbum(5,true,$keyword);  //5件,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ
        $artists = Artist::getArtist();  //全件　リスト用
        $msg = request('msg');
        $msg = ($msg===NULL && $keyword !==null && $albums === null) ? "検索結果が0件です。" : $msg;
        $input = $request->input('input');
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'artists', 'albums', 'input', 'msg'));
    }
    //削除
    public function album_del(Request $request)
    {
        $data = $request->only(['id']);
        $msg = Album::delAlbum($data['id']);
        $input = $request->only(['keyword']);
        return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
    }
    //変更
    public function album_chg(Request $request)
    {
        $input = $request->only(['id', 'alb_name', 'art_id', 'art_name', 'release_date', 'aff_id', 'aff_link']);
        $msg=null;
        //Album,Affiliate,Musicを一括で登録するため、事前にデータ確認
        //if(!$input['aff_link'])     $msg =  "アフィリエイトリンクを入力してください。";
        if(!$input['art_id'])       $msg =  "登録されていないアーティストは選択できません。";
        if(!$input['art_name'])     $msg =  "アーティストを選択してください。";
        if(!$input['alb_name'])     $msg =  "アルバム名を入力してください。";
        if(!$input['id'])           $msg =  "テーブルから選択してください。";
        if($msg!==null)         return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);

        //affiliate変更
        $input = $request->only(['aff_link', 'aff_id']);
        if($input['aff_link']){
            $ret = Affiliate::chgAffiliate($input);
            if($ret['error_code']==1)     $msg = "アフィリエイトリンクを入力してください。";
            if($ret['error_code']==2)     $msg = "アフィリエイトリンクが不正です。(URLと画像情報が必要)";
            if($ret['error_code']==-1)    $msg = "アフィリエイト情報の変更に失敗しました。";
            if($msg!==null) return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);

        }
        
        //Album変更
        $input = $request->only(['id', 'alb_name', 'art_id','release_date']);
        $input['name'] = $input['alb_name'];    //albumのカラム名に合わせる
        if($input){
            $ret = Album::chgAlbum($input);
            //最初にデータ不足の判定済み
            if($ret['error_code']==-1)    $msg = "アルバム情報の更新に失敗しました。";
            if($msg!==null) return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
            $alb_id=$ret['id']; //変更したAlbimID
        
        }
        //収録曲は詳細変更でのみ可能
        $input = null;
        $msg = "アルバム：" . $request->input('alb_name') . " を更新しました。";
        return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
    }
}
