<?//ホーム用スライダー追加 20240118 kanno?>
@guest
@else

<?//画像情報はDBから参照するように改修予定?>

<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
<div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="5000">
    <img src="{{ asset('img/slider/test1.png') }}" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="5000">
    <img src="{{ asset('img/slider/test2.png') }}" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="5000">
    <img src="{{ asset('img/slider/test3.png') }}" class="d-block w-100" alt="...">
    </div>
</div>
<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
</button>
</div>
@endguest




