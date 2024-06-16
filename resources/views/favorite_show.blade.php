@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>

        <!--
        <div class="tabs">
            <button class="tab-link active" onclick="openTab(event, 'all')">すべて</button>
            <button class="tab-link" onclick="openTab(event, 'songs')">曲</button>
            <button class="tab-link" onclick="openTab(event, 'albums')">アルバム</button>
            <button class="tab-link" onclick="openTab(event, 'playlists')">プレイリスト</button>
        </div>  
        -->
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item nav-item-red"><a class="nav-link nav-link-red active" onclick="openTab(event, 'all')" aria-current="page" href="#">すべて</a></li>
            <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'songs')">曲</a></li>
            <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'albums')">アルバム</a></li>
            <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'playlists')">プレイリスト</a></li>
        </ul>

        @php $tab_name = ['曲', 'アルバム', 'アーティスト', 'プレイリスト']; @endphp
        <div id="all" class="tab-content active">
            @foreach ($favorite_list as $key=> $table)
                @if($table) 
                    <h3>{{$tab_name[$key]}}</h3>
                    @if($key==0)
                        @include('layouts.table', ['music_table' => $table])
                    @elseif($key==1)

                    @elseif($key==2)
                        @include('layouts.table', ['album_table' => $table])
                    @elseif($key==3)
                        @include('layouts.table', ['playlist_table' => $table])
                    @endif
                @endif
            @endforeach
        </div>

        <div id="songs" class="tab-content">
            <h3>{{$tab_name[0]}}</h3>
            <?//テーブルリストは別ファイルで管理?>   
            @include('layouts.table', ['music_table' => $favorite_list[0]])
        </div>

        <div id="albums" class="tab-content">
            <h3>{{$tab_name[1]}}</h3>
            <?//テーブルリストは別ファイルで管理?>   
            @include('layouts.table', ['album_table' => $favorite_list[2]])
        </div>

        <div id="playlists" class="tab-content">
            <h3>{{$tab_name[3]}}</h3>
            <?//テーブルリストは別ファイルで管理?>   
            @include('layouts.table', ['playlist_table' => $favorite_list[3]])
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
    .nav-item-red {
        padding: 10px;
        cursor: pointer;
        border: none;
        outline: none;
    }

    /* 通常のナビリンクの色 */
    .nav-link-red {
        padding: 3px 0px !important;
        color: #000 !important; /* 通常のテキストカラー */
        background-color: #ffffff !important; /* 通常の背景カラー */
        border: 1px solid #ff0000 !important; /* 非アクティブ時の赤枠 */
    }

    /* ホバー時のナビリンクの色 */
    .nav-link-red:hover {
        color: #ff0000 !important; /* ホバー時のテキストカラー (赤) */
        background-color: #ffffff !important; /* ホバー時の背景カラー (白) */
    }

    /* アクティブなナビリンクの色 */
    .nav-link-red.active {
        color: #ffffff !important; /* アクティブ時のテキストカラー (白) */
        background-color: #ff0000 !important; /* アクティブ時の背景カラー (赤) */
    }

    /* 具体的なセレクタを使用する場合 */
    ul.nav.nav-pills.nav-fill .nav-link-red.active {
        color: #ffffff !important; /* アクティブ時のテキストカラー (白) */
        background-color: #ff0000 !important; /* アクティブ時の背景カラー (赤) */
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>
