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
    //追加
    public function music_regist(Request $request)
    {
        $artists = Artist::getArtist_list();  //全件　リスト用
        $musics = Music::getMusic_list(5);  //5件
        $msg = request('msg');
        
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        return view('admin.admin_home', compact('musics', 'artists', 'input', 'msg'));
    }
    //追加
    public function music_reg(Request $request)
    {
        //$input = $request->only(['name', 'alb_id', 'art_id', 'art_name', 'release_date', 'link', 'aff_link']);
        $input = $request->all();
        
        $input['name']              = get_proc_data($input,"name");
        $input['alb_id']            = get_proc_data($input,"alb_id");
        $input['art_id']            = get_proc_data($input,"art_id");
        $input['art_name']          = get_proc_data($input,"art_name");
        $input['release_date']      = get_proc_data($input,"release_date");
        $input['link']              = get_proc_data($input,"link");
        $input['aff_link']          = get_proc_data($input,"aff_link");
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
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        $input['search_music']          = get_proc_data($input,"search_music");
        $input['search_artist']         = get_proc_data($input,"search_artist");
        $input['search_album']          = get_proc_data($input,"search_album");
        //ユーザーによる検索
        $input['keyword']               = get_proc_data($input,"keyword");

        $input['page']                  = get_proc_data($input,"page");

        $musics = Music::getMusic_list(10,true,$input['page'],$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $artists = Artist::getArtist_list();  //全件　リスト用
        $msg = request('msg');
        $msg = ($msg===NULL && $input['keyword'] !==null && $musics === null) ? "検索結果が0件です。" : $msg;
        return view('admin.admin_home', compact('artists', 'musics', 'input', 'msg'));
    }
    //削除
    public function music_del(Request $request)
    {
        $input = $request->all();
        //$msg = Music::delMusic($data['id']);
        $ret = Music::delMusic($input['id']);
        if($ret['error_code']==0)     $msg = "曲：". $input['name']. "を削除しました。";
        if($ret['error_code']==-1)    $msg = "曲の削除に失敗しました。";

        return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);
    }
    //変更
    public function music_chg(Request $request)
    {
        //$input = $request->only(['id', 'alb_name', 'art_id', 'alb_id', 'art_name', 'release_date', 'link', 'aff_id', 'aff_link', 'keyword','page']);
        $input = $request->all();
        $msg=null;
        //music,Affiliate,Musicを一括で登録するため、事前にデータ確認
        //if(!$input['aff_link'])     $msg =  "アフィリエイトリンクを入力してください。";
        if(!$input['art_id'])       $msg =  "登録されていないアーティストは選択できません。";
        if(!$input['art_name'])     $msg =  "アーティストを選択してください。";
        if(!$input['alb_name'])     $msg =  "アルバム名を入力してください。";
        if(!$input['id'])           $msg =  "テーブルから選択してください。";
        if($msg!==null)         return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);

        //affiliate変更
        //$input = $request->only(['aff_link', 'aff_id','page']);
        $input = $request->all();
        if($input['aff_link']){
            $ret = Affiliate::chgAffiliate($input);
            if($ret['error_code']==1)     $msg = "アフィリエイトリンクを入力してください。";
            if($ret['error_code']==2)     $msg = "アフィリエイトリンクが不正です。(URLと画像情報が必要)";
            if($ret['error_code']==-1)    $msg = "アフィリエイト情報の変更に失敗しました。";
            if($msg!==null) return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);

        }
        
        //music変更
        //$input = $request->only(['id', 'alb_name', 'art_id','release_date', 'link','page']);
        $input = $request->all();
        $input['name'] = $input['alb_name'];    //musicのカラム名に合わせる
        if($input){
            $ret = Music::chgMusic($input);
            //最初にデータ不足の判定済み
            if($ret['error_code']==-1)    $msg = "アルバム情報の更新に失敗しました。";
            if($msg!==null) return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);
            $alb_id=$ret['id']; //変更したAlbimID
        
        }
        //収録曲は詳細変更でのみ可能
        //$input = $request->only(['keyword','page']);
        $input = $request->all();
        $msg = "アルバム：" . $request->input('alb_name') . " を更新しました。";
        return redirect()->route('admin-music-search', ['input' => $input, 'msg' => $msg]);
    }
}
