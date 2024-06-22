@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <div class="text-center ">
            <p class="card-text">{{ $artist->art_name }}</p>     <!--対象アーティストの曲一覧に遷移させるリンク-->
            <p>デビュー：{{ \Carbon\Carbon::parse($artist->debut)->format('Y-m') }}</p>
        </div>

        <!-- シェアポップアップモーダル -->
        @include('modals.share-modal', ['title' => $artist->name, 'url' => url()->current()])
        
        <?//メニューは別ファイルで管理?>   
        @include('layouts.menu_table', ['detail_id' => $artist->id, 'table' => 'art', 'fav_flag' => $artist->fav_flag])

        <?//テーブルリストは別ファイルで管理?>
        {{--@include('layouts.list_table', ['detail_table' => $artist->album, 'table' => 'alb'])--}}

        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection

<script>
    function redirectToMusicShow(id) {
        window.location.href = "{{ route('music-show') }}?id=" + id;
    }
</script>