<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Music;
use App\Models\Favorite;

class Playlist extends Model
{
    use HasFactory;
    protected $table = 'playlist';    //playlistsテーブルが指定されてしまうため、強制的に指定
    protected $fillable = ['name', 'user_id', 'admin_flag'];
    //取得
    public static function getPlaylist_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null)
    {
        $sql_cmd = DB::table('playlist');
        if($keyword){
            //マイプレイリスト
            if (isset($keyword['user_id'])) {
                $sql_cmd = $sql_cmd->where('playlist.user_id', Auth::id());
                $sql_cmd = $sql_cmd->where('playlist.admin_flag', 0);

            //ユーザーによる検索
            }elseif (isset($keyword['keyword'])) {
                $sql_cmd = $sql_cmd->where('playlist.name', 'like', '%'. $keyword['keyword']. '%');
                $sql_cmd = $sql_cmd->where('playlist.admin_flag', 1);
            
            //ユーザーによる検索条件なし検索
            }elseif (isset($keyword['user_serch'])) {
                $sql_cmd = $sql_cmd->where('playlist.admin_flag', 1);
            
            //管理者による検索
            }else{
                if (isset($keyword['search_playlist'])) 
                    $sql_cmd = $sql_cmd->where('playlist.name', 'like', '%'. $keyword['search_playlist']. '%');

                if (isset($keyword['search_admin_flag'])) 
                    $sql_cmd = $sql_cmd->where('playlist.admin_flag',$keyword['search_admin_flag']);
            }
        }

        $sql_cmd                = $sql_cmd->orderBy('created_at', 'desc');

        // ページング・取得件数指定・全件で分岐
        if ($pageing){
            if ($disp_cnt === null) $disp_cnt=5;
            $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        }                       
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $playlist = $sql_cmd;

        // 楽曲登録数、登録者名を取得
        foreach ($playlist as $item) {
            $item->mus_cnt = DB::table('playlistdetail')->where('pl_id', $item->id)->count();
            $user = DB::table('users')->where('id', $item->user_id)->select('name')->first();
            $item->user_name = $user->name ?? null;
            //ログインしているユーザーはお気に入り情報も取得する
            if (Auth::check())  $item->fav_flag = Favorite::chkFavorite(Auth::id(), "pl", $item->id);
            else                $item->fav_flag = 0;

            //ユーザー検索は詳細も取得
            if (isset($keyword['user_id']) || isset($keyword['user_serch'])) {
                $detail = Playlist::getPlaylist_detail($item->id);
                $item->detail = $detail->music;
            }
        }            
        

        return $playlist; 
    }
    //詳細変更　収録曲変更
    public static function getPlaylist_detail($pl_id)
    {
        //プレイリスト情報を取得
        $playlist = DB::table('playlist')->where('id', $pl_id)->first();
        if($playlist){
            //収録数、収録曲を取得
            $music_list = DB::table('playlistdetail')->where('pl_id', $pl_id)->get();
            $detail_list = [];
            //dd($music_list);
            foreach ($music_list as $key => $item) {
                $detail_list[$key] = Music::getMusic_detail($item->mus_id);
            }
            //ログインしているユーザーはお気に入り情報も取得する
            if (Auth::check())  $playlist->fav_flag = Favorite::chkFavorite(Auth::id(), "pl", $pl_id);
            else                $playlist->fav_flag = 0;
            $playlist->music = $detail_list;  
            //件数を取得
            $playlist->pl_cnt = count($detail_list);
            //dd($playlist);
            //画像情報を付与 getMusic_detailで取得
            //$playlist=setAffData($playlist);  
            return $playlist; 

        }else{ 
            return null; 

        }
    }
    //作成
    public static function createPlaylist($data)
    {
        make_error_log("createPlaylist.log","---------start----------");
        try {
            if(!$data['name'])  return ['id' => null, 'error_code' => 1];   //データ不足
            $data['user_id'] = Auth::id();
            //DB追加処理チェック
            make_error_log("createPlaylist.log","data=".print_r($data,1));
            $result = self::create($data);
            make_error_log("createPlaylist.log","success");
            return ['id' => null, 'error_code' => 0];   //追加成功
        } catch (\Exception $e) {
            make_error_log("createPlaylist.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
    }
    //変更
    public static function chgPlaylist($data)
    {
        make_error_log("chgPlaylist.log","---------start----------");
        try {
            if(!$data['id'])    return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['name'])  return ['id' => null, 'error_code' => 2];   //データ不足
            //DB追加処理チェック
            make_error_log("chgPlaylist.log","data=".print_r($data,1));

            //管理者以外は自身のﾌﾟﾚｲﾘｽﾄのみ更新可能
            $user = Auth::user();
            if($user->admin_flag)
                Playlist::where('id', $data['id'])->update(['name' => $data['name']]);
            else
                Playlist::where('id', $data['id'])->where('user_id', $user->id)->update(['name' => $data['name']]);

            make_error_log("chgPlaylist.log","success");
            return ['id' => null, 'error_code' => 0];   //追加成功
        } catch (\Exception $e) {
            make_error_log("chgPlaylist.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
    }
    //削除
    public static function delPlaylist($data)//-------------------------------------------ﾌﾟﾚｲﾘｽﾄ削除機能　開発必須
    {
        make_error_log("delPlaylist.log","---------start----------");
        try {
            if(!$data['id'])  return ['id' => null, 'error_code' => 1];   //データ不足
            make_error_log("delPlaylist.log","delete_pl_id=".$data['id']);

            //管理者以外は自身のﾌﾟﾚｲﾘｽﾄのみ更新可能
            $user = Auth::user();
            if($user->admin_flag){
                //リレーションでカスケード削除
                //DB::table('playlistdetail')->where('pl_id', $data['id'])->delete();
                Playlist::where('id', $data['id'])->delete();

                //おすすめからも削除する
                $chk_recom = DB::table('recommenddetail AS dtl')
                ->leftJoin('recommend AS recom', 'dtl.recom_id', '=', 'recom.id')
                ->where('dtl.detail_id', $data['id'])->where('recom.category', 3)
                ->select('recom.*','dtl.id As delete_id')
                ->first();
                if($chk_recom) DB::table('recommenddetail')->where('id', $chk_recom->delete_id)->delete();
                
            }else{
                //リレーションでカスケード削除
                //DB::table('playlistdetail')->where('pl_id', $data['id'])->delete();
                Playlist::where('id', $data['id'])->where('user_id', $user->id)->delete();

            }
            


            make_error_log("delPlaylist.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功
        } catch (\Exception $e) {
            make_error_log("delPlaylist.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗
        }
    }
    //プレイリスト収録曲　追加・削除
    public static function chgPlaylist_detail($data)
    {
        make_error_log("chgPlaylist_detail.log","-------start-------");
        try {
            if(!$data['id'])     return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['detail_id'])    return ['id' => null, 'error_code' => 2];   //データ不足
            
            make_error_log("chgPlaylist_detail.log","delete_pl_id=".$data['id']);
            switch($data['fnc']){
                case "add":
                    $pl_id = DB::table('playlistdetail')->insert(['pl_id' => $data['id'],'mus_id' => $data['detail_id']]);
                    break;
                case "del":
                    $deletedRows = DB::table('playlistdetail')->where(['pl_id'=>$data['id'],'mus_id'=>$data['detail_id']])->delete();
                    if ($deletedRows > 0) return ['id' => null, 'error_code' => 0];   //成功
                    else return ['id' => null, 'error_code' => -1];   //失敗
                    break;
                default:
                    return ['id' => null, 'error_code' => -1];   //失敗
                    break;
                    
            }
            make_error_log("chgPlaylist_detail.log","success");
            return ['id' => null, 'error_code' => 0];   //成功
        } catch (\Exception $e) {
            make_error_log("chgPlaylist_detail.log","failure");
            return ['id' => null, 'error_code' => -1];   //失敗
        }
    }
}
