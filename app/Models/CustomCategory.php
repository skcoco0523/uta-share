<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Music;

class CustomCategory extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'music_id', 'category_bit'];     //一括代入の許可

    //custom_categoryに該当するデータがあれば、category_bitを返す
    public static function chkBitNum($user_id, $music_id, $bit_num)
    {
        // 現在のcategory_bitを取得して返す
        return DB::table('custom_categories')
                ->where('user_id', $user_id)
                ->where('music_id', $music_id)
                ->whereRaw("category_bit & ? > 0", [$bit_num])
                ->value('category_bit');
    }

    //ユーザーのカテゴリ登録状態を確認
    public static function chkCustomCategory($user_id=null, $music_id=null, $bit_num=null)//ユーザーID、music_id、カテゴリ(ビット番号)
    {
        try {

            $sql_cmd = DB::table('custom_categories_define');
            $sql_cmd = $sql_cmd->where('disp_flag','1');
            $sql_cmd = $sql_cmd->orderBy('sort_num', 'asc')->get();
            $custom_category = $sql_cmd;
            
            //ユーザー、music_id指定　　ユーザーページで、登録状態を取得
            if($user_id && $music_id){
                foreach($custom_category as $category){
                    $before_bit_num = CustomCategory::chkBitNum($user_id, $music_id, $category->bit_num);
                    $category->status = $before_bit_num ? true : false;
                }

            //user_idのみ指定   指定曲の登録状態を取得
            }elseif($user_id && !$music_id){
                foreach($custom_category as $category){
                }
                $user = DB::table('custom_categories')->where('user_id', $user_id)->get();
                //ユーザー情報も合わせて返す？

            //music_id指定      指定曲の登録状態を取得
            }elseif(!$user_id && $music_id){
                foreach($custom_category as $category){
                }
                $user = DB::table('custom_categories')->where('music_id', $music_id)->get();
                //ユーザー情報も合わせて返す？

            //指定bit番号の      指定カテゴリの登録状態を取得
            }elseif(!$user_id && $bit_num){
                foreach($custom_category as $category){
                }
                $user = DB::table('custom_categories')->whereRaw("category_bit & ? > 0", [$bit_num])->get();
                //ユーザー情報も合わせて返す？

            }else{

            }
            //dd($custom_category);


            return $custom_category;

        } catch (\Exception $e) {
            make_error_log("chkFavorite.log","failure");
            return null;
        }
    }
    //変更
    public static function chgCustomCategory($user_id, $music_id, $bit_num, $type)//type:add=追加、del=削除
    {
        make_error_log("chgUserCustomCategory.log","-------start-------");
        //make_error_log("favorite_chg","detail_id=".print_r($detail_id,1));
        try {
            make_error_log("chgUserCustomCategory.log","user_id=".$user_id." music_id=".$music_id." bit_num=".$bit_num." type=".$type);

            $data = ["user_id"=>$user_id, "music_id"=>$music_id];

            $before_bit_num = CustomCategory::chkBitNum($user_id, $music_id, $bit_num);
            make_error_log("chgUserCustomCategory.log","before_bit_num=".$before_bit_num);
            if($type=="add"){
                if($before_bit_num & $bit_num > 0){
                    //追加済み
                    make_error_log("chgUserCustomCategory.log","already added");
                    return "すでに登録済みです。";
                }else{
                    //追加処理
                    make_error_log("chgUserCustomCategory.log","to add");
                    
                    $result = CustomCategory::updateOrCreate(
                        ['user_id' => $user_id, 'music_id' => $music_id],
                        ['category_bit'=> DB::raw("category_bit | $bit_num")]
                    );
                    //create($validatedData);
                    return "add";
                }
            }elseif($type=="del"){
                if(($before_bit_num & $bit_num) > 0){
                    make_error_log("chgUserCustomCategory.log","cheke=".($before_bit_num & $bit_num));
                    //削除
                    $after_bit_num = $before_bit_num & (~(int)$bit_num);
                    if($after_bit_num){
                        // ビットが残っている場合は更新
                        CustomCategory::where('user_id', $user_id)
                                        ->where('music_id', $music_id)
                                        ->update(['category_bit' => $after_bit_num]);
                    }else{
                        CustomCategory::where('user_id', $user_id)
                                        ->where('music_id', $music_id)
                                        ->delete();
                    }
                    //self::where($data)->delete();
                    return "del";
                }else{
                    //削除対象無し
                    make_error_log("chgUserCustomCategory.log","not match");
                    return "カテゴリ解除済みです。";
                }
            }else{
                return "更新に失敗しました。";   //更新失敗
            }

        } catch (\Exception $e) {
            make_error_log("chgUserCustomCategory.log","failure");
            return "更新に失敗しました。";   //更新失敗
        }
    }
}
