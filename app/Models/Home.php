<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Music; // Musicモデルをインポート
class Home extends Model
{
    use HasFactory;

    //ランキング取得
    public function getRankingData($rank_type, $disp_cnt=10) {
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
            
            case "fav_cnt"://お気に入り曲ランキング
                break;
        
            case "want_to_sing"://歌いたい曲ランキング
                break;
            default:
                $ranking = DB::table('musics')
                ->select('musics.id', 'musics.name', 'albums.name AS alb_name',
                        DB::raw('GROUP_CONCAT(COALESCE(musics.aff_id, albums.aff_id)) AS aff_id'))
                ->leftJoin('albums', 'albums.id', '=', 'musics.alb_id')
                ->groupBy('musics.id', 'musics.name', 'albums.name')
                ->limit($disp_cnt)
                ->get();
        }

        //画像情報を付与
        $ranking=setAffData($ranking);
        //{{ asset('storage/icon/friend.png') }}
        return $ranking;
    }

    //プレイリスト取得
    public function getPlaylistData($user_id, $disp_cnt=10){
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

    //おすすめ取得　ホーム用
    public function getRecommendList($category,$disp_cnt=10){
        $recommend = null;
        //categoryに分岐
        $sql_cmd = DB::table('recommend');
        $sql_cmd = $sql_cmd->where('category', $category)->where('disp_flag', 1);
        $sql_cmd = $sql_cmd->limit($disp_cnt)->orderBy('sort_num', 'asc')->get();

        $recommend = $sql_cmd;
        if ($recommend->isNotEmpty()) {
            // $recommend にデータがある場合の処理
            foreach ($recommend as $key => $item) {
                $sql_cmd = DB::table('recommenddetail as dtl');
                $sql_cmd = $sql_cmd->where('dtl.recom_id', '=', $item->id);
                $detail_list = $sql_cmd->get();

                $item->count = count($detail_list);
                //収録曲が０件の場合は除外
                if ($item->count == 0)  unset($recommend[$key]);
                //dd($detail_list);

                //if (is_null($detail->detail_id)) continue;
                switch($category){
                    case 0://曲
                        $music = new Music();
                        foreach ($detail_list as $key => $detail) {
                            $detail_list[$key] = $music->getMusic_detail($detail->detail_id);
                        }
                        $item->detail = $detail_list;
                        break;
                    
                    case 1://アーティスト
                        //現在はアーティストに画像情報がない
                        //$artist = new Artist();
                        //foreach ($detail_list as $key => $detail) {
                            //$detail_list[$key] = $artist->getMusic_detail($detail->detail_id);
                        //}
                        //$item->detail = $detail_list;
                        break;
                    case 2://アルバム
                        $album = new Album();
                        foreach ($detail_list as $key => $detail) {
                            $detail_list[$key] = $album->getAlbum_detail($detail->detail_id);
                        }
                        $item->detail = $detail_list;
                        break;
                    case 3://プレイリスト
                        $playlist = new Playlist();
                        foreach ($detail_list as $key => $detail) {
                            $detail_list[$key] = $playlist->getPlaylist_detail($detail->detail_id);
                        }
                        $item->detail = $detail_list;
                        break;
                    default:
                        $recommend = DB::table('musics')
                        ->select('musics.id', 'musics.name', 'albums.name AS alb_name',
                                DB::raw('GROUP_CONCAT(COALESCE(musics.aff_id, albums.aff_id)) AS aff_id'))
                        ->leftJoin('albums', 'albums.id', '=', 'musics.alb_id')
                        ->groupBy('musics.id', 'musics.name', 'albums.name')
                        ->limit($disp_cnt)
                        ->get();
                    
                }
                //トップ用のリンクを取得
                //$recommend[0]->href = $recommend[0]->detail[0]->href;
                //$recommend[0]->src = $recommend[0]->detail[0]->src;
                //dd($recommend);
            }
        }else{

        }
        return $recommend;
    }

    //
}