<?php
// app/Helpers/CustomFunctions.php

//エラーログ
if (! function_exists('make_error_log')) {
    /**
     * Custom function to make error log.
     *
     * @param  string  $file_name
     * @param  mixed  $prm
     * @return void
     */
    function make_error_log($file_name, $prm)
    {
        $file_name = config('error.log.path', 'errorlog01') . "/" . $file_name;
        config(['logging.channels.single.path' => storage_path($file_name)]);
        \Illuminate\Support\Facades\Log::channel('single')->debug($prm);
    }
}


//画像情報付与 aff_idに対して、画像格納を合わせて返す 20240122 kanno
if (! function_exists('setAffData')) {
    function setAffData($obj) {
        try{
            //id プロパティが存在する場合の処理
            if (!empty($obj->id)){
                $aff_data = DB::table('affiliates')
                ->select('affiliates.id','affiliates.href','affiliates.src')
                ->where('id', '=', $obj->aff_id)
                ->first();
                if ($aff_data) {
                    $obj->href = $aff_data->href;
                    $obj->src = $aff_data->src;
                } else {
                    // $aff_data が null の場合
                    $obj->href = NULL;
                    $obj->src = asset('img/pic/no_image.png');
                }
            }else{
                foreach ($obj as $val) {
                    //コレクション内のaff_idが配列
                    if(is_array($val->aff_id)){
                        $aff_array = array(); 
                        
                        foreach ($val->aff_id as $aff_id) {
                            
                            $aff_data = DB::table('affiliates')
                            ->select('affiliates.id','affiliates.href','affiliates.src')
                            ->where('id', '=', $aff_id)
                            ->first();
                            if ($aff_data) {
                                $href_array[] = $aff_data->href;
                                $src_array[] = $aff_data->src;
                            } else {
                                // $aff_data が null の場合
                                $href_array[] = NULL;
                                $src_array[] = asset('img/pic/no_image.png');
                            }
                            /*
                            $href_array[] = $href;
                            $src_array[] = $src;
                            if($aff_data->isEmpty()){
                                //$aff_array[] = "pic/no_image.png";
                                $aff_array[] = "pic/no_image.png";
                            }
                            */
                        }
                        $val->href = $href_array;
                        $val->src = $src_array;
                    //コレクション内のaff_idがオブジェクト
                    }else{
                        $aff_data = DB::table('affiliates')
                        ->select('affiliates.id','affiliates.href','affiliates.src')
                        ->where('id', '=', $val->aff_id)
                        ->first();
                        if ($aff_data) {
                            $href = $aff_data->href;
                            $src = $aff_data->src;
                        } else {
                            // $aff_data が null の場合
                            $href = null;
                            $src = asset('img/pic/no_image.png');
                        }
                        $val->href = $href;
                        $val->src = $src;
                    }
                }
            }
            return $obj;

        } catch (\Exception $e) {
            make_error_log("setAffData.log","failure");
            make_error_log("setAffData.log","obj=".$obj);

        }
    }
}