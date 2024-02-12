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
        $albums = Album::getAlbum_list(5);  //5件
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
        //$input = $request->only(['art_id', 'release_date']);  //アルバムにリリース日情報があるため不要
        $input = $request->only(['art_id']);
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

        $albums = Album::getAlbum_list(5,true,$keyword);  //5件,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ
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
    //詳細変更
    public function album_chg_detail(Request $request)
    {
        $tab_name="アルバム";
        $ope_type="album_chg_detail";
        $keyword = $request->input('keyword');
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $alb_id = request('input')['alb_id'];
        else                                    $alb_id = $request->input('id');
        $chg_flg = 0;
        $redirect_flg = 0;
        //if($request->input('input')!==null) dd($alb_id);
        if($alb_id === null){
            //検索
            $album = null;
            $albums = Album::getAlbum_list(5,true,$keyword);  //全件,なし,ｷｰﾜｰﾄﾞ　リスト用
        }else{
            //収録曲変更
            $chg_flg = 1;
            $album = Album::getAlbum_detail($alb_id);  //全件,なし,ｷｰﾜｰﾄﾞ　リスト用
            $albums = null;
            //リダイレクトの場合は、表示状態とする
            if($request->input('input')!==null) $redirect_flg = 1;
        }
        
        $msg = request('msg');
        $msg = ($msg===NULL && $keyword !==null && $albums === null) ? "検索結果が0件です。" : $msg;
        $input = $request->input('input');
        $input['chg_flg'] = $chg_flg;
        $input['redirect_flg'] = $redirect_flg;
        
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'albums', 'album', 'input', 'msg'));
    }
    //詳細変更用　関数(変更・削除・追加)
    public function album_chg_detail_fnc(Request $request)
    {
        make_error_log("album_chg_detail_fnc.log","-----start-----");
        $input = $request->only(['fnc', 'alb_id', 'mus_id','name']);
        $msg=null;
        make_error_log("album_chg_detail_fnc.log","fnc=".$input['fnc']." alb_id=".$input['alb_id']." mus_id=".$input['mus_id']." name=".$input['name']);
        $input['id']=$input['mus_id'];
        switch($input['fnc']){
            case "chg":
                $ret = Music::chgMusic($input);
                if($ret['error_code']==-1)    $msg = "収録曲の更新に失敗しました。";
                if($ret['error_code']==0)    $msg = "収録曲を更新しました。";

                break;
            case "del":
                $ret = Music::delMusic($input['id']);
                if($ret['error_code']==-1)    $msg = "収録曲の削除に失敗しました。";
                if($ret['error_code']==0)    $msg = "収録曲を削除しました。";

                break;
            case "add":
                if($input['name'] == null){
                    $msg = "アルバムに追加する楽曲名を入力してください。";
                }else{
                    //album情報を取得　既存art_idを付与
                    $album = Album::getalbum_detail($input['alb_id']);
                    //album情報から既存art_idを取得
                    $input['art_id'] = $album->art_id;
                    $ret = Music::createMusic($input);
                    //dd($input,$album,$ret);
                }
                break;
            default:
        }
        return redirect()->route('admin-album-chgdetail', ['input' => $input, 'msg' => $msg]);
    }
}
