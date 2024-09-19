<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Music;
use App\Models\Album;
use App\Models\Playlist;

class Recommend extends Model
{
    use HasFactory;
    protected $table = 'recommend';    //recommendsテーブルが指定されてしまうため、強制的に指定
    protected $fillable = ['name', 'user_id', 'category', 'disp_flag', 'sort_num'];
    //取得
    public static function getRecommend_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null,$user_flag=false)
    {
        $sql_cmd = DB::table('recommend');
        if($keyword){
            
            if (isset($keyword['search_recommend'])) 
                $sql_cmd = $sql_cmd->where('recommend.name', 'like', '%'. $keyword['search_recommend']. '%');

            if (isset($keyword['search_category'])) {
                $category = $keyword['search_category'];
                $sql_cmd = $sql_cmd->where('recommend.category',$keyword['search_category']);
                $sql_cmd = $sql_cmd->orderBy('sort_num', 'asc');
            }else{
                $sql_cmd = $sql_cmd->orderBy('created_at', 'desc');
            }
        }
        
        if($user_flag){
            $sql_cmd = $sql_cmd->where('disp_flag', 1);
            $sql_cmd = $sql_cmd->orderBy('sort_num', 'asc');
        }                      

        //dd($user_flag);
        // ページング・取得件数指定・全件で分岐
        if ($pageing){
            if ($disp_cnt === null) $disp_cnt=5;
            $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        }                       
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();
        
        $recommend = $sql_cmd;
        // 登録数、登録者名を取得
        foreach ($recommend as $key => $item) {
            $item->detail = DB::table('recommenddetail')->where('recom_id', $item->id)->get();
            $item->detail_cnt = count($item->detail);
            $user = DB::table('users')->where('id', $item->user_id)->select('name')->first();
            $item->user_name = $user->name ?? null;

            //user_flagありはユーザー用に加工
            if($user_flag) {
                //収録曲が０件の場合は除外
                if ($item->detail_cnt == 0) unset($recommend[$key]);

                if ($item->detail->isNotEmpty()) {
                    $add_list = [];

                    if($category==0){          //曲
                        foreach ($item->detail as $key => $detail_data) {
                            $add_list[$key] = Music::getMusic_detail($detail_data->detail_id);
                            if(!$add_list[$key]) unset($add_list[$key]);
                        }
                    }elseif($category==1){//アーティスト
                        foreach ($item->detail as $key => $detail_data) {
                            $add_list[$key] = Artist::getArtist_detail($detail_data->detail_id);
                            if(!$add_list[$key]) unset($add_list[$key]);
                        }
                    }elseif($category==2){//アルバム
                        foreach ($item->detail as $key => $detail_data) {
                            $add_list[$key] = Album::getAlbum_detail($detail_data->detail_id);
                            if(!$add_list[$key]) unset($add_list[$key]);
                        }
    
                    }elseif($category==3){//プレイリスト  
                        foreach ($item->detail as $key => $detail_data) {       
                            $add_list[$key] = Playlist::getPlaylist_detail($detail_data->detail_id);
                            if(!$add_list[$key]) {
                                unset($add_list[$key]);
                            }else{
                                //プレイリストの収録曲が０件の場合は除外
                                if (count($add_list[$key]->music) == 0) unset($add_list[$key]);
                            }
                        }
                    }
                    if (count($add_list) == 0){
                        unset($recommend[$key]);
                    }else{
                        // キーを再インデックス化して、連続した配列にする (歯抜けになるから詰める)
                        $add_list = array_values($add_list); 
                        $item->detail = $add_list;
                    } 
                }
            }
        }
        //dd($recommend);

        return $recommend; 
    }
    //おすすめ詳細情報を取得
    //public static function getRecommend_detail($recom_id)
    public static function getRecommend_detail($disp_cnt=null,$pageing=false,$page=1,$recom_id)
    {
        //おすすめ情報を取得
        $recommend = DB::table('recommend')->where('id', $recom_id)->first();
        //ユーザーが適当なidを引き渡した場合
        if(!$recommend) return null; 

        $sql_cmd = DB::table('recommenddetail')->where('recom_id', $recommend->id);
        // ページング・取得件数指定・全件で分岐
        if ($pageing){
            if ($disp_cnt === null) $disp_cnt=5;
            $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        }                       
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $detail_list = $sql_cmd;
        //if ($detail_list->isNotEmpty()) {
            $add_list = [];
            $item1 = "";
            $table = "";
            if($recommend->category==0){          //曲
                $item1   = "曲名";
                $table   = "mus";
                foreach ($detail_list as $key => $detail_data) {
                    $add_list[$key] = Music::getMusic_detail($detail_data->detail_id);
                    if(!$add_list[$key]) {
                        unset($detail_list[$key]);unset($add_list[$key]);
                    }
                }
            }elseif($recommend->category==1){//アーティスト
                $item1   = "アーティスト名";
                $table   = "art";
            }elseif($recommend->category==2){//アルバム
                $item1   = "アルバム名";
                $table   = "alb";
                foreach ($detail_list as $key => $detail_data) {
                    $add_list[$key] = Album::getAlbum_detail($detail_data->detail_id);
                    if(!$add_list[$key]) {
                        unset($detail_list[$key]);unset($add_list[$key]);
                    }
                }

            }elseif($recommend->category==3){//プレイリスト  
                $item1   = "プレイリスト名";
                $table   = "pl";
                foreach ($detail_list as $key => $detail_data) {       
                    $add_list[$key] = Playlist::getPlaylist_detail($detail_data->detail_id);
                    if(!$add_list[$key]) {
                        unset($detail_list[$key]);unset($add_list[$key]);
                    }else{
                        //プレイリストの収録曲が０件の場合は除外
                        if (count($add_list[$key]->music) == 0) {
                            unset($detail_list[$key]);unset($add_list[$key]);
                        }
                    }
                }
            }
            // キーを再インデックス化して、連続した配列にする (歯抜けになるから詰める)
            $detail = array_values($add_list); 
            
            if($pageing){
                $recommend2 = $detail_list;
                $recommend2->setCollection(collect($detail));
            }else{
                $recommend2 = $detail_list->values();
                $recommend2 = $recommend2->replace($detail);
                //$recommend2->collect($detail);
            }
        //}
        $recommend2->item1      = $item1;
        $recommend2->table      = $table; 
        $recommend2->id         = $recommend->id;
        $recommend2->name       = $recommend->name;
        $recommend2->category   = $recommend->category;
        //dd($recommend2);
        return $recommend2; 
    }
    //作成
    public static function createRecommend($data)
    {
        make_error_log("createRecommend.log","---------start----------");
        try {
            
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
        } catch (\Exception $e) {
            make_error_log("createRecommend.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
    }
    //変更
    public static function chgRecommend($data)
    {
        make_error_log("chgRecommend.log","---------start----------");
        try {
            if(!$data['id'])                return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['name'])              return ['id' => null, 'error_code' => 2];   //データ不足
            if($data['disp_flag']==null)    return ['id' => null, 'error_code' => 3];   //データ不足    カテゴリ:0がはじかれる
            //DB追加処理チェック
            make_error_log("chgRecommend.log","data=".print_r($data,1));
            /*  クエリビルダではupdated_atが自動更新されない
            DB::table('recommend')->where('id', $data['id'])
            ->update([
                'name' => $data['name']
            ]);
            */
            Recommend::where('id', $data['id'])->update(['name' => $data['name'],'disp_flag' => $data['disp_flag']]);

            make_error_log("chgRecommend.log","success");
            return ['id' => null, 'error_code' => 0];   //追加成功
        } catch (\Exception $e) {
            make_error_log("chgRecommend.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
    }
    //削除
    public static function delRecommend($data)//-------------------------------------------ﾌﾟﾚｲﾘｽﾄ削除機能　開発必須
    {
        make_error_log("delRecommend.log","---------start----------");
        try {
            if(!$data['id'])  return ['id' => null, 'error_code' => -1];   //データ不足
            make_error_log("delRecommend.log","delete_recom_id=".$data['id']);

            DB::table('recommenddetail')->where('recom_id', $data['id'])->delete();
            DB::table('recommend')->where('id', $data['id'])->delete();

            make_error_log("delRecommend.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功
        } catch (\Exception $e) {
            make_error_log("delRecommend.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗
        }
    }
    //おすすめ表示順変更
    public static function chgsortRecommend($data)
    {
        make_error_log("chgsortRecommend.log","---------start----------");
        try {
            if(!$data['fnc'])       return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['id'])        return ['id' => null, 'error_code' => 1];   //データ不足
            //if(!$data['category'])  return ['id' => null, 'error_code' => 1];   //データ不足  カテゴリ:0がはじかれる

            make_error_log("chgsortRecommend.log","sort_chg id=".$data['id']."-".$data['fnc']);
            
            $sort1=DB::table('recommend')->where('id', $data['id'])->value('sort_num');

            if($data['fnc']=="up"){
                $sort2=$sort1-1;
                //0以下は不可
                if($sort2<=0)           return ['id' => null, 'error_code' => -1];   //削除失敗
            }elseif($data['fnc']=="down"){
                $sort2=$sort1+1;
                $max_num=DB::table('recommend')->where('category', $data['category'])->max('sort_num');
                //連番にするため最大値以上との入れ替えを禁止
                if($sort2>$max_num)     return ['id' => null, 'error_code' => -1];   //削除失敗
            }
            //表示順を入れ替え
            $replace_id = DB::table('recommend')->where('category', $data['category'])->where('sort_num', $sort2)->value('id');

            Recommend::where('id', $data['id'])->update(['sort_num' => $sort2]);
            Recommend::where('id', $replace_id)->update(['sort_num' => $sort1]);
            make_error_log("chgsortRecommend.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功
        } catch (\Exception $e) {
            make_error_log("chgsortRecommend.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗
        }

    }
    //おすすめ　追加・削除
    public static function chgRecommend_detail($data)
    {
        make_error_log("chgRecommend_detail.log","-------start-------");
        //try {
            if(!$data['id'])     return ['id' => null, 'error_code' => 1];   //データ不足
            if(!$data['detail_id'])    return ['id' => null, 'error_code' => 2];   //データ不足
            
            make_error_log("chgRecommend_detail.log","delete_id=".$data['detail_id']);
            switch($data['fnc']){
                case "add":
                    $detail_id = DB::table('recommenddetail')->insert(['recom_id' => $data['id'],'detail_id' => $data['detail_id']]);
                    break;
                case "del":
                    DB::table('recommenddetail')->where('recom_id', $data['id'])->where('detail_id', $data['detail_id'])->delete();
                    break;
                default:
            }
            make_error_log("chgRecommend_detail.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功
        //} catch (\Exception $e) {
            make_error_log("chgRecommend_detail.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗
        //}
    }

}
