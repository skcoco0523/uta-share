<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Music;

class Ranking extends Model
{
    use HasFactory;
    
    //ランキング取得
    public static function getFavoriteRanking($disp_cnt=null,$pageing=false,$page=1,$table=null) {

        if($table){
            if    ($table=="mus") $sql_cmd = DB::table('favorite_mus');
            elseif($table=="alb") $sql_cmd = DB::table('favorite_mus');
            elseif($table=="pl")  $sql_cmd = DB::table('favorite_pl');
            //ユーザーがtableを修正して引き渡した場合の対応
            else return null;

            $sql_cmd = $sql_cmd->select('fav_id', DB::raw('COUNT(*) as count'));
            $sql_cmd = $sql_cmd->groupBy('fav_id')->orderByDesc('count');

            // ページング・取得件数指定・全件で分岐
            if ($pageing){
                if ($disp_cnt === null) $disp_cnt=5;
                $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
            }                       
            elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
            else                                $sql_cmd = $sql_cmd->get();       
            $favorite = $sql_cmd;
            
            if($table=="mus") {
                foreach($favorite as $fav){
                    $ranking[] = Music::getMusic_detail($fav->fav_id);
                }
            }
            if($table=="alb") {
            }
            if($table=="pl") {
            }

            if($pageing){
                $favorite->setCollection(collect($ranking));
            }else{
                $favorite = $favorite->replace($ranking);
                //dd($recommend2,$detail);
            }
            //dd($favorite);
        }
        return $favorite;
    }
}

