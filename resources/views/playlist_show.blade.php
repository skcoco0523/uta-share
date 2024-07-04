@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
{{-- 
<a href="{{//$playlist->href }}">
    <div class="py-2 d-flex justify-content-center">
        <div class="card" style="width: 80%; height: calc(80% * 1);">
            <img src="{{ $playlist->src }}" class="card-img-top" alt="pic" style="object-fit: cover; ">
        </div>
    </div>
</a>
--}}
<div class="text-center ">
    <p class="card-text">{{ $playlist->name }}</p>
</div>

<?//メニューは別ファイルで管理?>   
@include('layouts.menu_table', ['detail_id' => $playlist->id, 'table' => 'pl', 'fav_flag' => $playlist->fav_flag])

<!-- シェアポップアップモーダル -->
@include('modals.share-modal', ['title' => $playlist->name, 'url' => url()->current()])

<?//テーブルリストは別ファイルで管理?>
@include('layouts.list_table', ['detail_table' => $playlist->music, 'table' => 'mus'])

@endsection