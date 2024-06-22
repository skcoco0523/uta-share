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
    public static function getArtist_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null)
    {
        $sql_cmd = DB::table('artists')->orderBy('created_at', 'desc')
            ->where('name', 'like', "%$keyword%")
            ->orWhere('name2', 'like', "%$keyword%");
        
        // デフォルト5件
        if ($disp_cnt === null)             $disp_cnt=5;
        // ページング・取得件数指定・全件で分岐
        if ($pageing)                       $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $artist = $sql_cmd;
        //アーティストはお気に入り登録なし
        foreach($artist as $art){
            $art->fav_flag = 0;
        }

        return $artist; 
    }
    //取得
    public static function getArtist_detail($art_id)
    {
        try {
            //アルバム情報を取得
            $artist = DB::table('artists')
                ->where('artists.id', $art_id)
                ->select('artists.*','artists.id as art_id','artists.name as art_name')
                ->first();
            //アーティストは固定でfalse
            $artist->fav_flag = 0;
            //dd($artist);
            
            //曲取得

            //アルバム取得


            
            return $artist; 
        } catch (\Exception $e) {
            make_error_log("getArtist_detail.log","failure");
            return null; 
        }
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