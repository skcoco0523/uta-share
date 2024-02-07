
<link rel="stylesheet" href="{{ asset('/css/style.css') }}">
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    
    @include('layouts.slider')
    <div class="col-md-8">
        
        <?//ランキング?>
        @include('layouts.ranking')

        <?//プレイリスト?>
        @include('layouts.playlist')

        <?//おすすめ?>
        @include('layouts.recommend')

        
    </div>
</div>
@endsection
