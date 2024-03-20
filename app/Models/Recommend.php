<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Recommend extends Model
{
    use HasFactory;
    protected $table = 'recommend';    //recommendsテーブルが指定されてしまうため、強制的に指定
    protected $fillable = ['name', 'user_id', 'category', 'disp_flag', 'sort_num'];
    //取得
    public static function getRecommend_list($disp_cnt=null,$pageing=false,$keyword=null,$sort_flg=0)
    {
        $sql_cmd = DB::table('recommend')
            ->where('name', 'like', "%$keyword%");
        
        //ソート条件を判定
        if ($sort_flg)                      $sql_cmd = $sql_cmd->orderBy('sort_num', 'asc');
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
                $sql_cmd = $sql_cmd->paginate($disp_cnt);
                $sql_cmd = $sql_cmd->join('musics', 'recommenddetail.recom_id', '=', 'musics.id');
                $sql_cmd = $sql_cmd->join('artists', 'musics.art_id', '=', 'artists.id');
                $sql_cmd = $sql_cmd->select('recommenddetail.id','musics.name As mus_name','artists.name As art_name');
                break;
            case 1: //ｱｰﾃｨｽﾄ
                break;
            case 2: //ｱﾙﾊﾞﾑ
                break;
            case 3: //ﾌﾟﾚｲﾘｽﾄ
                break;
            default:
            break;
        }
        $recommend->detail = $sql_cmd;      
        //画像情報を付与
        //$album=setAffData($album);
        
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
            if(!$data['id'])  return 1;   //データ不足
            if(!$data['name'])  return 2;   //データ不足
            //DB追加処理チェック
            make_error_log("chgRecommend.log","data=".print_r($data,1));

            /*  クエリビルダではupdated_atが自動更新されない
            DB::table('recommend')->where('id', $data['id'])
            ->update([
                'name' => $data['name']
            ]);
            */
            Recommend::where('id', $data['id'])
                ->update([
                    'name' => $data['name']
                ]);

            make_error_log("chgRecommend.log","success");
            return 0;   //追加成功
        } catch (\Exception $e) {
            make_error_log("chgRecommend.log","failure");
            return -1;   //追加失敗
        }
    }
    //削除
    public static function delRecommend($data)//-------------------------------------------ﾌﾟﾚｲﾘｽﾄ削除機能　開発必須
    {
        make_error_log("delRecommend.log","---------start----------");
        try {
            if(!$data['pl_id'])  return 1;   //データ不足
            make_error_log("delRecommend.log","delete_pl_id=".$data['pl_id']);

            DB::table('recommenddetail')->where('pl_id', $data['pl_id'])->delete();
            DB::table('recommend')->where('id', $data['pl_id'])->delete();

            make_error_log("delRecommend.log","success");
            return 0;   //削除成功
        } catch (\Exception $e) {
            make_error_log("delRecommend.log","failure");
            return -1;   //削除失敗
        }
    }
    //プレイリスト収録曲削除
    public static function delRecommend_detail($data)
    {
        make_error_log("delRecommend_detail.log","-------start-------");
        try {
            if(!$data['pl_id'])     return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['detail_id'])    return ['id' => null, 'error_code' => 2];   //データ不足
            make_error_log("delMusic.log","delete_pl_id=".$data['pl_id']);
            // pl_detailデータ削除
            //$music = DB::table('musics')->where('id', $data['mus_id'])->first();
            DB::table('recommenddetail')->where('pl_id', $data['pl_id'])->where('id', $data['detail_id'])->delete();

            make_error_log("delRecommend_detail.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功

        } catch (\Exception $e) {
            make_error_log("delRecommend_detail.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗
        }
    }
    //プレイリスト収録曲追加
    public static function addRecommend_detail($data)
    {
        make_error_log("addRecommend_detail.log","-------start-------");
        //try {
            if(!$data['pl_id'])     return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['mus_id'])    return ['id' => null, 'error_code' => 2];   //データ不足
            make_error_log("addRecommend_detail.log","pl_id=".$data['pl_id']);
            make_error_log("addRecommend_detail.log","pl_detail_add_mus_id=".$data['mus_id']);
            // pl_detailデータ追加
            
        
            $pl_id = DB::table('recommenddetail')->insert([
                'pl_id' => $data['pl_id'],
                'mus_id' => $data['mus_id']
            ]);

            make_error_log("addRecommend_detail.log","success");
            return ['id' => null, 'error_code' => 0];   //更新成功

        //} catch (\Exception $e) {
            make_error_log("addRecommend_detail.log","failure");
            return ['id' => null, 'error_code' => -1];   //更新失敗
        //}
    }
}
