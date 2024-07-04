@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<a href="{{$music->href }}">
    <img src="{{ $music->src }}" class="icon-80p" alt="pic">
</a>
<div class="text-center">
    
    <p class="detail-title">
        <a href="https://www.youtube.com/results?search_query={{ $music->name }} {{ $music->art_name }}" target="_blank" class="no-decoration">
            {{ $music->name }}
        </a>
    </p>

    <p class="detail-txt">
        <a href="{{ route('album-show',  ['id' => $music->alb_id]) }}" class="no-decoration">
            アルバム：{{ $music->alb_name }}
        </a>
    </p>
    <p class="detail-txt">
        <a href="{{ route('artist-show',  ['id' => $music->art_id]) }}" class="no-decoration">
            アーティスト：{{ $music->art_name }}
        </a>
    </p>   
    <!--対象アーティストの曲一覧に遷移させるリンク-->
</div>
<?//メニューは別ファイルで管理?>   
@include('layouts.menu_table', ['detail_id' => $music->id, 'table' => 'mus', 'fav_flag' => $music->fav_flag])

<?//ェアポップアップモーダル?>  
@include('modals.share-modal', ['title' => $music->name, 'url' => url()->current()])

@endsection
