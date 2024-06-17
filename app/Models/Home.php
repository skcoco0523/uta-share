<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Music;
use App\Models\Ranking;
class Home extends Model
{
    use HasFactory;

    //プレイリスト取得
    public function getUserPlaylist($user_id, $disp_cnt=10){
        $playlist = DB::table('playlist')
        ->select('playlist.id', 'playlist.name', 
                DB::raw('GROUP_CONCAT(COALESCE(musics.aff_id, albums.aff_id)) AS aff_id'), 
                DB::raw('GROUP_CONCAT(playlistdetail.id) AS ids'),
                DB::raw('COUNT(playlistdetail.id) as cnt'))
        ->leftJoin('playlistdetail', 'playlist.id', '=', 'playlistdetail.id')
        ->leftJoin('musics', 'musics.id', '=', 'playlistdetail.id')
        ->leftJoin('albums', 'albums.id', '=', 'musics.alb_id')
        ->where([
            ['playlist.user_id', '=', $user_id],
            ['playlist.admin_flag', '=', 0],
        ])
        ->groupBy('playlist.id', 'playlist.name')
        ->limit($disp_cnt)
        ->get();

        // Check if the result includes ids property
        if (isset($playlist[0]) && property_exists($playlist[0], 'ids')) {
            // aff_no を配列に変換
            foreach ($playlist as $item) {
                $item->aff_no = explode(',', $item->aff_no);
                $item->ids = explode(',', $item->ids);
            }
        }
        //画像情報を付与
        $playlist=setAffData($playlist);
        return $playlist;
        /*
        ->join('contacts', 'users.id', '=', 'contacts.user_id')
        ->join('orders', 'users.id', '=', 'orders.user_id')
        ->select('users.*', 'contacts.phone', 'orders.price')
*/
    }


    //
}