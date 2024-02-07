<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;
    protected $table = 'musics';    //musicテーブルが指定されてしまうため、強制的に指定
    protected $fillable = ['alb_id', 'art_id', 'name', 'release_date', 'aff_id'];     //一括代入の許可


    //作成
    public static function createMusic($data)
    {
        make_error_log("createMusic.log","-------start-------");
        make_error_log("createMusic.log","alb_id=".$data['alb_id']." art_id=".$data['art_id']." name=".$data['name']." release_date=".$data['release_date']);
        //データチェック
        if(!$data['name'])      return ['id' => null, 'error_code' => 1];   //データ不足
        if(!$data['art_id'])    return ['id' => null, 'error_code' => 2];   //データ不足
        //if(!$data['alb_id'])    return ['id' => null, 'error_code' => 3];   //データ不足      シングルもあるため
        //dd($data);
        //DB追加処理チェック
        try {
            // DBに追加
            $Music = self::create($data);
            $mus_id = $Music->id;
            make_error_log("createMusic.log","success");
            return ['id' => $mus_id, 'error_code' => 0];   //追加成功

        } catch (\Exception $e) {
            make_error_log("createMusic.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加成功
        }
    }
    //削除
    public static function delMusic($id)
    {
        make_error_log("delMusic.log","-------start-------");
        make_error_log("delMusic.log","delete_mus_id=".$id);

        $msg="";
        $musics = DB::table('musics')->where('id', $id)->first();
        if ($artist !== null) {
            DB::table('musics')->where('id', $id)->delete();
            $msg = "楽曲：{$musics->name}を削除しました。";
        } else {
            $msg = "指定された楽曲は存在しません。";
        }
        return $msg;
    }
}
