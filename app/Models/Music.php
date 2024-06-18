<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class Music extends Model
{
    use HasFactory;
    protected $table = 'musics';    //musicテーブルが指定されてしまうため、強制的に指定
    protected $fillable = ['alb_id', 'art_id', 'name', 'release_date', 'link', 'aff_id'];     //一括代入の許可

    //楽曲一覧取得
    public static function getMusic_list($disp_cnt=null,$pageing=false,$keyword=null)
    {
        $sql_cmd = DB::table('musics')
        ->join('artists', 'musics.art_id', '=', 'artists.id')
        ->leftJoin('albums', 'musics.alb_id', '=', 'albums.id')
        ->where(function ($query) use ($keyword) {
            $query->where('musics.name', 'like', "%{$keyword}%")
                ->orWhere('artists.name', 'like', "%{$keyword}%")
                ->orWhere('artists.name2', 'like', "%{$keyword}%")
                ->orWhere('albums.name', 'like', "%{$keyword}%");
        })
        ->orderBy('musics.created_at', 'desc')
        ->select('musics.*', 'artists.name as art_name', 'musics.id as mus_id', 'albums.name as alb_name');

        // デフォルト5件
        if ($disp_cnt === null)             $disp_cnt=5;
        // ページング・取得件数指定・全件で分岐
        if ($pageing)                       $sql_cmd = $sql_cmd->paginate($disp_cnt);
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $music = $sql_cmd;
        //dd($music);
        
        //画像情報を付与
        $music=setAffData($music);
        return $music; 
    }
    //取得
    public static function getMusic_detail($mus_id)
    {
        try {
            //オブジェクトの場合と配列の場合の2パターンを作成して負荷軽減
            //楽曲情報を取得
            $music = DB::table('musics')->where('id', $mus_id)->first();
            $music->mus_id = $music->id;
            $music->mus_name = $music->name;

            //アーティスト情報を取得
            $artist = DB::table('artists')->where('id', $music->art_id)->first();
            $music->art_name = $artist->name;

            //アルバム情報を取得
            $album = DB::table('albums')->where('id', $music->alb_id)->first();
            $music->alb_name = $album->name;
            if($music->aff_id == null) $music->aff_id = $album->aff_id;
            if($music->release_date == null) $music->release_date = $album->release_date;
            //ログインしているユーザーはお気に入り情報も取得する
            if (Auth::check()) {
                $music->fav_flag = Favorite::chkFavorite(Auth::id(), "mus", $music->mus_id);
            }else{
                $music->fav_flag = 0;
            }
            //dd($music);
            //画像情報を付与
            $music=setAffData($music);

            return $music; 
        } catch (\Exception $e) {
            return null; 
        }
    }
    //作成
    public static function createMusic($data)
    {
        make_error_log("createMusic.log","-------start-------");
        make_error_log("createMusic.log","create_data=".print_r($data,1));
        //データチェック
        if(!isset($data['name']) || !$data['name'])     return ['id' => null, 'error_code' => 1];   //データ不足
        if(!isset($data['art_id']) || !$data['art_id']) return ['id' => null, 'error_code' => 2];   //データ不足

        //if(!$data['alb_id'])    return ['id' => null, 'error_code' => 3];   //データ不足      シングルもあるため
        //dd($data);
        //DB追加処理チェック
        //dd($data);
        try {
            // DBに追加
            $music = self::create($data);
            $mus_id = $music->id;
            make_error_log("createMusic.log","success");
            return ['id' => $mus_id, 'error_code' => 0];   //追加成功

        } catch (\Exception $e) {
            make_error_log("createMusic.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
    }
    //削除：曲・アフィリエイトリンク　を削除
    public static function delMusic($id)
    {
        make_error_log("delMusic.log","-------start-------");

        try {
            make_error_log("delMusic.log","delete_mus_id=".$id);
            // musicデータ削除
            $music = DB::table('musics')->where('id', $id)->first();
            DB::table('musics')->where('id', $id)->delete();
            DB::table('affiliates')->where('id', $music->aff_id)->delete();

            make_error_log("delMusic.log","success");
            return ['id' => $music->id, 'error_code' => 0];   //更新成功

        } catch (\Exception $e) {
            make_error_log("delMusic.log","failure");
            return ['id' => null, 'error_code' => -1];   //更新失敗
        }
    }
    //変更
    public static function chgMusic($data)
    {
        make_error_log("chgMusic.log","-------start-------");
        try {
            $music = DB::table('musics')->where('id', $data['id'])->first();
            make_error_log("chgMusic.log","before_data=".print_r($music,1));
            // 更新対象となるカラムと値を連想配列に追加
            $updateData = [];
            $updateData['id'] =             $data['id'];
            $updateData['alb_id'] =         (isset($data['alb_id']))        ? $data['alb_id'] : $music->alb_id;
            $updateData['art_id'] =         (isset($data['art_id']))        ? $data['art_id'] : $music->art_id;
            $updateData['name'] =           (isset($data['name']))          ? $data['name'] : $music->name;
            $updateData['release_date'] =   (isset($data['release_date']))  ? $data['release_date'] : $music->release_date;
            $updateData['link'] =           (isset($data['link']))          ? $data['link'] : $music->link;
            $updateData['aff_id'] =         (isset($data['aff_id']))        ? $data['aff_id'] : $music->aff_id;
        
            make_error_log("chgMusic.log","after_data=".print_r($updateData,1));
            // musicデータ更新
            /*  クエリビルダではupdated_atが自動更新されない
            DB::table('musics')->where('id', $updateData['id'])->update($updateData);
            */
            Music::where('id', $updateData['id'])->update($updateData);

            make_error_log("chgMusic.log","success");
            return ['id' => $music->id, 'error_code' => 0];   //更新成功

        } catch (\Exception $e) {
            make_error_log("chgMusic.log","failure");
            return ['id' => null, 'error_code' => -1];   //更新失敗
        }
    }
}
