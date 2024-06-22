<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Music;
use App\Models\Favorite;

class Album extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'art_id', 'release_date', 'aff_id'];     //一括代入の許可
    //アルバム一覧取得
    public static function getAlbum_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null)
    {
        $sql_cmd = DB::table('albums')
            ->orderBy('created_at', 'desc')
            ->where('name', 'like', "%$keyword%");
        
        // デフォルト5件
        if ($disp_cnt === null)             $disp_cnt=5;
        // ページング・取得件数指定・全件で分岐
        if ($pageing)                       $sql_cmd = $sql_cmd->paginate($disp_cnt);
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $album = $sql_cmd;

        //収録数、収録曲を取得
        foreach ($album as $alb) {
            $music_list = DB::table('musics')->where('alb_id', $alb->id)->select('name')->get();
            $alb->music_list = "";
            $alb->mus_cnt = 0;
            foreach ($music_list as $music) {
                $alb->music_list = $alb->music_list. $music->name. "\r\n";
                $alb->mus_cnt++ ;
            }
        }
        //アーティスト名を取得
        foreach ($album as $alb) {
            $alb->art_name = DB::table('artists')->where('id', $alb->art_id)->first()->name;
            //ログインしているユーザーはお気に入り情報も取得する
            if (Auth::check())      $alb->fav_flag = Favorite::chkFavorite(Auth::id(), "alb", $alb->id);
            else                    $alb->fav_flag = 0;
        }

        //画像情報を付与
        $album=setAffData($album);
        return $album; 
    }
    //取得
    public static function getAlbum_detail($alb_id)
    {
        try {
            
            //アルバム情報を取得
            $album = DB::table('albums')
                ->join('artists', 'albums.art_id', '=', 'artists.id')
                ->leftJoin('musics', 'albums.id', '=', 'musics.alb_id')
                ->where('albums.id', $alb_id)
                ->select('albums.*','albums.id as alb_id','artists.name as art_name')
                ->first();
            
            if ($album) {
                // 収録曲の詳細情報を取得
                $music = DB::table('musics')->where('alb_id', $album->alb_id)->get();
                $detail_list = [];
                foreach ($music as $key => $item) {
                    $detail_list[$key] = Music::getMusic_detail($item->id);
                }
                $album->music = $detail_list;
                $album->mus_cnt = count($album->music);
            
                // ログインしているユーザーはお気に入り情報も取得する
                $album->fav_flag = Auth::check() ? Favorite::chkFavorite(Auth::id(), "alb", $alb_id) : 0;
            }
            
            //画像情報を付与
            $album=setAffData($album);
            
            return $album; 
        } catch (\Exception $e) {
            make_error_log("getAlbum_detail.log","failure");
            return null; 
        }
    }
    //作成
    public static function createAlbum($data)
    {
        make_error_log("createAlbum.log","-------start-------");
        make_error_log("createAlbum.log","name=".$data['name']." art_id=".$data['art_id']." release_date=".$data['release_date']." aff_id=".$data['aff_id']);

        //データチェック
        if(!isset($data['name']) || !$data['name'])     return ['id' => null, 'error_code' => 1];   //データ不足
        if(!isset($data['art_id']) || !$data['art_id']) return ['id' => null, 'error_code' => 2];   //データ不足
        if(!isset($data['aff_id']) || !$data['aff_id']) return ['id' => null, 'error_code' => 3];   //データ不足
        
        //DB追加処理チェック
        try {
            // DBに追加
            $Album = self::create($data);
            $alb_id = $Album->id;
            make_error_log("createAlbum.log","success");
            return ['id' => $alb_id, 'error_code' => 0];   //追加成功

        } catch (\Exception $e) {
            make_error_log("createAlbum.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加成功
        }
    }
    //変更
    public static function chgAlbum($data)
    {
        //データチェック
        if(!isset($data['id']) || !$data['id'])         return ['id' => null, 'error_code' => 1];   //データ不足
        if(!isset($data['art_id']) || !$data['art_id']) return ['id' => null, 'error_code' => 2];   //データ不足
        if(!isset($data['name']) || !$data['name'])     return ['id' => null, 'error_code' => 3];   //データ不足
        
        make_error_log("chgAlbum.log","-------start-------");
        make_error_log("chgAlbum.log","id=".$data['id']." art_id=".$data['art_id']." name=".$data['name']);
        
        
        $album = DB::table('albums')->where('id', $data['id'])->first();
        if ($album !== null) {
            /*  クエリビルダではupdated_atが自動更新されない
                DB::table('albums')->where('id', $data['id'])
                ->update([
                    'name' => $data['name'], 
                    'art_id' => $data['art_id'], 
                    'release_date' => $data['release_date'], 
            ]);
            */
            Album::where('id', $data['id'])
            ->update([
                'name' => $data['name'], 
                'art_id' => $data['art_id'], 
                'release_date' => $data['release_date'], 
            ]);

            //アーティストが変更された場合、曲のart_idも更新する
            if($album->art_id != $data['art_id']){
                make_error_log("chgAlbum.log","change:musics.art_id");
                DB::table('musics')->where('alb_id', $data['id'])->update(['art_id' => $data['art_id']]);
            }


            return ['id' => $data['id'], 'error_code' => 0];   //更新成功
        } else {
            return ['id' => null, 'error_code' => -1];   //更新失敗
        }
    }
    //削除：アルバム・アフィリエイトリンク・収録曲　を削除                    ●●●●●●●●●戻り値にエラーコードを返して、呼び出しもとでエラーメッセージを定義●●●●●●●●
    public static function delAlbum($id)
    {
        $msg="";
        //データチェック
        $musics = DB::table('musics')->where('alb_id', $id)->get();
        $albums = DB::table('albums')->where('id', $id)->first();
        
        if ($albums !== null) {
            make_error_log("delAlbum.log","-------start-------");
            //削除対象アルバムの収録曲削除
            foreach ($musics as $music) {
                make_error_log("delAlbum.log","mus_id=".$music->id);
                DB::table('musics')->where('id', $music->id)->delete();
            }
            DB::table('albums')->where('id', $albums->id)->delete();
            DB::table('affiliates')->where('id', $albums->aff_id)->delete();
            make_error_log("delAlbum.log","alb_id=".$albums->id, " alb_id=".$albums->id, " alb_name=".$albums->name, " alb_aff_id=".$albums->aff_id);

            $msg = "アルバム：{$albums->name}、収録曲を削除しました。";
        } else {
            $msg = "指定されたアルバムは存在しません。";
        }
        return $msg;
    }
}
