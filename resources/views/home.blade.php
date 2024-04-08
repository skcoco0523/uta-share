<link rel="stylesheet" href="{{ asset('/css/style.css') }}">

@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')

        <?//スライダー?>  
        @include('layouts.slider')
        
        <?//ランキング?>  
        @include('layouts.ranking')

        <?//プレイリスト?>  
        @include('layouts.playlist')

        <?//おすすめ?>  
        @include('layouts.recommend')

        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')


    </div>
</div>
</div>
@endsection
