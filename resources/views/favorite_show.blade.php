@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <div class="d-flex overflow-auto">
            <ul class="nav nav-pills flex-nowrap">
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red active" onclick="openTab(event, 'all')" aria-current="page" href="#">すべて</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'songs')">曲</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'albums')">アルバム</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'playlists')">プレイリスト</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'myplaylists')">マイプレイリスト</a></li>
                <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'category')">カテゴリ別</a></li>
            </ul>
        </div>

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
        
        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;

        // 全てのタブコンテンツを非表示にする
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // 全てのタブリンクからactiveクラスを削除する
        tablinks = document.getElementsByClassName("nav-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // 現在のタブコンテンツを表示し、タブリンクにactiveクラスを追加する
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // 初期表示設定
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('all').style.display = 'block';
    });
</script>

<style>
</style>
