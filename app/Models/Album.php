<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Album extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'art_id', 'release_date', 'aff_id'];     //一括代入の許可
    //アルバム一覧取得
    public static function getAlbum_list($disp_cnt=null,$pageing=false,$keyword=null)
    {
        if ($pageing) {
            // ページングを適用してデータを取得する     デフォルト5件
            if ($disp_cnt === null) $disp_cnt=5;  
            $album = DB::table('albums')
                ->orderBy('created_at', 'desc')
                ->where('name', 'like', "%$keyword%")
                ->paginate($disp_cnt);
        }elseif($disp_cnt !== null){
            //件数指定で取得                        デフォルト5件
            if ($disp_cnt === null) $disp_cnt=5;  
            $album = DB::table('albums')
                ->orderBy('created_at', 'desc')
                ->where('name', 'like', "%$keyword%")
                ->limit($disp_cnt)
                ->get();
        }else{
            //全データ取得
            $album = DB::table('albums')
                ->orderBy('created_at', 'desc')
                ->where('name', 'like', "%$keyword%")
                ->get();
        }
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
        }

        //画像情報を付与
        $album=setAffData($album);
        return $album; 
    }
    //詳細変更　収録曲変更
    public static function getAlbum_detail($alb_id)
    {
        //アルバム情報を取得
        $album = DB::table('albums')->where('id', $alb_id)->first();
        //アーティスト名を取得
        $album->art_name = DB::table('artists')->where('id', $album->art_id)->first()->name;
        //収録数、収録曲を取得
        $album->music = DB::table('musics')->where('alb_id', $album->id)->get();
        //画像情報を付与
        $album=setAffData($album);
        
        return $album; 
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
        $msg="";
        //データチェック
        if(!isset($data['id']) || !$data['id'])         return ['id' => null, 'error_code' => 1];   //データ不足
        if(!isset($data['art_id']) || !$data['art_id']) return ['id' => null, 'error_code' => 2];   //データ不足
        if(!isset($data['name']) || !$data['name'])     return ['id' => null, 'error_code' => 3];   //データ不足
        
        
        
        $artist = DB::table('albums')->where('id', $data['id'])->first();
        if ($artist !== null) {
            DB::table('albums')->where('id', $data['id'])
            ->update([
                'name' => $data['name'], 
                'art_id' => $data['art_id'], 
                'release_date' => $data['release_date'], 
            ]);
            return ['id' => $data['id'], 'error_code' => 0];   //更新成功
        } else {
            return ['id' => null, 'error_code' => -1];   //更新失敗
        }
    }
    //削除
    public static function delAlbum($id)
    {
        $msg="";
        //データチェック
        $musics = DB::table('musics')->where('alb_id', $id)->get();
        $albums = DB::table('albums')->where('id', $id)->first();
        
        if ($albums !== null) {
            make_error_log("delAlbum.log","-------start-------");
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
