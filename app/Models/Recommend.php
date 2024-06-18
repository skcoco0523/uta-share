<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Music;
use App\Models\Album;
use App\Models\Playlist;

class Recommend extends Model
{
    use HasFactory;
    protected $table = 'recommend';    //recommendsテーブルが指定されてしまうため、強制的に指定
    protected $fillable = ['name', 'user_id', 'category', 'disp_flag', 'sort_num'];
    //取得
    public static function getRecommend_list($disp_cnt=null,$pageing=false,$keyword=null,$category=null,$sort_flag=0)
    {
        $sql_cmd = DB::table('recommend')
            ->where('name', 'like', "%$keyword%");
        
        if ($category !== null)                       $sql_cmd = $sql_cmd->where('category', '=', $category);
        //ソート条件を判定
        if ($sort_flag)                     $sql_cmd = $sql_cmd->orderBy('sort_num', 'asc');
        else                                $sql_cmd = $sql_cmd->orderBy('created_at', 'desc');
            
        // デフォルト5件
        if ($disp_cnt === null)             $disp_cnt=5;
        // ページング・取得件数指定・全件で分岐
        if ($pageing)                       $sql_cmd = $sql_cmd->paginate($disp_cnt);
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();
        
        $recommend = $sql_cmd;

        // 登録数、登録者名を取得
        foreach ($recommend as $item) {
            $item->detail_cnt = DB::table('recommenddetail')->where('recom_id', $item->id)->count();
            $user = DB::table('users')->where('id', $item->user_id)->select('name')->first();
            $item->user_name = $user->name ?? null;
        }

        return $recommend; 
    }
    //おすすめ詳細情報を取得
    public static function getRecommend_detail($recom_id)
    {
        //おすすめ情報を取得
        $recommend = DB::table('recommend')->where('id', $recom_id)->first();

        //登録情報を取得
        $sql_cmd = DB::table('recommenddetail')->where('recom_id', $recom_id);
        switch($recommend->category){
            case 0: //曲
                $item1   = "曲名";
                $item2   = "";
                $table   = "mus";
                $sql_cmd = $sql_cmd->join('musics', 'recommenddetail.detail_id', '=', 'musics.id');
                $sql_cmd = $sql_cmd->join('artists', 'musics.art_id', '=', 'artists.id');
                //$sql_cmd = $sql_cmd->select('recommenddetail.id','musics.name As mus_name','artists.name As art_name')->get();
                $sql_cmd = $sql_cmd->select('recommenddetail.id','musics.id As detail_id','musics.name','artists.name As art_name')->get();
                break;
            case 1: //ｱｰﾃｨｽﾄ
                $item1   = "アーティスト名";
                $item2   = "";
                $table   = "art";
                $sql_cmd = $sql_cmd->join('artists', 'recommenddetail.detail_id', '=', 'artists.id');
                //$sql_cmd = $sql_cmd->select('recommenddetail.id','artists.name As art_name')->get();
                $sql_cmd = $sql_cmd->select('recommenddetail.id','artists.id As detail_id','artists.name')->get();
                break;
            case 2: //ｱﾙﾊﾞﾑ
                $item1   = "アルバム名";
                $item2   = "";
                $table   = "alb";
                $sql_cmd = $sql_cmd->join('albums', 'recommenddetail.detail_id', '=', 'albums.id');
                $sql_cmd = $sql_cmd->join('artists', 'albums.art_id', '=', 'artists.id');
                $sql_cmd = $sql_cmd->select('recommenddetail.id','albums.id As detail_id','albums.name','artists.name As art_name')->get();
                break;
            case 3: //ﾌﾟﾚｲﾘｽﾄ
                $item1   = "プレイリスト名";
                $item2   = "";
                $table   = "pl";
                $sql_cmd = $sql_cmd->join('playlist', 'recommenddetail.detail_id', '=', 'playlist.id');
                $sql_cmd = $sql_cmd->select('recommenddetail.id','playlist.id As detail_id','playlist.name')->get();
                break;
            default:
                break;
        }
        $recommend->detail = $sql_cmd; 
        $recommend->table = $table; 


        //ログインしているユーザーはお気に入り情報も取得する
        foreach ($recommend->detail as $item) {
            if (Auth::check()) {
                $item->fav_flag = Favorite::chkFavorite(Auth::id(), $recommend->table, $item->detail_id);
            }else{
                $item->fav_flag = 0;
            }
        }

        //dd($recommend->detail );
        $recommend->item1 = $item1; 
        $recommend->item2 = $item2;    
        return $recommend; 
    }
    //作成
    public static function createRecommend($data)
    {
        make_error_log("createRecommend.log","---------start----------");
        //try {
            
            if(!$data['name'])      return ['id' => null, 'error_code' => 1];   //データ不足
            //if(!$data['category'])  return ['id' => null, 'error_code' => 2];   //データ不足
            //選択カテゴリの既存表示順(最後尾)を取得
            $data['sort_num'] = DB::table('recommend')->where('category', $data['category'])->max('sort_num');
            if($data['sort_num']==null)     $data['sort_num']=1;
            else                            $data['sort_num']++;
            make_error_log("createRecommend.log","data=".print_r($data,1));
            $result = self::create($data);
            make_error_log("createRecommend.log","success");
            return ['id' => null, 'error_code' => 0];   //追加成功
        //} catch (\Exception $e) {
            make_error_log("createRecommend.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        //}
    }
    //変更
    public static function chgRecommend($data)
    {
        make_error_log("chgRecommend.log","---------start----------");
        try {
            if(!$data['id'])                return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['name'])              return ['id' => null, 'error_code' => 2];   //データ不足
            if($data['disp_flag']==null)    return ['id' => null, 'error_code' => 3];   //データ不足    カテゴリ:0がはじかれる
            //DB追加処理チェック
            make_error_log("chgRecommend.log","data=".print_r($data,1));
            /*  クエリビルダではupdated_atが自動更新されない
            DB::table('recommend')->where('id', $data['id'])
            ->update([
                'name' => $data['name']
            ]);
            */
            Recommend::where('id', $data['id'])->update(['name' => $data['name'],'disp_flag' => $data['disp_flag']]);

            make_error_log("chgRecommend.log","success");
            return ['id' => null, 'error_code' => 0];   //追加成功
        } catch (\Exception $e) {
            make_error_log("chgRecommend.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
    }
    //削除
    public static function delRecommend($data)//-------------------------------------------ﾌﾟﾚｲﾘｽﾄ削除機能　開発必須
    {
        make_error_log("delRecommend.log","---------start----------");
        try {
            if(!$data['recom_id'])  return ['id' => null, 'error_code' => -1];   //データ不足
            make_error_log("delRecommend.log","delete_recom_id=".$data['recom_id']);

            DB::table('recommenddetail')->where('recom_id', $data['recom_id'])->delete();
            DB::table('recommend')->where('id', $data['recom_id'])->delete();

            make_error_log("delRecommend.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功
        } catch (\Exception $e) {
            make_error_log("delRecommend.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗
        }
    }
    //おすすめ表示順変更
    public static function chgsortRecommend($data)
    {
        make_error_log("chgsortRecommend.log","---------start----------");
        try {
            if(!$data['fnc'])       return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['id'])        return ['id' => null, 'error_code' => 1];   //データ不足
            //if(!$data['category'])  return ['id' => null, 'error_code' => 1];   //データ不足  カテゴリ:0がはじかれる

            make_error_log("chgsortRecommend.log","sort_chg id=".$data['id']."-".$data['fnc']);
            
            $sort1=DB::table('recommend')->where('id', $data['id'])->value('sort_num');

            if($data['fnc']=="up"){
                $sort2=$sort1-1;
                //0以下は不可
                if($sort2<=0)           return ['id' => null, 'error_code' => -1];   //削除失敗
            }elseif($data['fnc']=="down"){
                $sort2=$sort1+1;
                $max_num=DB::table('recommend')->where('category', $data['category'])->max('sort_num');
                //連番にするため最大値以上との入れ替えを禁止
                if($sort2>$max_num)     return ['id' => null, 'error_code' => -1];   //削除失敗
            }
            //表示順を入れ替え
            $replace_id = DB::table('recommend')->where('category', $data['category'])->where('sort_num', $sort2)->value('id');

            Recommend::where('id', $data['id'])->update(['sort_num' => $sort2]);
            Recommend::where('id', $replace_id)->update(['sort_num' => $sort1]);
            make_error_log("chgsortRecommend.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功
        } catch (\Exception $e) {
            make_error_log("chgsortRecommend.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗
        }

    }
    //おすすめ　追加・削除
    public static function chgRecommend_detail($data)
    {
        make_error_log("chgRecommend_detail.log","-------start-------");
        //try {
            if(!$data['recom_id'])     return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['detail_id'])    return ['id' => null, 'error_code' => 2];   //データ不足
            
            make_error_log("chgRecommend_detail.log","delete_pl_id=".$data['detail_id']);
            switch($data['fnc']){
                case "add":
                    $detail_id = DB::table('recommenddetail')->insert(['recom_id' => $data['recom_id'],'detail_id' => $data['detail_id']]);
                    break;
                case "del":
                    DB::table('recommenddetail')->where('recom_id', $data['recom_id'])->where('id', $data['detail_id'])->delete();
                    break;
                default:
            }
            make_error_log("chgRecommend_detail.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功
        //} catch (\Exception $e) {
            make_error_log("chgRecommend_detail.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗
        //}
    }


    //おすすめ取得　ユーザー用
    public static function getUserRecommendList($category,$disp_cnt=10){
        $recommend = null;
        //categoryに分岐
        $sql_cmd = DB::table('recommend');
        $sql_cmd = $sql_cmd->where('category', $category)->where('disp_flag', 1);
        $sql_cmd = $sql_cmd->limit($disp_cnt)->orderBy('sort_num', 'asc')->get();

        $recommend = $sql_cmd;
        if ($recommend->isNotEmpty()) {
            // $recommend にデータがある場合の処理
            foreach ($recommend as $key => $item) {
                $item->recom_id = $item->id;
                $sql_cmd = DB::table('recommenddetail as dtl');
                $sql_cmd = $sql_cmd->where('dtl.recom_id', '=', $item->id);
                $detail_list = $sql_cmd->get();

                $item->count = count($detail_list);
                //収録曲が０件の場合は除外
                if ($item->count == 0) unset($recommend[$key]);
                $add_list = [];
                //if (is_null($detail->detail_id)) continue;
                switch($category){
                    case 0://曲
                        foreach ($detail_list as $key => $detail) {
                            $add_list[$key] = Music::getMusic_detail($detail->detail_id);
                        }
                        $item->detail = $add_list;
                        break;
                    
                    case 1://アーティスト
                        //現在はアーティストに画像情報がない
                        //$artist = new Artist();
                        //foreach ($detail_list as $key => $detail) {
                            //$add_list[$key] = $artist->getMusic_detail($detail->detail_id);
                        //}
                        //$item->detail = $add_list;
                        break;
                    case 2://アルバム
                        foreach ($detail_list as $key => $detail) {
                            $add_list[$key] = Album::getAlbum_detail($detail->detail_id);
                        }
                        $item->detail = $add_list;
                        break;
                    case 3://プレイリスト          
                        foreach ($detail_list as $key => $detail) {
                            $add_list[$key] = Playlist::getPlaylist_detail($detail->detail_id);
                            //プレイリストの収録曲が０件の場合は除外
                            if (count($add_list[$key]->music) == 0) unset($add_list[$key]);
                        }     
                        if (count($add_list) == 0) unset($recommend[$key]);
                        //dd($add_list);     
                        $item->detail = $add_list;
                        break;
                    default:
                        $recommend = DB::table('musics')
                        ->select('musics.id', 'musics.name', 'albums.name AS alb_name',
                                DB::raw('GROUP_CONCAT(COALESCE(musics.aff_id, albums.aff_id)) AS aff_id'))
                        ->leftJoin('albums', 'albums.id', '=', 'musics.alb_id')
                        ->groupBy('musics.id', 'musics.name', 'albums.name')
                        ->limit($disp_cnt)
                        ->get();
                    
                }
                
                //トップ用のリンクを取得
                //$recommend[0]->href = $recommend[0]->detail[0]->href;
                //$recommend[0]->src = $recommend[0]->detail[0]->src;
                //dd($recommend);
            }
        }else{

        }
        return $recommend;
    }

    
}
