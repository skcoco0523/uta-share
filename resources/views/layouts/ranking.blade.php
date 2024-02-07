<?//ホーム用ランキング追加 20240118 kanno?>
@guest
@else

<?//ホーム用ランキング情報  現状スクロールバーの非表示はできていない?>

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

@endguest




