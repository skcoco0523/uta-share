<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Advertisement extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'aff_id', 'memo', 'type', 'sdate', 'days', 'age', 'priority', 'disp_flag'];
    protected $table = 'advertisement';

    //広告一覧取得
    public static function getAdv_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null)
    {
        $sql_cmd = DB::table('advertisement');
        if($keyword){
            //全検索
            if (isset($keyword['search_all'])) {
                $sql_cmd = $sql_cmd->Where('albums.name', 'like', '%'. $keyword['search_all']. '%');

            }else{
                //ユーザーによる検索
                if (isset($keyword['keyword'])) {
                    $sql_cmd = $sql_cmd->Where('albums.name', 'like', '%'. $keyword['keyword']. '%');
                    $sql_cmd = $sql_cmd->orWhere('artists.name', 'like', '%'. $keyword['keyword']. '%');
                    $sql_cmd = $sql_cmd->orWhere('artists.name2', 'like', '%'. $keyword['keyword']. '%');

                }
                //管理者による検索
                if (isset($keyword['search_album'])) 
                    $sql_cmd = $sql_cmd->where('albums.name', 'like', '%'. $keyword['search_album']. '%');
    
                if (isset($keyword['search_artist'])) {
                    $sql_cmd = $sql_cmd->where('artists.name', 'like', '%'. $keyword['search_artist']. '%');
                    $sql_cmd = $sql_cmd->orwhere('artists.name2', 'like', '%'. $keyword['search_artist']. '%');
                }

            }
        }

        // ページング・取得件数指定・全件で分岐
        if ($pageing){
            if ($disp_cnt === null) $disp_cnt=5;
            $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        }                       
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $advertisement = $sql_cmd;

        //画像情報を付与
        $advertisement=setAffData($advertisement);
        return $advertisement; 
    }
    
    //作成
    public static function createAdv($data)
    {
        make_error_log("createAdv.log","-------start-------");

        //データチェック
        $valid_types = ['top', 'banner', 'footer', 'in_contents', 'popup'];           //必要があれば追加する
        if(!isset($data['name']))       return ['id' => null, 'error_code' => 1];   //データ不足
        if(!isset($data['aff_id']))     return ['id' => null, 'error_code' => 2];   //データ不足
        if(!isset($data['type']))       return ['id' => null, 'error_code' => 3];   //データ不正
        if (!in_array($data['type'], $valid_types))  return ['id' => null, 'error_code' => 3];   //データ不正

        //いずれかが引き渡されたらチェック
        if(isset($data['month']) || isset($data['day']) || isset($data['days'])) {
            if(!isset($data['month']) || !isset($data['day']) || !isset($data['days']))
                return ['id' => null, 'error_code' => 4];   //データ不正(時間指定でどちらかがなし)
            if (!Advertisement::check_date($data['month'],$data['day'])) 
                return ['id' => null, 'error_code' => 4]; // データ不正（sdateがMM-DD形式ではない）
            if (is_numeric($data['days']) && $data['days'] > 99) 
                return ['id' => null, 'error_code' => 4]; // データ不正（daysが指定されていない）

            $data['sdate'] = str_pad($data['month'], 2, '0', STR_PAD_LEFT) . "-" . str_pad($data['day'], 2, '0', STR_PAD_LEFT);
        }


        //DB追加処理チェック
        try {
            // DBに追加
            $Advertisement = self::create($data);
            $adv_id = $Advertisement->id;
            make_error_log("createAdv.log","success");
            return ['id' => $adv_id, 'error_code' => 0];   //追加成功

        } catch (\Exception $e) {
            make_error_log("createAdv.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加成功
        }
    }

    public static function check_date($month, $day) {
        // 月の範囲チェック
        if ($month < 1 || $month > 12) 
            return false;
        // 各月の日数チェック
        $daysInMonth = [ 1 => 31, 2 => 29, 3 => 31, 4 => 30, 5 => 31, 6 => 30, 7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31, ];
        // 日の範囲チェック
        if ($day < 1 || $day > $daysInMonth[$month]) 
            return false;

        return true;
    }



}
