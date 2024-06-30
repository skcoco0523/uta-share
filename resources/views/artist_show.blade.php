@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
<div class="text-center ">
    <p class="card-text">{{ $artist->art_name }}</p>
    <p>デビュー：{{ \Carbon\Carbon::parse($artist->debut)->format('Y-m') }}</p>
</div>

<!-- シェアポップアップモーダル -->
@include('modals.share-modal', ['title' => $artist->name, 'url' => url()->current()])

<?//メニューは別ファイルで管理?>   
@include('layouts.menu_table', ['detail_id' => $artist->id, 'table' => 'art', 'fav_flag' => $artist->fav_flag])

<?//テーブルリストは別ファイルで管理?>
<h3>アルバム</h3>
@include('layouts.list_table', ['detail_table' => $album, 'table' => 'alb'])
<h3>曲</h3>
@include('layouts.list_table', ['detail_table' => $music, 'table' => 'mus'])
<h3>プレイリスト</h3>
@include('layouts.list_table', ['detail_table' => $playlist, 'table' => 'pl'])

@endsection

<script>
    function redirectToMusicShow(id) {
        window.location.href = "{{ route('music-show') }}?id=" + id;
    }
</script>