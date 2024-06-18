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
    public static function getPlaylist_list($disp_cnt=null,$pageing=false,$keyword=null,$admin_flag=0)
    {
        $sql_cmd = DB::table('playlist')
            ->orderBy('created_at', 'desc')
            ->where('name', 'like', "%$keyword%")
            ->where('admin_flag', $admin_flag);
        
        // デフォルト5件
        if ($disp_cnt === null)             $disp_cnt=5;
        // ページング・取得件数指定・全件で分岐
        if ($pageing)                       $sql_cmd = $sql_cmd->paginate($disp_cnt);
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $playlist = $sql_cmd;

        // 楽曲登録数、登録者名を取得
        foreach ($playlist as $item) {
            $item->mus_cnt = DB::table('playlistdetail')->where('pl_id', $item->id)->count();
            $user = DB::table('users')->where('id', $item->user_id)->select('name')->first();
            $item->user_name = $user->name ?? null;
        }

        return $playlist; 
    }
    //詳細変更　収録曲変更
    public static function getPlaylist_detail($pl_id)
    {
        //プレイリスト情報を取得
        $playlist = DB::table('playlist')->where('id', $pl_id)->first();

        //収録数、収録曲を取得
        $music_list = DB::table('playlistdetail')->where('pl_id', $pl_id)
        ->get();
        /*
        $playlist->music = DB::table('playlistdetail')->where('pl_id', $pl_id)
            ->join('musics', 'playlistdetail.mus_id', '=', 'musics.id')
            ->join('artists', 'musics.art_id', '=', 'artists.id')
            ->select('playlistdetail.id')
            ->get();
        */
        $detail_list = [];
        //dd($music_list);
        foreach ($music_list as $key => $item) {
            $detail_list[$key] = Music::getMusic_detail($item->mus_id);
        }
        //ログインしているユーザーはお気に入り情報も取得する
        if (Auth::check()) {
            $playlist->fav_flag = Favorite::chkFavorite(Auth::id(), "pl", $pl_id);
        }else{
            $playlist->fav_flag = 0;
        }
        $playlist->music = $detail_list;  
        //件数を取得
        $playlist->pl_cnt = count($detail_list);
    
        //dd($playlist);
        //画像情報を付与 getMusic_detailで取得
        //$playlist=setAffData($playlist);
        
        return $playlist; 
    }
    //作成
    public static function createPlaylist($data)
    {
        make_error_log("createPlaylist.log","---------start----------");
        try {
            if(!$data['name'])  return ['id' => null, 'error_code' => 1];   //データ不足
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

            /*  クエリビルダではupdated_atが自動更新されない
            DB::table('playlist')->where('id', $data['id'])
            ->update([
                'name' => $data['name']
            ]);
            */
            Playlist::where('id', $data['id'])
                ->update(['name' => $data['name']]);

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
            if(!$data['pl_id'])  return ['id' => null, 'error_code' => 1];   //データ不足
            make_error_log("delPlaylist.log","delete_pl_id=".$data['pl_id']);

            //DB::table('playlistdetail')->where('pl_id', $data['pl_id'])->where('id', $data['detail_id'])->delete();
            DB::table('playlistdetail')->where('pl_id', $data['pl_id'])->delete();
            DB::table('playlist')->where('id', $data['pl_id'])->delete();

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
            if(!$data['pl_id'])     return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['detail_id'])    return ['id' => null, 'error_code' => 2];   //データ不足
            
            make_error_log("chgPlaylist_detail.log","delete_pl_id=".$data['pl_id']);
            switch($data['fnc']){
                case "add":
                    $pl_id = DB::table('playlistdetail')->insert(['pl_id' => $data['pl_id'],'mus_id' => $data['detail_id']]);
                    break;
                case "del":
                    DB::table('playlistdetail')->where('pl_id', $data['pl_id'])->where('id', $data['detail_id'])->delete();
                    break;
                default:
            }
            make_error_log("chgPlaylist_detail.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功
        } catch (\Exception $e) {
            make_error_log("chgPlaylist_detail.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗
        }
    }
}
