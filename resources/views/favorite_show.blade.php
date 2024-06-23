@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <div class="d-flex overflow-auto contents_box">
            <ul class="nav nav-pills flex-nowrap">
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red active" onclick="openTab(event, 'all')" aria-current="page" href="#">すべて</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'songs')">曲</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'albums')">アルバム</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'playlists')">プレイリスト</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'myplaylists')">Myプレイリスト</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'category')">カテゴリ別</a></li>
            </ul>
        </div>
        <br>
        <div id="all" class="tab-content active">
            <?//テーブルリストは別ファイルで管理?>   
            <h3>曲</h3>
            @include('layouts.list_table', ['detail_table' => $favorite_list["mus"], 'table' => 'mus'])
            <h3>アルバム</h3>
            @include('layouts.list_table', ['detail_table' => $favorite_list["alb"], 'table' => 'alb'])
            <h3>プレイリスト</h3>
            @include('layouts.list_table', ['detail_table' => $favorite_list["pl"], 'table' => 'pl'])
        </div>

        <?//テーブルリストは別ファイルで管理?>   
        <div id="songs" class="tab-content">
            <h3>曲</h3>
            @include('layouts.list_table', ['detail_table' => $favorite_list["mus"], 'table' => 'mus'])
        </div>

        <div id="albums" class="tab-content">
            <h3>アルバム</h3> 
            @include('layouts.list_table', ['detail_table' => $favorite_list["alb"], 'table' => 'alb'])
        </div>

        <div id="playlists" class="tab-content">
            <h3>プレイリスト</h3>
            @include('layouts.list_table', ['detail_table' => $favorite_list["pl"], 'table' => 'pl'])
        </div>

        <div id="myplaylists" class="tab-content">
            <h3>Myプレイリスト</h3>
        </div>

        <div id="category" class="tab-content">
            <h3>カテゴリ別</h3>
        </div>
        
        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection

<script>
    // 初期表示設定
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('all').style.display = 'block';
    });
</script>

<style>
</style>
