<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

//管理者
use App\Http\Middleware\AdminMiddleware;

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminMusicController;
use App\Http\Controllers\Admin\AdminAlbumController;
use App\Http\Controllers\Admin\AdminArtistController;
use App\Http\Controllers\Admin\AdminPlaylistController;
use App\Http\Controllers\Admin\AdminRecommendController;
use App\Http\Controllers\Admin\AdminCategoryController;

//ユーザー
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\UserDeviceController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FriendlistController;
use App\Http\Controllers\RecommendController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\CategoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();

// 未認証ユーザー向け
Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');


//ユーザー------------------------------------------------------------------------
//PWAアプリインストール時の　デバイス情報登録
Route::post('devices/check', [UserDeviceController::class, 'device_update'])->name('devices-check');

//パスワードリセット
Route::post('password/reset/mailsend', [UserController::class, 'password_reset_mailsend'])->name('password-reset');

//アーティスト詳細
Route::get('artist', [ArtistController::class, 'artist_show'])->name('artist-show');

//曲詳細
Route::get('music', [MusicController::class, 'music_show'])->name('music-show');

//アルバム詳細
Route::get('album', [AlbumController::class, 'album_show'])->name('album-show');

//プレイリスト詳細
Route::get('playlist', [PlaylistController::class, 'playlist_show'])->name('playlist-show');



//おすすめ一覧
Route::get('recommend-list', [RecommendController::class, 'recommend_list_show'])->name('recommend-list-show');
//おすすめ詳細
Route::get('recommend', [RecommendController::class, 'recommend_show'])->name('recommend-show');

//ランキング　おすすめ
Route::get('favorite-ranking', [RankingController::class, 'favorite_ranking'])->name('favorite-ranking');




//検索
Route::get('search', [SearchController::class, 'search_show'])->name('search-show');
//検索補足
Route::get('suggestions', [SearchController::class, 'search_suggestions'])->name('search-suggestions');
//検索結果
Route::get('search-list', [SearchController::class, 'search_list_show'])->name('search-list-show');



