<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Artist extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'name2', 'debut', 'sex'];
    //取得
    public static function getArtist($disp_cnt=null,$pageing=false,$keyword=null)
    {
        $sql_cmd = DB::table('artists')->orderBy('created_at', 'desc')
            ->where('name', 'like', "%$keyword%")
            ->orWhere('name2', 'like', "%$keyword%");
        
        // デフォルト5件
        if ($disp_cnt === null) $disp_cnt=5;  
        // ページングを適用してデータを取得する
        if ($pageing) {                     $sql_cmd = $sql_cmd->paginate($disp_cnt);
        // 件数指定で取得
        }elseif($disp_cnt !== null){        $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        // 全データ取得
        }else{                              $sql_cmd = $sql_cmd->get();
        }
        $artist = $sql_cmd;

        return $artist; 
    }
    //作成
    public static function createArtist($data)
    {
        if(!$data['name'])  return 1;   //データ不足
        //DB追加処理チェック
        try {
            $result = self::create($data);
            return 0;   //追加成功
        } catch (\Exception $e) {
            return -1;   //追加失敗
        }
    }
    //変更
    public static function chgArtist($data)
    {
        $msg="";
        //データチェック
        if(!$data['id'])    return "更新対象をテーブルから選択してください。";
        if(!$data['name'])  return "アーティスト名を入力してください。";

        $artist = DB::table('artists')->where('id', $data['id'])->first();
        if ($artist !== null) {
            /*  クエリビルダではupdated_atが自動更新されない
            DB::table('artists')->where('id', $data['id'])
            ->update([
                'name' => $data['name'], 
                'name2' => $data['name2'], 
                'debut' => $data['debut'], 
                'sex' => $data['sex'], 
            ]);
            */
            
            Artist::where('id', $data['id'])
                ->update([
                    'name' => $data['name'],
                    'name2' => $data['name2'],
                    'debut' => $data['debut'],
                    'sex' => $data['sex'],
                ]);

            $msg = "更新しました。";
        } else {
            $msg = "更新に失敗しました。";
        }
        return $msg;
    }
    //削除
    public static function delArtist($id)
    {
        $msg="";
        //データチェック
        $alb_cnt = DB::table('albums')->where('art_id', $id)->count();
        $art_cnt = DB::table('musics')->where('art_id', $id)->count();
        if($alb_cnt)        $msg="アルバム ";
        if($art_cnt)        $msg.="音楽 ";
        if($msg)            $msg.="に紐づいているため削除できません。\n対象を先に削除してください。";
        if($msg!="") return $msg;

        $artist = DB::table('artists')->where('id', $id)->first();
        if ($artist !== null) {
            DB::table('artists')->where('id', $id)->delete();
            $msg = "アーティスト：{$artist->name}を削除しました。";
        } else {
            $msg = "指定されたアーティストは存在しません。";
        }
        return $msg;
    }
}