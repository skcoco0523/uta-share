<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Friendlist;
use App\Models\Favorite;
use App\Models\User;

class FriendlistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //フレンドリスト表示
    public function friendlist_show(Request $request)
    {
        //ユーザー検索
        $friend_code = $request->input('friend_code');
        $search_user = array();
        if($friend_code){
            $search_user[] = Friendlist::findByFriendCode($friend_code,Auth::id());
            if(!$search_user[0]){
                //ユーザー検索で一致しなかった場合は場合はリダイレクトする
                $message = ['message' => 'ユーザーが見つかりませんでした。',
                            'type' => 'error',
                            'sec' => '2000'];
                return redirect()->route('friendlist-show')->with($message);
            }
        }

        //0:承認待ち,1:承認済み,2:拒否
        $friendlist = Friendlist::getFriendlist(Auth::id());
        $msg = null;
        //dd($friendlist);
        if($friendlist){
            return view('friendlist_show', compact('friendlist', 'search_user', 'msg'));
        }else{
            return redirect()->route('home')->with('error', 'エラーが発生しました');
        }
    }    
    //フレンド情報表示
    public function friend_show(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['table']))             $input['table']=null;
        //選択しているタブのﾍﾟｰｼﾞｬｰのみページを指定する
        //$art_page = ($input['table'] == "art") ? $input['page'] :1;
        $mus_page = ($input['table'] == "mus") ? $input['page'] :1;
        $alb_page = ($input['table'] == "alb") ? $input['page'] :1;
        $pl_page  = ($input['table'] == "pl")  ? $input['page'] :1;

        //ユーザー検索
        //dd($input);
        $friend_id = $input['friend_id'] ;
        //公開フラグ確認
        $friend_profile = User::profile_get($friend_id);
        
        
        if(!isset($friend_profile) || $friend_profile->friend_status!="accepted"){
            // フレンドリストにリダイレクト\
            $message = ['message' => 'フレンド以外のデータは閲覧できません。', 'type' => 'error', 'sec' => '2000'];
            return redirect()->route('friendlist-show')->with($message);
        }

        $favorite_list = array();
        //フレンド承認済みで相手の公開制限無し
        if($friend_profile->release_flag!=1 && $friend_profile->friend_status=="accepted"){
            //$favorite_list["art"] = Favorite::getFavorite(10,true,$mus_page,$friend_id,"art");  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
            $favorite_list["mus"] = Favorite::getFavorite(10,true,$mus_page,$friend_id,"mus");  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
            //$favorite_list["alb"] = Favorite::getFavorite(10,true,$alb_page,$friend_id,"alb");  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
            //$favorite_list["pl"]  = Favorite::getFavorite(10,true,$pl_page ,$friend_id,"pl" );  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
        }else{
            $favorite_list["mus"] = null;
            //$favorite_list["alb"] = null;
            //$favorite_list["pl"]  = null;
        }

        $msg = null;
        if($favorite_list){
            return view('friend_show', compact('friend_profile', 'favorite_list', 'msg'));
        }else{
            return redirect()->route('home')->with('error', 'エラーが発生しました');
        }
    }
    //フレンド申請
    public function friend_request(Request $request)
    {
        $friend_id =  (int) $request->user_id;
        if((Auth::id() != $friend_id)){
            $status = Friendlist::requestFriend(Auth::id(), $friend_id);
        }
        // フレンドリストにリダイレクト
        $msg = $status === 'success' ? 'フレンド申請を送信しました。' : 'フレンド申請の送信に失敗しました。';
        $message = ['message' => $msg, 'type' => 'friend', 'sec' => '2000'];
        
        return redirect()->route('friendlist-show')->with($message);
    }
    //フレンド申請承諾
    public function friend_accept(Request $request)
    {
        $friend_id =  (int) $request->user_id;
        $status = Friendlist::acceptFriend(Auth::id(), $friend_id);

        // フレンドリストにリダイレクト
        $msg = $status === 'success' ? 'フレンド申請を承諾しました。' : 'フレンド申請の承諾に失敗しました。';
        $message = ['message' => $msg, 'type' => 'friend', 'sec' => '2000'];
        
        return redirect()->route('friendlist-show')->with($message);
    }
    //フレンド申請拒否
    public function friend_decline(Request $request)
    {
        $friend_id =  (int) $request->user_id;
        $status = Friendlist::declineFriend(Auth::id(), $friend_id);

        // 必要なデータを再取得してビューに渡す
        $friendlist = Friendlist::getFriendlist(Auth::id());
        $msg = $status === 'success' ? 'フレンド申請を拒否しました。' : 'フレンド申請の拒否に失敗しました。';
        $message = ['message' => $msg, 'type' => 'friend', 'sec' => '2000'];

        return redirect()->route('friendlist-show')->with($message);
    }
    //フレンド申請キャンセル
    public function friend_cancel(Request $request)
    {
        $friend_id =  (int) $request->user_id;
        $status = Friendlist::cancelFriend(Auth::id(), $friend_id);

        // フレンドリストにリダイレクト
        $msg = $status === 'success' ? 'フレンド申請を削除しました。' : 'フレンド申請の削除に失敗しました。';
        $message = ['message' => $msg, 'type' => 'friend', 'sec' => '2000'];

        return redirect()->route('friendlist-show')->with($message);
    }
}
