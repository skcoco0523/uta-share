@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
<a href="{{$album->href }}">
    <img src="{{ $album->src }}" class="icon-80p" alt="pic">
</a>
<div class="text-center">
    <p class="detail-title">{{ $album->name }}</p>
    <p class="detail-txt">
        <a href="{{ route('artist-show',  ['id' => $album->art_id]) }}" style="text-decoration: none; color: inherit;">
            {{ $album->art_name }}
        </a>
    </p>   
    <p>{{ $album->release_date }}：{{$album->mus_cnt }}曲</p>
</div>

<!-- シェアポップアップモーダル -->
@include('modals.share-modal', ['title' => $album->name, 'url' => url()->current()])

<?//メニューは別ファイルで管理?>   
@include('layouts.menu_table', ['detail_id' => $album->id, 'table' => 'alb', 'fav_flag' => $album->fav_flag])

<?//テーブルリストは別ファイルで管理?>
@include('layouts.list_table', ['detail_table' => $album->music, 'table' => 'mus'])

@endsection

<script>
    function redirectToMusicShow(id) {
        window.location.href = "{{ route('music-show') }}?id=" + id;
    }
</script>