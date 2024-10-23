@extends('layouts.app')

<?//コンテンツ?>  
@section('content')

<?//広告バナー?> 
@include('layouts.adv_banner')

<?//お気に入りランキング?>  
@if(isset($ranking['fav_mus']))
    <div class="py-3">
        <div class="title-text">
            <h3>お気に入りの曲　TOP10</h3>
            <a href="{{ route('favorite-ranking', ['table' => 'mus']) }}">
                <i class="fa-solid fa-greater-than icon-20 red title-right"></i>
            </a>
        </div>
        <div class="d-flex overflow-auto contents_box">
            @foreach ($ranking['fav_mus'] as $data)
            <a href="{{ route('music-show',['id' => $data->id]) }}" class="no-decoration">
                <div class="card card-mini" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                    <img src="{{ $data->src }}" class="card-img-mini" alt="pic" style="object-fit: cover; height: 75%;">
                    <p class="card-text text-truncate">{{$data->name}}</p>
                    <p class="card-text text-truncate">{{$data->art_name}}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endif

<?//おすすめ：曲?> 
@if(isset($recommend_mus))
    <div class="py-2">
        <div class="title-text">
            <h3>曲</h3>
            <a href="{{ route('recommend-list-show', ['category' => 0]) }}">
                <i class="fa-solid fa-greater-than icon-20 red title-right"></i>
            </a>
        </div>
        <div class="d-flex overflow-auto contents_box">
            @foreach ($recommend_mus as $recom_mus)
            <a href="{{ route('recommend-show', ['id' => $recom_mus->id]) }}" class="no-decoration">
                <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                        <img src="{{ $recom_mus->detail[0]->src }}" class="card-img-mini" alt="pic" style="object-fit: cover; height: 75%;">
                        <p class="card-text">{{$recom_mus->name}}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endif 
<?//おすすめ：アルバム?>
@if(isset($recommend_alb))
    <div class="py-2">
        <div class="title-text">
            <h3>アルバム</h3>
            <a href="{{ route('recommend-list-show', ['category' => 2]) }}">
                <i class="fa-solid fa-greater-than icon-20 red title-right"></i>
            </a>
        </div>
        <div class="d-flex overflow-auto contents_box">
            @foreach ($recommend_alb as $recom_alb)
            <a href="{{ route('recommend-show', ['id' => $recom_alb->id]) }}" class="no-decoration">
                <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                        <img src="{{ $recom_alb->detail[0]->src }}" class="card-img-mini" alt="pic" style="object-fit: cover; height: 75%;">
                        <p class="card-text">{{$recom_alb->name}}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endif
<?//おすすめ：プレイリスト?>
@if(isset($recommend_pl))
    <div class="py-2">
        <div class="title-text">
            <h3>プレイリスト</h3>
            <a href="{{ route('recommend-list-show', ['category' => 3]) }}">
                <i class="fa-solid fa-greater-than icon-20 red title-right"></i>
            </a>
        </div>
        <div class="d-flex overflow-auto contents_box">
            @foreach ($recommend_pl as $recom_pl)
            <a href="{{ route('recommend-show', ['id' => $recom_pl->id]) }}" class="no-decoration">
                <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                        <img src="{{ $recom_pl->detail[0]->music[0]->src }}" class="card-img-mini" alt="pic" style="object-fit: cover; height: 75%;">
                        <p class="card-text">{{$recom_pl->name}}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endif

@endsection
