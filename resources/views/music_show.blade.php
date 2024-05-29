<link rel="stylesheet" href="{{ asset('/css/style.css') }}">

@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <a href="{{$music->href }}">
        <div class="py-2 d-flex justify-content-center">
                <div class="card" style="width: 80%; height: calc(80% * 1);">
                    <img src="{{ $music->src }}" class="card-img-top" alt="pic" style="object-fit: cover; ">
                </div>
        </div>
            </a>
        <div class="text-center">
            <p class="card-text">{{ $music->name }}</p>
            <a >
            <p class="card-text">
                <a href="{{ route('album-show',  ['id' => $music->alb_id]) }}" style="text-decoration: none; color: inherit;">{{ $music->alb_name }}</a>
            </p>
            <p class="card-text">{{ $music->art_name }}</p>     <!--対象アーティストの曲一覧に遷移させるリンク-->
        </div>
        <!--
            共有　お気に入り　プレイリスト登録
        -->
        

        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection
