@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <a href="{{$album->href }}">
            <div class="py-2 d-flex justify-content-center">
                    <div class="card" style="width: 80%; height: calc(80% * 1);">
                        <img src="{{ $album->src }}" class="card-img-top" alt="pic" style="object-fit: cover; ">
                    </div>
            </div>
        </a>
        <div class="text-center ">
            <p class="card-text">{{ $album->name }}</p>
            <p class="card-text">{{ $album->art_name }}</p>     <!--対象アーティストの曲一覧に遷移させるリンク-->
            <p>{{ $album->release_date }}：{{$album->mus_cnt }}曲</p>
        </div>

        <!-- シェアポップアップモーダル -->
        @include('modals.share-modal', ['title' => $album->name, 'url' => url()->current()])
        
        <?//メニューは別ファイルで管理?>   
        @include('layouts.menu_table', ['detail_id' => $album->id, 'table' => 'alb', 'fav_flag' => $album->fav_flag])

        <?//テーブルリストは別ファイルで管理?>
        @include('layouts.list_table', ['detail_table' => $album->music, 'table' => 'mus'])

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