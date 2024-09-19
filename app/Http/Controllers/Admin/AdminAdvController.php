<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Affiliate;
use App\Models\Advertisement;

//アルバムコントローラー
class AdminAdvController extends Controller
{
    //追加ページ
    public function adv_regist(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        $advertisement = Advertisement::getAdv_list(5);  //5件
        $msg = request('msg');
        
        return view('admin.admin_home', compact('advertisement', 'input', 'msg'));
    }
    //追加処理
    public function adv_reg(Request $request)
    {
        $input = $request->all();
        $msg=null;
        $aff_ret = Affiliate::createAffiliate($input);
        //affiliate登録成功
        if($aff_ret['error_code'] == 0){
            $input['aff_id'] = $aff_ret['id'];
            $adv_ret = Advertisement::createAdv($input);

            //広告登録成功
            if($adv_ret['error_code'] == 0){
                $adv_id=$adv_ret['id']; //追加した広告ID

            //広告登録失敗 
            }else{
                //一時的に作られたアフィリエイトデータは削除
                Affiliate::delAffiliate($input);
                if($adv_ret['error_code']==1)     $msg = "広告名を入力してください。";
                if($adv_ret['error_code']==2)     $msg = "アフィリエイト情報の登録に失敗しました。";
                if($adv_ret['error_code']==3)     $msg = "広告タイプの制約に反します。";
                if($adv_ret['error_code']==4)     $msg = "掲載期間が不正です。";
                if($adv_ret['error_code']==-1)    $msg = "広告情報の登録に失敗しました。";
            }
            
        //affiliate登録失敗
        }else{
            if($aff_ret['error_code']==1)     $msg = "アフィリエイトリンクを入力してください。";
            if($aff_ret['error_code']==2)     $msg = "アフィリエイトリンクが不正です。(URLと画像情報が必要)";
            if($aff_ret['error_code']==-1)    $msg = "アフィリエイト情報の登録に失敗しました。";
        }

        return redirect()->route('admin-adv-reg', ['input' => $input, 'msg' => $msg]);

    }
    //検索
    public function adv_search(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        $input['search_artist']         = get_proc_data($input,"search_artist");
        $input['search_album']          = get_proc_data($input,"search_album");
        //ユーザーによる検索
        $input['keyword']               = get_proc_data($input,"keyword");

        $input['page']                  = get_proc_data($input,"page");

        $album = Album::getAlbum_list(10,true,$input['page'],$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $artist = Artist::getArtist_list();  //全件　リスト用
        $msg = request('msg');
        
        return view('admin.admin_home', compact('artist', 'album', 'input', 'msg'));
    }
    //削除
    public function adv_del(Request $request)
    {
        $data = $request->only(['id']);
        $msg = Album::delAlbum($data['id']);
        $input = $request->all();
        return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
    }
    //変更
    public function adv_chg(Request $request)
    {
        //$input = $request->only(['id', 'alb_name', 'art_id', 'art_name', 'release_date', 'aff_id', 'aff_link', 'keyword']);
        $input = $request->all();
        $msg=null;
        //Album,Affiliate,Musicを一括で登録するため、事前にデータ確認
        //if(!$input['aff_link'])     $msg =  "アフィリエイトリンクを入力してください。";
        if(!$input['art_id'])       $msg =  "登録されていないアーティストは選択できません。";
        if(!$input['art_name'])     $msg =  "アーティストを選択してください。";
        if(!$input['alb_name'])     $msg =  "アルバム名を入力してください。";
        if(!$input['id'])           $msg =  "テーブルから選択してください。";
        if($msg!==null)         return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);

        //affiliate変更
        //$input = $request->only(['aff_link', 'aff_id']);
        if($input['aff_link']){
            $ret = Affiliate::chgAffiliate($input);
            if($ret['error_code']==1)     $msg = "アフィリエイトリンクを入力してください。";
            if($ret['error_code']==2)     $msg = "アフィリエイトリンクが不正です。(URLと画像情報が必要)";
            if($ret['error_code']==-1)    $msg = "アフィリエイト情報の変更に失敗しました。";
            if($msg!==null) return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);

        }
        
        //Album変更
        //$input = $request->only(['id', 'alb_name', 'art_id','release_date']);
        $input['name'] = $input['alb_name'];    //albumのカラム名に合わせる
        if($input){
            $ret = Album::chgAlbum($input);
            //最初にデータ不足の判定済み
            if($ret['error_code']==-1)    $msg = "アルバム情報の更新に失敗しました。";
            if($msg!==null) return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
            $alb_id=$ret['id']; //変更したAlbimID
        
        }
        //収録曲は詳細変更でのみ可能
        //$input = $request->only(['keyword']);
        $msg = "アルバム：" . $request->input('alb_name') . " を更新しました。";
        return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
    }
}
