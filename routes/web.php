<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

//管理者
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

    //管理者
    Route::group(['prefix' => 'admin'], function(){

        Route::get('home', [AdminHomeController::class, 'home'])->name('admin-home');

        //管理者　音楽------------------------------------------------------------------------
        Route::get('music/reg', [AdminMusicController::class, 'music_regist'])->name('admin-music-reg');
        Route::get('music/bulkreg', [AdminMusicController::class, 'music_bulkreg'])->name('admin-music-bulkreg');
        Route::get('music/chg', [AdminMusicController::class, 'music_chg'])->name('admin-music-chg');
        Route::get('music/search', [AdminMusicController::class, 'music_search'])->name('admin-music-search');
    
        //管理者　アルバム------------------------------------------------------------------------
        Route::get('album/reg', [AdminAlbumController::class, 'album_regit'])->name('admin-album-reg');
        Route::post('album/reg', [AdminAlbumController::class, 'album_reg'])->name('admin-album-reg');

        Route::get('album/chgdetail', [AdminAlbumController::class, 'album_chg_detail'])->name('admin-album-chgdetail');
        Route::post('album/chgdetail', [AdminAlbumController::class, 'album_chg_detail_fnc'])->name('admin-album-chgdetail_fnc');

        Route::get('album/search', [AdminAlbumController::class, 'album_search'])->name('admin-album-search');
        Route::post('album/search/del', [AdminAlbumController::class, 'album_del'])->name('admin-album-del');
        Route::post('album/search/chg', [AdminAlbumController::class, 'album_chg'])->name('admin-album-chg');
    
        //管理者　アーティスト------------------------------------------------------------------------
        Route::get('artist/reg', [AdminArtistController::class, 'artist_regist'])->name('admin-artist-reg');
        Route::post('artist/reg', [AdminArtistController::class, 'artist_reg'])->name('admin-artist-reg');
    
        Route::get('artist/bulkreg', [AdminArtistController::class, 'artist_bulkreg'])->name('admin-artist-bulkreg');
        
        Route::get('artist/search', [AdminArtistController::class, 'artist_search'])->name('admin-artist-search');
        Route::post('artist/search/del', [AdminArtistController::class, 'artist_del'])->name('admin-artist-del');
        Route::post('artist/search/chg', [AdminArtistController::class, 'artist_chg'])->name('admin-artist-chg');
        
    
        //管理者　プレイリスト------------------------------------------------------------------------
        Route::get('playlist/reg', [PlaylistController::class, 'playlist_regist'])->name('admin-playlist-reg');
        Route::get('playlist/bulkreg', [PlaylistController::class, 'playlist_bulkreg'])->name('admin-playlist-bulkreg');
        Route::get('playlist/chg', [PlaylistController::class, 'playlist_chg'])->name('admin-playlist-chg');
        Route::get('playlist/search', [PlaylistController::class, 'playlist_search'])->name('admin-playlist-search');
        
        //管理者　おすすめ------------------------------------------------------------------------
        Route::get('recommend/reg', [RecommendController::class, 'recommend_regist'])->name('admin-recommend-reg');
        Route::get('recommend/bulkreg', [RecommendController::class, 'recommend_bulkreg'])->name('admin-recommend-bulkreg');
        Route::get('recommend/chg', [RecommendController::class, 'recommend_chg'])->name('admin-recommend-chg');
        Route::get('recommend/search', [RecommendController::class, 'recommend_search'])->name('admin-recommend-search');

    });
});
