<?//ホーム用プレイリスト追加 20240118 kanno?>
@guest
@else

<?//ホーム用プレイリスト情報  現状スクロールバーの非表示はできていない?>

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

@endguest




