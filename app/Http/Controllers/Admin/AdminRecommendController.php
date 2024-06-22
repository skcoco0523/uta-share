<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Recommend;
use App\Models\Artist;
use App\Models\Album;
use App\Models\Music;
use App\Models\Playlist;

//おすすめコントローラー
class AdminRecommendController extends Controller
{
    public function home()
    {
        return view('admin.adminhome');
    }
    public function recommend_regist(Request $request)
    {
        $tab_name="おすすめ";
        $ope_type="recommend_reg";
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['name']);
        //$sort_flag = 0;
        //$category = null;
        $recommend = Recommend::getRecommend_list(null,false,null,null);  //5件,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,カテゴリ,sort_flag
        $msg = request('msg');
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'recommend', 'input', 'msg'));
    }
    //追加
    public function recommend_reg(Request $request)
    {
        //$input = $request->only(['name', 'user_id', 'category','page']);
        $input = $request->all();
        $ret = Recommend::createRecommend($input);
        if($ret['error_code']==0){
             $msg = "おすすめ：{$input['name']} を追加しました。";
             $input=null;   //データ登録成功時 初期化
        }
        if($ret['error_code']==1) $msg = "登録名を入力してください。";
        if($ret['error_code']==2) $msg = "カテゴリを選択してください。";
        if($ret['error_code']==-1) $msg = "おすすめ：{$input['name']} の追加に失敗しました。";
        return redirect()->route('admin-recommend-reg', ['input' => $input, 'msg' => $msg]);
    }
    //削除
    public function recommend_del(Request $request)
    {
        $input = $request->all();
        $ret = Recommend::delRecommend($input);
        if($ret['error_code']==0)   $msg = "おすすめ：{$input['recom_name']} を削除しました。";
        if($ret['error_code']==-1)  $msg = "おすすめ：{$input['recom_name']} の削除に失敗しました。";
        return redirect()->route('admin-recommend-search', ['input' => $input, 'msg' => $msg]);
    }
    //検索
    public function recommend_search(Request $request)
    {
        $tab_name="おすすめ";
        $ope_type="recommend_search";
        
        //変更 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)         $input = request('input');
        else                                        $input = $request->all();
        if (empty($input['keyword']))               $input['keyword']=null;
        // 0を許容するためissetを使用
        if (!isset($input['category']))             $input['category'] = null;
        // 現在のページ番号を取得。指定がない場合は1を使用
        if (empty($input['page']))              $input['page'] = 1;
        
        //$sort_flag = ($input['category']!=null) ?       1:0;
        if($input['category']!=null){
            //カテゴリ検索時は表示順を切り替えるため件数を15に増やす
            $recommend = Recommend::getRecommend_list(15,true,$input['page'],$input['keyword'],$input['category'],true);  //表示件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,category,表示順ソート
        }else{
            $recommend = Recommend::getRecommend_list(10,true,$input['page'],$input['keyword'],$input['category'],false);  //表示件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,category,
        }
        $msg = request('msg');
        $msg = ($msg==NULL && $input['keyword'] !==null && count($recommend) === 0) ? "検索結果が0件です。" : $msg;
        return view('admin.adminhome', compact('tab_name', 'ope_type', 'recommend', 'input', 'msg'));
    }
    //変更
    public function recommend_chg(Request $request)
    {
        $input = $request->all();
        $ret = Recommend::chgRecommend($input);
        
        if($ret['error_code']==1) $msg = "テーブルから変更対象を選択してください。";
        if($ret['error_code']==2) $msg = "登録名を入力してください。";
        if($ret['error_code']==3) $msg = "表示有無を選択してください。";
        if($ret['error_code']==-1) $msg = "おすすめ：{$input['name']} の変更に失敗しました。";
        if($ret['error_code']==0) $msg = "おすすめ：{$input['name']} を更新しました。";

        return redirect()->route('admin-recommend-search', ['input' => $input, 'msg' => $msg]);
    }
    //詳細変更
    public function recommend_chg_detail(Request $request)
    {
        $tab_name="おすすめ";
        $ope_type="recommend_search";    //同一テンプレート内で分岐する
        //$ope_type="recommend_chg_detail";
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['id']))                $input['id']=null;
        if (empty($input['keyword']))           $input['keyword']=null;
        // 0を許容するためissetを使用
        if (!isset($input['category']))         $input['category'] = null;

        //収録曲変更
        $recommend_detail = Recommend::getRecommend_detail($input['id']);  //全件,なし,ｷｰﾜｰﾄﾞ　リスト用
        $recommend = null;
            
        $msg = request('msg');
        $msg = ($msg===NULL && $input['keyword'] !==null && $recommend_detail === null) ? "検索結果が0件です。" : $msg;

        return view('admin.adminhome', compact('tab_name', 'ope_type', 'recommend_detail', 'recommend', 'input', 'msg'));
    }
    //詳細変更用　楽曲検索
    public function recommend_detail_search(Request $request)
    {
        $tab_name="おすすめ";
        $ope_type="recommend_search";    //同一テンプレート内で分岐する
        //追加 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)  $input = request('input');
        else                                 $input = $request->all();
        if (empty($input['id']))             $input['id'] = null;
        if (empty($input['dtl_keyword']))    $input['dtl_keyword'] = null;
        // 0を許容するためissetを使用
        if (!isset($input['category']))         $input['category'] = null;
        // 現在のページ番号を取得。指定がない場合は1を使用
        if (empty($input['page']))              $input['page'] = 1;

        //収録曲変更　現在の収録曲
        $recommend_detail = Recommend::getRecommend_detail($input['id']);  //全件,なし,ｷｰﾜｰﾄﾞ　リスト用
        $recommend = null;
        
        //カテゴリに応じて分岐
        switch($input['category']){
            case 0: //曲
                $detail = Music::getMusic_list(5,true,$input['page'],$input['dtl_keyword']);//件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
                break;
            case 1: //ｱｰﾃｨｽﾄ
                $detail = Artist::getArtist_list(5,true,$input['page'],$input['dtl_keyword']);  //5件,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
                break;
            case 2: //ｱﾙﾊﾞﾑ
                $detail = Album::getAlbum_list(5,true,$input['page'],$input['dtl_keyword']);  //5件,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
                break;
            case 3: //ﾌﾟﾚｲﾘｽﾄ
                $detail = Playlist::getPlaylist_list(5,true,$input['page'],$input['dtl_keyword'],1);  //5件,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,admin_flg
                break;
            default:
                break;
        }

        $msg = request('msg');
        $msg = ($msg===NULL && $input['dtl_keyword'] !==null && $detail === null) ? "検索結果が0件です。" : $msg;

        return view('admin.adminhome', compact('tab_name', 'ope_type', 'recommend_detail', 'recommend', 'detail', 'input', 'msg'));
    }
    //表示順変更用
    public function recommend_chg_sort(Request $request)
    {
        make_error_log("recommend_chg_sort.log","-----start-----");
        $input = $request->all();
        //dd($input);
        $msg=null;
        make_error_log("recommend_chg_sort.log","fnc=".$input['fnc']." recom_id=".$input['id']);
        $ret = Recommend::chgsortRecommend($input);
        if($ret['error_code']==1)    $msg = "必要な情報が不足しています。";
        if($ret['error_code']==-1)    $msg = "表示順の変更に失敗しました。";
        if($ret['error_code']==0)    $msg = "表示順を変更しました。";

        return redirect()->route('admin-recommend-search', ['input' => $input, 'msg' => $msg]);
    }
    //詳細変更用　関数(変更・削除・追加)
    public function recommend_chg_detail_fnc(Request $request)
    {
        make_error_log("recommend_chg_detail_fnc.log","-----start-----");
        $input = $request->all();
        $input['id'] = $input['recom_id'];
        $msg=null;
        make_error_log("recommend_chg_detail_fnc.log","fnc=".$input['fnc']." recom_id=".$input['recom_id']." category=".$input['category']." detail_id=".$input['detail_id']);
        $ret = Recommend::chgRecommend_detail($input);
        switch($input['fnc']){
            case "del":
                if($ret['error_code']==-1)    $msg = "登録データの削除に失敗しました。";
                if($ret['error_code']==0)    $msg = "登録データを削除しました。";
                break;
            case "add":
                if($ret['error_code']==-1)    $msg = "おすすめ追加に失敗しました。";
                if($ret['error_code']==0)    $msg = "おすすめへ追加しました。";
                break;
            default:
                $msg = "データ処理に失敗しました。";
                
        }
        //return redirect()->route('admin-recommend-chgdetail', ['input' => $input, 'msg' => $msg]);
        return redirect()->route('admin-recommend-detail-search', ['input' => $input, 'msg' => $msg]);
        
    }
}
