@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')

        <form action="{{ route('search-list-show') }}" method="GET">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="search" name="keyword" class="form-control" placeholder="曲、アーティスト、アルバムなど" aria-describedby="basic-addon1">
            </div>
        </form>
        
        <?//入力時は以下を非表示にする?>
        <h4>履歴</h4>
        <ul>
            @foreach ($history as $his)
                <li>{{ $his->search_query }} ({{ $his->created_at->format('Y-m-d H:i:s') }})</li>
            @endforeach
        </ul>

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
