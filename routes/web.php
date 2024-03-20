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

// 未認証ユーザー向け
Route::get('/', [HomeController::class, 'index']);

Auth::routes();

// 認証済みユーザー向け
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    
    // 管理者権限があるユーザーのみ
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::group(['prefix' => 'admin'], function(){

            Route::get('home', [AdminHomeController::class, 'home'])->name('admin-home');

            //管理者　音楽------------------------------------------------------------------------
            Route::get('music/reg', [AdminMusicController::class, 'music_regist'])->name('admin-music-reg');
            Route::post('music/reg', [AdminMusicController::class, 'music_reg'])->name('admin-music-reg');

            Route::get('music/search', [AdminMusicController::class, 'music_search'])->name('admin-music-search');
            Route::post('music/search/del', [AdminMusicController::class, 'music_del'])->name('admin-music-del');
            Route::post('music/search/chg', [AdminMusicController::class, 'music_chg'])->name('admin-music-chg');
        
            //管理者　アルバム------------------------------------------------------------------------
            Route::get('album/reg', [AdminAlbumController::class, 'album_regist'])->name('admin-album-reg');
            Route::post('album/reg', [AdminAlbumController::class, 'album_reg'])->name('admin-album-reg');

            Route::get('album/chgdetail', [AdminAlbumController::class, 'album_chg_detail'])->name('admin-album-chgdetail');
            Route::post('album/chgdetail', [AdminAlbumController::class, 'album_chg_detail_fnc'])->name('admin-album-chgdetail_fnc');

            Route::get('album/search', [AdminAlbumController::class, 'album_search'])->name('admin-album-search');
            Route::post('album/search/del', [AdminAlbumController::class, 'album_del'])->name('admin-album-del');
            Route::post('album/search/chg', [AdminAlbumController::class, 'album_chg'])->name('admin-album-chg');
        
            //管理者　アーティスト------------------------------------------------------------------------
            Route::get('artist/reg', [AdminArtistController::class, 'artist_regist'])->name('admin-artist-reg');
            Route::post('artist/reg', [AdminArtistController::class, 'artist_reg'])->name('admin-artist-reg');
        
            Route::get('artist/search', [AdminArtistController::class, 'artist_search'])->name('admin-artist-search');
            Route::post('artist/search/del', [AdminArtistController::class, 'artist_del'])->name('admin-artist-del');
            Route::post('artist/search/chg', [AdminArtistController::class, 'artist_chg'])->name('admin-artist-chg');
            
        
            //管理者　プレイリスト------------------------------------------------------------------------
            Route::get('playlist/reg', [AdminPlaylistController::class, 'playlist_regist'])->name('admin-playlist-reg');
            Route::post('playlist/reg', [AdminPlaylistController::class, 'playlist_reg'])->name('admin-playlist-reg');
            
            Route::get('playlist/chgdetail', [AdminPlaylistController::class, 'playlist_chg_detail'])->name('admin-playlist-chgdetail');
            Route::get('playlist/chgdetail/getmusic', [AdminPlaylistController::class, 'playlist_music_search'])->name('admin-playlist-music-search');

            
            Route::post('playlist/chgdetail', [AdminPlaylistController::class, 'playlist_chg_detail_fnc'])->name('admin-playlist-chgdetail_fnc');

            Route::get('playlist/search', [AdminPlaylistController::class, 'playlist_search'])->name('admin-playlist-search');
            Route::post('playlist/search/del', [AdminPlaylistController::class, 'playlist_del'])->name('admin-playlist-del');

            Route::post('playlist/chg', [AdminPlaylistController::class, 'playlist_chg'])->name('admin-playlist-chg');
            
            //管理者　おすすめ------------------------------------------------------------------------
            Route::get('recommend/reg', [AdminRecommendController::class, 'recommend_regist'])->name('admin-recommend-reg');
            Route::get('recommend/chg', [AdminRecommendController::class, 'recommend_chg'])->name('admin-recommend-chg');
            Route::get('recommend/search', [AdminRecommendController::class, 'recommend_search'])->name('admin-recommend-search');

        });
    });
});