// 認証済みユーザー向け
Route::middleware(['auth'])->group(function () {
    /*
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    */
    
    // 管理者権限があるユーザーのみ
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::group(['prefix' => 'admin'], function(){

            Route::get('home', [AdminHomeController::class, 'home'])->name('admin-home');

        //------------------------------------------------------------------------
            //曲登録
            Route::get('music/reg', [AdminMusicController::class, 'music_regist'])->name('admin-music-reg');
            Route::post('music/reg', [AdminMusicController::class, 'music_reg'])->name('admin-music-reg');

            //曲検索
            Route::get('music/search', [AdminMusicController::class, 'music_search'])->name('admin-music-search');
            //曲検索>変更
            Route::post('music/search/chg', [AdminMusicController::class, 'music_chg'])->name('admin-music-chg');
            //曲検索>削除
            Route::post('music/search/del', [AdminMusicController::class, 'music_del'])->name('admin-music-del');
        //------------------------------------------------------------------------
            //カテゴリー設定
            Route::get('music/category/setting', [AdminCategoryController::class, 'custom_category_setting'])->name('admin-custom-category-setting');
            Route::post('music/category/reg', [AdminCategoryController::class, 'custom_category_reg'])->name('admin-custom-category-reg');
            //カテゴリー設定>変更
            Route::post('music/category/chg', [AdminCategoryController::class, 'custom_category_chg'])->name('admin-custom-category-change');
            //カテゴリー設定>並び順変更
            Route::post('music/category/chgsort', [AdminCategoryController::class, 'custom_category_chg_sort'])->name('admin-custom-category-sort-chg');
        //------------------------------------------------------------------------
            //アルバム登録
            Route::get('album/reg', [AdminAlbumController::class, 'album_regist'])->name('admin-album-reg');
            Route::post('album/reg', [AdminAlbumController::class, 'album_reg'])->name('admin-album-reg');
            
            //アルバム検索
            Route::get('album/search', [AdminAlbumController::class, 'album_search'])->name('admin-album-search');
            //アルバム検索>変更
            Route::post('album/search/chg', [AdminAlbumController::class, 'album_chg'])->name('admin-album-chg');
            //アルバム検索>削除
            Route::post('album/search/del', [AdminAlbumController::class, 'album_del'])->name('admin-album-del');
            

            Route::get('album/chgdetail', [AdminAlbumController::class, 'album_chg_detail'])->name('admin-album-chgdetail');
            Route::post('album/chgdetail', [AdminAlbumController::class, 'album_chg_detail_fnc'])->name('admin-album-chgdetail-fnc');

    
        //------------------------------------------------------------------------
            //アーティスト登録
            Route::get('artist/reg', [AdminArtistController::class, 'artist_regist'])->name('admin-artist-reg');
            Route::post('artist/reg', [AdminArtistController::class, 'artist_reg'])->name('admin-artist-reg');
            
            //アーティスト検索
            Route::get('artist/search', [AdminArtistController::class, 'artist_search'])->name('admin-artist-search');
            //アーティスト検索>変更
            Route::post('artist/search/chg', [AdminArtistController::class, 'artist_chg'])->name('admin-artist-chg');
            //アーティスト検索>削除
            Route::post('artist/search/del', [AdminArtistController::class, 'artist_del'])->name('admin-artist-del');  
    
        //------------------------------------------------------------------------
            //プレイリスト登録
            Route::get('playlist/reg', [AdminPlaylistController::class, 'playlist_regist'])->name('admin-playlist-reg');
            Route::post('playlist/reg', [AdminPlaylistController::class, 'playlist_reg'])->name('admin-playlist-reg');

            //プレイリスト検索
            Route::get('playlist/search', [AdminPlaylistController::class, 'playlist_search'])->name('admin-playlist-search');
            //プレイリスト検索>変更
            Route::post('playlist/chg', [AdminPlaylistController::class, 'playlist_chg'])->name('admin-playlist-chg');
            //プレイリスト検索>削除
            Route::post('playlist/search/del', [AdminPlaylistController::class, 'playlist_del'])->name('admin-playlist-del');
            //プレイリスト検索>収録曲修正
            Route::get('playlist/chgdetail', [AdminPlaylistController::class, 'playlist_chg_detail'])->name('admin-playlist-chgdetail');
            Route::post('playlist/chgdetail', [AdminPlaylistController::class, 'playlist_chg_detail_fnc'])->name('admin-playlist-chgdetail-fnc');
            //プレイリスト検索>収録曲修正>曲検索
            Route::get('playlist/chgdetail/search-detail', [AdminPlaylistController::class, 'playlist_detail_search'])->name('admin-playlist-detail-search');
        
        //------------------------------------------------------------------------
            //おすすめ登録
            Route::get('recommend/reg', [AdminRecommendController::class, 'recommend_regist'])->name('admin-recommend-reg');
            Route::post('recommend/reg', [AdminRecommendController::class, 'recommend_reg'])->name('admin-recommend-reg');

            //おすすめ検索
            Route::get('recommend/search', [AdminRecommendController::class, 'recommend_search'])->name('admin-recommend-search');
            //おすすめ検索>変更
            Route::post('recommend/chg', [AdminRecommendController::class, 'recommend_chg'])->name('admin-recommend-chg');
            //おすすめ検索>削除
            Route::post('recommend/search/del', [AdminRecommendController::class, 'recommend_del'])->name('admin-recommend-del');
            //おすすめ検索>詳細変更
            Route::get('recommend/chgdetail', [AdminRecommendController::class, 'recommend_chg_detail'])->name('admin-recommend-chgdetail');

            //おすすめ検索>カテゴリ指定検索>並び変更
            Route::post('recommend/chgsort', [AdminRecommendController::class, 'recommend_chg_sort'])->name('admin-recommend-sort-chg');

            //おすすめ検索>詳細変更>登録データ修正
            Route::get('recommend/chgdetail/search-detail', [AdminRecommendController::class, 'recommend_detail_search'])->name('admin-recommend-detail-search');
            Route::post('recommend/chgdetail', [AdminRecommendController::class, 'recommend_chg_detail_fnc'])->name('admin-recommend-chgdetail-fnc');
            
        });
    });

    //ユーザー------------------------------------------------------------------------
    //プロフィール
    Route::get('profile/show', [UserController::class, 'profile_show'])->name('profile-show');
    Route::post('profile/change', [UserController::class, 'profile_change'])->name('profile-change');

    //お気に入り表示
    Route::get('favorite', [FavoriteController::class, 'favorite_show'])->name('favorite-show');
    //お気に入り変更
    Route::post('favorite/chg', [FavoriteController::class, 'favorite_chg'])->name('favorite-chg');
    //カテゴリ登録変更
    Route::post('custom/category/chg', [CategoryController::class, 'custom_category_chg'])->name('custom-category-chg');
    //フレンドリスト表示
    Route::get('friendlist', [FriendlistController::class, 'friendlist_show'])->name('friendlist-show');
    //フレンド申請
    Route::post('friend/request', [FriendlistController::class, 'friend_request'])->name('friend-request');
    //フレンド承認
    Route::post('friend/accept', [FriendlistController::class, 'friend_accept'])->name('friend-accept');
    //フレンド申請拒否
    Route::post('friend/decline', [FriendlistController::class, 'friend_decline'])->name('friend-decline');
    //フレンド申請キャンセル
    Route::post('friend/cancel', [FriendlistController::class, 'friend_cancel'])->name('friend-cancel');
    //フレンド情報表示
    Route::get('friend/show', [FriendlistController::class, 'friend_show'])->name('friend-show');
    
    //検索履歴削除
    Route::post('history/delete', [SearchController::class, 'del_search_history'])->name('history-delete');
    
});
