<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \App\Models\Favorite;

class Favorite extends Model
{
    use HasFactory;
    protected $table = 'favorite';    //musicテーブルが指定されてしまうため、強制的に指定
    protected $fillable = ['user_id', 'category', 'detail_id'];     //一括代入の許可


    //お気に入りチェック
    public static function chkFavorite($user_id, $category, $detail_id)
    {
        try {
            $fav_chk = ["user_id"=>$user_id, "category"=>$category, "detail_id"=>$detail_id];
            $favorite = DB::table('favorite')->where($fav_chk)->first();

            return ($favorite) ? 1 : 0;
        } catch (\Exception $e) {
            make_error_log("chkFavorite.log","failure");
            return "failure";
        }
    }

    //変更
    public static function chgFavorite($user_id, $category, $detail_id, $type)//type:add=追加、del=削除
    {
        make_error_log("chgFavorite.log","-------start-------");
        //make_error_log("favorite_chg","detail_id=".print_r($detail_id,1));
        make_error_log("chgFavorite","user_id=".$user_id." category=".$category." detail_id=".$detail_id." type=".$type);
        try {
            $fav_chk = Favorite::chkFavorite($user_id, $category, $detail_id);
            $data = ["user_id"=>$user_id, "category"=>$category, "detail_id"=>$detail_id];

            if($type=="add"){
                if($fav_chk){
                    //追加済み
                    make_error_log("chgFavorite.log","already added");
                    return "すでに登録済みです。";
                }else{
                    //追加処理
                    make_error_log("chgFavorite.log","to add");
                    self::create($data);
                    //create($validatedData);
                    return "add";
                }
            }elseif($type=="del"){
                if($fav_chk){
                    //削除
                    make_error_log("chgFavorite.log","delete");
                    self::where($data)->delete();
                    return "del";
                }else{
                    //削除対象無し
                    make_error_log("chgFavorite.log","not match");
                    return "お気に入り解除済みです。";
                }
            }

        } catch (\Exception $e) {
            make_error_log("chgFavorite.log","failure");
            return "更新に失敗しました。";   //更新失敗
        }
    }
}
