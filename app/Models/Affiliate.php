<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Affiliate extends Model
{
    use HasFactory;

    
    //作成
    public static function createAffiliate($data)
    {
        make_error_log("createAffiliate.log","-------start-------");
        make_error_log("createAffiliate.log","aff_link=".$data['aff_link']);
        if(!$data['aff_link'])  return ['id' => null, 'error_code' => 1];   //データ不足

        //DB追加処理チェック
        try {
            preg_match('/href="([^"]+)"/', $data['aff_link'], $matches);
            $href = $matches[1];
    
            preg_match('/src="([^"]+)"/', $data['aff_link'], $matches);
            //ファイル拡張子までを取得
            preg_match('/(https?:\/\/[^\/\s]+\/[^\s]+)\.[a-zA-Z0-9]{2,4}/', $matches[1], $matches);
            $src = $matches[0];

            try {
                $affiliate = new Affiliate();
                $affiliate->href = $href;
                $affiliate->src = $src;
                $affiliate->save();
                // 保存されたレコードのIDを取得
                $aff_id = $affiliate->id;
                make_error_log("createAffiliate.log","success");
                return ['id' => $aff_id, 'error_code' => 0];   //追加成功

            } catch (\Exception $e) {
                make_error_log("createAffiliate.log","failure");
                return ['id' => null, 'error_code' => -1];   //追加失敗

            }
        } catch (\Exception $e) {
            make_error_log("createAffiliate.log","fraudulent data");
            return ['id' => null, 'error_code' => 2];   //不正データ

        }
    }
    //変更
    public static function chgAffiliate($data)
    {
        make_error_log("chgAffiliate.log","-------start-------");
        make_error_log("chgAffiliate.log","aff_link=".$data['aff_link']);
        if(!$data['aff_link'])  return ['id' => null, 'error_code' => 1];   //データ不足

        //DB追加処理チェック
        try {
            preg_match('/href="([^"]+)"/', $data['aff_link'], $matches);
            $new_href = $matches[1];
    
            preg_match('/src="([^"]+)"/', $data['aff_link'], $matches);
            //ファイル拡張子までを取得
            preg_match('/(https?:\/\/[^\/\s]+\/[^\s]+)\.[a-zA-Z0-9]{2,4}/', $matches[1], $matches);
            $new_src = $matches[0];
            make_error_log("chgAffiliate.log","chg_aff_id=".$data['aff_id']);

            try {
                /*  クエリビルダではupdated_atが自動更新されない
                DB::table('affiliates')->where('id', $data['aff_id'])
                ->update([
                    'href' => $new_href, 
                    'src' => $new_src, 
                ]);
                */
                Affiliate::where('id', $data['aff_id'])
                    ->update([
                        'href' => $new_href, 
                        'src' => $new_src, 
                    ]);
                
                // 保存されたレコードのIDを取得
                make_error_log("chgAffiliate.log","success");
                return ['id' => $data['aff_id'], 'error_code' => 0];   //更新成功

            } catch (\Exception $e) {
                make_error_log("chgAffiliate.log","failure");
                return ['id' => null, 'error_code' => -1];   //更新失敗

            }
        } catch (\Exception $e) {
            make_error_log("chgAffiliate.log","fraudulent data");
            return ['id' => null, 'error_code' => 2];   //不正データ

        }
    }
}
