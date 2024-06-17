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
    public static function getRanking($rank_type, $category=null, $disp_cnt=10) {
        //ランキングタイプごとに分岐
        switch($rank_type){
            case "7week_acc"://直近7週間のアクセス件数ランキング
                $sevenDaysAgo = now()->subDays(7);
                $ranking = DB::table('musics')
                ->select('artists.name', 'musics.name', DB::raw('COALESCE(musics.aff_id, albums.aff_id) AS aff_id'))
                ->leftJoin('artists', 'artists.id', '=', 'musics.art_id')
                ->leftJoin('albums', 'albums.art_id', '=', 'artists.id')
                ->groupBy('musics.name', 'artists.name', 'musics.aff_id', 'albums.aff_id')
                ->limit($disp_cnt)
                ->get();
                break;
            
            case "favorite"://お気に入り曲ランキング
                if(is_numeric($category)){
                    $favorite = DB::table('favorite')
                    ->select('detail_id', DB::raw('COUNT(*) as count'))
                    ->where('category', $category)
                    ->groupBy('detail_id')
                    ->orderByDesc('count')
                    ->limit($disp_cnt)
                    ->get();
                    //dd($fav);
                    foreach($favorite as $fav){
                        $ranking[] = Music::getMusic_detail($fav->detail_id);
                    }
                }
                break;
        
            case "want_to_sing"://歌いたい曲ランキング
                break;
            default:
                /*
                $ranking = DB::table('musics')
                ->select('musics.id', 'musics.name', 'albums.name AS alb_name',
                        DB::raw('GROUP_CONCAT(COALESCE(musics.aff_id, albums.aff_id)) AS aff_id'))
                ->leftJoin('albums', 'albums.id', '=', 'musics.alb_id')
                ->groupBy('musics.id', 'musics.name', 'albums.name')
                ->limit($disp_cnt)
                ->get();
                */
        }

        if(isset($ranking)){
            //画像情報を付与
            return $ranking=setAffData($ranking);
        }else{
            return null;
        }
    }
}
