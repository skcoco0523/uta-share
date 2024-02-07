<?//ホーム用おすすめ追加 20240118 kanno?>
@guest
@else

<?//ホーム用おすすめ情報  現状スクロールバーの非表示はできていない?>

@if(isset($recommend))
<div class="py-2">
<div class="d-flex justify-content-between align-items-center">
    <h5>おすすめ</h5>
    <a href="#" class="text-danger">もっと見る＞</a>
</div>
    <div class="d-flex overflow-auto contents_box">
        @for ($i=0; $i < count($recommend); $i++)
        <div class="card" style="width: 120px; height: 170px; flex: 0 0 auto; margin-right: 10px;">
                <img src="{{ $ranking[$i]->src }}" class="card-img-top" alt="pic" style="object-fit: cover; height: 75%;">
                <p class="card-text">{{$recommend[$i]->name}}</p>
        </div>
        @endfor
    </div>
</div>
@endif

@endguest




