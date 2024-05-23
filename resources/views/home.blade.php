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
        @if(isset($ranking))
            <div class="py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>週間閲覧ランキング</h5> <a href="#" class="text-danger ml-auto">もっと見る＞</a>
                </div>
                <div class="d-flex overflow-auto contents_box">
                    @for ($i=0; $i < count($ranking); $i++)
                    <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                        <img src="{{ $ranking[$i]->src }}" class="card-img-top" alt="pic" style="object-fit: cover; height: 75%;">
                        <p class="card-text text-truncate">{{$ranking[$i]->name}}</p>
                        <p class="card-text text-truncate">{{$ranking[$i]->alb_name}}</p>
                    </div>
                    @endfor
                </div>
            </div>
        @endif
        <?//プレイリスト?>  
        @if(isset($playlist))
            <div class="py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>プレイリスト</h5> <a href="#" class="text-danger ml-auto">もっと見る＞</a>
                </div>
                <div class="d-flex overflow-auto contents_box">
                    @for ($i=0; $i < count($playlist); $i++)
                    <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                        <!--<img src="..." class="card-img-top" alt="pic" style="object-fit: cover; height: 70%;">-->
                        <div class="image-container" style="display: flex; flex-wrap: wrap; width: 100%; height: 75%;">
                        @if(isset($playlist[$i]->pic_dir[0]))
                            <div class="image-part" style="width: 50%; height: 50%;">
                                <img src="{{ $ranking[$i]->src[0] }}" class="card-img-top" alt="pic" style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                        @endif
                        @if(isset($playlist[$i]->pic_dir[1]))
                            <div class="image-part" style="width: 50%; height: 50%;">
                                <img src="{{ $ranking[$i]->src[1] }}" class="card-img-top" alt="pic" style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                        @endif
                        @if(isset($playlist[$i]->pic_dir[2]))
                            <div class="image-part" style="width: 50%; height: 50%;">
                                <img src="{{ $ranking[$i]->src[2] }}" class="card-img-top" alt="pic" style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                        @endif
                        @if(isset($playlist[$i]->pic_dir[3]))
                            <div class="image-part" style="width: 50%; height: 50%;">
                                <img src="{{ $ranking[$i]->src[3] }}" class="card-img-top" alt="pic" style="object-fit: cover; width: 100%; height: 100%;">
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
            <div class="d-flex justify-content-between align-items-center">
                <h5>おすすめ：曲</h5>
                <a href="#" class="text-danger">もっと見る＞</a>
            </div>
                <div class="d-flex overflow-auto contents_box">
                    @for ($i=0; $i < count($recommend_mus); $i++)
                    <a href="{{ route('music-list-show', ['list' => $recommend_mus[$i]]) }}" style="text-decoration: none; color: inherit;">
                        <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                                <img src="{{ $recommend_mus[$i]->detail[0]->src }}" class="card-img-top" alt="pic" style="object-fit: cover; height: 75%;">
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
            <div class="d-flex justify-content-between align-items-center">
                <h5>おすすめ：アルバム</h5>
                <a href="#" class="text-danger">もっと見る＞</a>
            </div>
                <div class="d-flex overflow-auto contents_box">
                    @for ($i=0; $i < count($recommend_alb); $i++)
                    <a href="{{ route('album-list-show', ['list' => $recommend_alb[$i]]) }}" style="text-decoration: none; color: inherit;">
                        <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                                <img src="{{ $recommend_alb[$i]->detail[0]->src }}" class="card-img-top" alt="pic" style="object-fit: cover; height: 75%;">
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
            <div class="d-flex justify-content-between align-items-center">
                <h5>おすすめ：プレイリスト</h5>
                <a href="#" class="text-danger">もっと見る＞</a>
            </div>
                <div class="d-flex overflow-auto contents_box">
                    @for ($i=0; $i < count($recommend_pl); $i++)
                    <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                            <img src="{{ $recommend_pl[$i]->detail[0]->music[0]->src }}" class="card-img-top" alt="pic" style="object-fit: cover; height: 75%;">
                            <p class="card-text">{{$recommend_pl[$i]->name}}</p>
                    </div>
                    @endfor
                </div>
            </div>
        @endif

        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')


    </div>
</div>
</div>
@endsection
