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
    public static function getMusic_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null)
    {
        $sql_cmd = DB::table('musics');
        $sql_cmd = $sql_cmd->join('artists', 'musics.art_id', '=', 'artists.id');
        $sql_cmd = $sql_cmd->leftJoin('albums', 'musics.alb_id', '=', 'albums.id');
        if($keyword){
            $sql_cmd = $sql_cmd->where(function ($query) use ($keyword) {
                                $query->where('musics.name', 'like', "%{$keyword}%")
                                    ->orWhere('artists.name', 'like', "%{$keyword}%")
                                    ->orWhere('artists.name2', 'like', "%{$keyword}%")
                                    ->orWhere('albums.name', 'like', "%{$keyword}%");});
        }
        $sql_cmd = $sql_cmd->orderBy('musics.created_at', 'desc');
        $sql_cmd = $sql_cmd->select('musics.*', 'artists.name as art_name', 'musics.id as mus_id', 
                                    'albums.name as alb_name', 'albums.aff_id as alb_aff_id');

        // デフォルト5件
        if ($disp_cnt === null)             $disp_cnt=5;
        // ページング・取得件数指定・全件で分岐
        if ($pageing)                       $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $music = $sql_cmd;
        //dd($music);
        foreach($music as $mus){
            //アフィリエイトIDを書き換える
            if($mus->aff_id == null) $mus->aff_id = $mus->alb_aff_id;
            //ログインしているユーザーはお気に入り情報も取得する
            if (Auth::check())  $mus->fav_flag = Favorite::chkFavorite(Auth::id(), "mus", $mus->mus_id);
            else                $mus->fav_flag = 0;
        }
        
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
            $music = DB::table('musics')
                ->join('artists', 'musics.art_id', '=', 'artists.id')
                ->leftJoin('albums', 'musics.alb_id', '=', 'albums.id')
                ->where('musics.id', $mus_id)
                ->select(
                    'musics.*',
                    'musics.id as mus_id','musics.name as mus_name',
                    'artists.name as art_name',
                    'albums.name as alb_name','albums.aff_id as album_aff_id','albums.release_date as album_release_date'
                )
                ->first();

            if ($music) {
                if (is_null($music->aff_id))        $music->aff_id = $music->album_aff_id;
                if (is_null($music->release_date))  $music->release_date = $music->album_release_date;
            }
            //ログインしているユーザーはお気に入り情報も取得する
            if (Auth::check())  $music->fav_flag = Favorite::chkFavorite(Auth::id(), "mus", $music->mus_id);
            else                $music->fav_flag = 0;
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
