@extends('layouts.app')

<?//コンテンツ?>  
@section('content')

<?//スライダー?>  
@include('layouts.slider')

<?//ランキング?>  
@if(isset($ranking['fav_mus']))
    <div class="py-3">
        <div class="title-text">
            <h3>みんなのお気に入り</h3>
            <i class="fa-solid fa-chevron-up fa-rotate-90 icon-20 red title-right"></i>
        </div>
        <div class="d-flex overflow-auto contents_box">
            @for ($i=0; $i < count($ranking['fav_mus']); $i++)
            <a href="{{ route('music-show',['id' => $ranking['fav_mus'][$i]->id]) }}" style="text-decoration: none; color: inherit;">
                <div class="card card-mini" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                    <img src="{{ $ranking['fav_mus'][$i]->src }}" class="card-img-mini" alt="pic" style="object-fit: cover; height: 75%;">
                    <p class="card-text text-truncate">{{$ranking['fav_mus'][$i]->name}}</p>
                    <p class="card-text text-truncate">{{$ranking['fav_mus'][$i]->alb_name}}</p>
                </div>
            </a>
            @endfor
        </div>
    </div>
@endif
<?//プレイリスト?>  
@if(isset($playlist))
    <div class="py-2">
        <div class="title-text">
            <h3>プレイリスト</h3>
            <i class="fa-solid fa-chevron-up fa-rotate-90 icon-20 red title-right"></i>
        </div>
        <div class="d-flex overflow-auto contents_box">
            @for ($i=0; $i < count($playlist); $i++)
            <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                <!--<img src="..." class="card-img-top" alt="pic" style="object-fit: cover; height: 70%;">-->
                <div class="image-container" style="display: flex; flex-wrap: wrap; width: 100%; height: 75%;">
                @if(isset($playlist[$i]->pic_dir[0]))
                    <div class="image-part" style="width: 50%; height: 50%;">
                        <img src="{{ $ranking[$i]->src[0] }}" class="card-img-mini" alt="pic" style="object-fit: cover; width: 100%; height: 100%;">
                    </div>
                @endif
                @if(isset($playlist[$i]->pic_dir[1]))
                    <div class="image-part" style="width: 50%; height: 50%;">
                        <img src="{{ $ranking[$i]->src[1] }}" class="card-img-mini" alt="pic" style="object-fit: cover; width: 100%; height: 100%;">
                    </div>
                @endif
                @if(isset($playlist[$i]->pic_dir[2]))
                    <div class="image-part" style="width: 50%; height: 50%;">
                        <img src="{{ $ranking[$i]->src[2] }}" class="card-img-mini" alt="pic" style="object-fit: cover; width: 100%; height: 100%;">
                    </div>
                @endif
                @if(isset($playlist[$i]->pic_dir[3]))
                    <div class="image-part" style="width: 50%; height: 50%;">
                        <img src="{{ $ranking[$i]->src[3] }}" class="card-img-mini" alt="pic" style="object-fit: cover; width: 100%; height: 100%;">
                    </div>
                @endif
                </div>
                <p class="card-text">{{$playlist[$i]->name}} ({{$playlist[$i]->cnt}})</p>
            </div>
            @endfor
        </div>
    </div>
@endif

<?//おすすめ：曲?> 
@if(isset($recommend_mus))
    <div class="py-2">
        <div class="title-text">
            <h3>曲</h3>
            <a href="{{ route('recommend-list-show', ['category' => 0]) }}">
                <i class="fa-solid fa-chevron-up fa-rotate-90 icon-20 red title-right"></i>
            </a>
        </div>
        <div class="d-flex overflow-auto contents_box">
            @for ($i=0; $i < count($recommend_mus); $i++)
            <a href="{{ route('recommend-show', ['id' => $recommend_mus[$i]->id]) }}" style="text-decoration: none; color: inherit;">
                <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                        <img src="{{ $recommend_mus[$i]->detail[0]->src }}" class="card-img-mini" alt="pic" style="object-fit: cover; height: 75%;">
                        <p class="card-text">{{$recommend_mus[$i]->name}}</p>
                </div>
            </a>
            @endfor
        </div>
    </div>
@endif 
<?//おすすめ：アルバム?>
@if(isset($recommend_alb))
    <div class="py-2">
        <div class="title-text">
            <h3>アルバム</h3>
            <a href="{{ route('recommend-list-show', ['category' => 2]) }}">
                <i class="fa-solid fa-chevron-up fa-rotate-90 icon-20 red title-right"></i>
            </a>
        </div>
        <div class="d-flex overflow-auto contents_box">
            @for ($i=0; $i < count($recommend_alb); $i++)
            <a href="{{ route('recommend-show', ['id' => $recommend_alb[$i]->id]) }}" style="text-decoration: none; color: inherit;">
                <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                        <img src="{{ $recommend_alb[$i]->detail[0]->src }}" class="card-img-mini" alt="pic" style="object-fit: cover; height: 75%;">
                        <p class="card-text">{{$recommend_alb[$i]->name}}</p>
                </div>
            </a>
            @endfor
        </div>
    </div>
@endif
<?//おすすめ：プレイリスト?>
@if(isset($recommend_pl))
    <div class="py-2">
        <div class="title-text">
            <h3>プレイリスト</h3>
            <a href="{{ route('recommend-list-show', ['category' => 3]) }}">
                <i class="fa-solid fa-chevron-up fa-rotate-90 icon-20 red title-right"></i>
            </a>
        </div>
        <div class="d-flex overflow-auto contents_box">
            @for ($i=0; $i < count($recommend_pl); $i++)
            <a href="{{ route('recommend-show', ['id' => $recommend_pl[$i]->id]) }}" style="text-decoration: none; color: inherit;">
                <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                        <img src="{{ $recommend_pl[$i]->detail[0]->music[0]->src }}" class="card-img-mini" alt="pic" style="object-fit: cover; height: 75%;">
                        <p class="card-text">{{$recommend_pl[$i]->name}}</p>
                </div>
            </a>
            @endfor
        </div>
    </div>
@endif

@endsection
