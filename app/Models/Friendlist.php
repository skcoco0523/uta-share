<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class Friendlist extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'friend_id', 'status'];     //一括代入の許可
    //0:承認待ち,1:承認済み,2:拒否

    //フレンドリスト取得
    public static function getFriendlist($user_id)
    {
        try {
            $list = self::where(["user_id"=>$user_id])->orwhere(["friend_id"=>$user_id])->orderby('status','desc')->get();
            //承認待ち　承認済み　拒否　未承認

            $friendlist = ['pending' => [],'accepted' => [],'declined' => [], 'request' => []];
            foreach ($list as $data) {
                $friend_id = ($data->user_id != $user_id) ? $data->user_id : $data->friend_id;
                $friend = User::where('id', $friend_id)->select('id','name')->first();
                $data->id   = $friend ? $friend->id : null;
                $data->name = $friend ? $friend->name : null;

                if ($data->status == 0) {
                    if ($data->user_id == $user_id) {
                        // 自身からのリクエストの承認待ち
                        $friendlist['pending'][] = $data; 
                    } else {
                        //拒否している場合を考慮する
                        $declined_flag=1;
                        foreach ($friendlist['declined'] as $declined) {
                            if($declined->friend_id == $data->user_id){
                                $declined_flag=0;
                            }
                        }
                        // 相手からのリクエストの未承認
                        if($declined_flag) $friendlist['request'][] = $data; 
                    }
                } elseif ($data->status == 1 && $data->user_id == $user_id) {
                    // 承認済みのフレンド
                    $friendlist['accepted'][] = $data;
                } elseif ($data->status == 2 && $data->user_id == $user_id) {
                    // 拒否中のフレンド
                    $friendlist['declined'][] = $data; 
                }
            }
            //dd($friendlist);
            return $friendlist;

        } catch (\Exception $e) {
            make_error_log("getFriendlist.log","failure");
            return "failure";
        }
    }
    //フレンド状態取得  none:データなし  request:承認待ち  pending:未承認  accepted:承認済  declined:拒否
    public static function getFriendStatus($user_id, $friend_id)
    {
        $friendships = Friendlist::where(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $user_id)->where('friend_id', $friend_id);
        })
        ->orWhere(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $friend_id)->where('friend_id', $user_id);
        })
        ->get();
        //dd($friendships);
        //該当なし
        if(count($friendships)==0) return 'none';

        //相手からの申請のみ　かつ　未承認
        if(count($friendships)==1 && $friendships[0]->user_id==$friend_id && $friendships[0]->status==0){
            return 'request'; 
        }
        //自身からの申請のみ　かつ　未承諾
        if(count($friendships)==1 && $friendships[0]->user_id==$user_id && $friendships[0]->status==0){
            return 'pending'; 
        }
        //フレンド申請　承認済み
        if(count($friendships)==2 && $friendships[0]->status==1 && $friendships[1]->status==1){
            return 'accepted'; 
        }
        //自身からの申請　相手から拒否
        foreach($friendships as $data){
            if($data->user_id == $friend_id && $data->status==2){
                return 'pending'; 
            }
        }
        //相手からの申請を拒否
        foreach($friendships as $data){
            if($data->user_id == $user_id && $data->status==2){
                return 'declined'; 
            }
        }
    }
    //フレンド検索
    public static function findByFriendCode($friendCode,$user_id)
    {
        $user = User::where('friend_code', $friendCode)->select('id', 'name')->first();
        if($user && ($user_id != $user->id)){
            //フレンド申請状態を確認
            $user->status = Friendlist::getFriendStatus(Auth::id(), $user->id);
            return $user;

        }else{
            return null;
        }
    }
    //フレンド申請
    public static function requestFriend($user_id, $friend_id)
    {
        try {
            // フレンドリクエストを作成
            //dd($user_id,$friend_id);
            $result = self::updateOrCreate(['user_id' => $user_id, 'friend_id' => $friend_id], ['status' => 0]);
            return true;

        } catch (\Exception $e) {
            make_error_log("requestFriend.log","failure");
            return false;
        }
    }
    //フレンド承認
    public static function acceptFriend($user_id, $friend_id)
    {
        try {
            // フレンドリクエストを承認 1:承認
            self::updateOrCreate(['user_id' => $friend_id, 'friend_id' => $user_id], ['status' => 1]);
            //自身のレコードも追加する
            self::updateOrCreate(['user_id' => $user_id, 'friend_id' => $friend_id], ['status' => 1]);
            return true;

        } catch (\Exception $e) {
            make_error_log("acceptFriend.log","failure");
            return false;
        }
    }
    //フレンド申請拒否
    public static function declineFriend($user_id, $friend_id)
    {
        try {
            // フレンドリクエストを拒否 2:拒否
            self::updateOrCreate(['user_id' => $user_id, 'friend_id' => $friend_id], ['status' => 2]);
            //現在フレンドの可能性があるため、相手の申請を申請中に戻す
            self::updateOrCreate(['user_id' => $friend_id, 'friend_id' => $user_id], ['status' => 0]);
            return true;

        } catch (\Exception $e) {
            make_error_log("declineFriend.log","failure");
            return false;
        }
    }
    public static function cancelFriend($user_id, $friend_id)
    {
        try {
            // フレンドリクエストをキャンセルする
            //self::where('user_id', $user_id)->where('friend_id', $friend_id)->where('status', 0)->delete();
            self::where('user_id', $user_id)->where('friend_id', $friend_id)->delete();
            // 相手の申請はあれば削除する
            self::where('user_id', $friend_id)->where('friend_id', $user_id)->delete();
            return true;

        } catch (\Exception $e) {
            make_error_log("declineFriend.log","failure");
            return false;
        }
    }

}
