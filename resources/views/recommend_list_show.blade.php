@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <div class="py-2 d-flex justify-content-center">
            <p class="card-text">{{ $recommnd->name }}</p>

        </div>
        <div class="text-center ">
            <table class="table table-borderless table-center">
                <tbody>
                {{--お気に入りの初期状態を取得--}}
                @foreach ($recommnd->detail as $key => $detail)
                    <tr>
                        <td class="col-1" onclick="redirectToDetailShow({{ $detail->detail_id }}, {{ $recommnd->category }})">
                            {{ $key + 1 }}
                        </td>
                        <td class="col-9" onclick="redirectToDetailShow({{ $detail->detail_id }}, {{ $recommnd->category }})">
                            {{ Str::limit($detail->name, 30, '...') }}
                        </td>
                        <td class="col-1" favorite-id="{{ $detail->detail_id }}">
                            @if($detail->fav_flag)
                                <i id="favoriteIcon-{{ $detail->detail_id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->detail_id }}, {{ $recommnd->category }})"></i>
                            @else
                                <i id="favoriteIcon-{{ $detail->detail_id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->detail_id }}, {{ $recommnd->category }})"></i>
                            @endif
                        </td>
                        <td class="col-1">
                            <img src="{{ asset('img/icon/add.png') }}" alt="アイコン" class="icon-20">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection

<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        // お気に入り状態初期値を定義
        <?php foreach ($recommnd->detail as $detail): ?>
            setFavoriteActions({{ $detail->detail_id }}, {{$detail->fav_flag}});
        <?php endforeach; ?>

    });

    function redirectToDetailShow(detail_id,category) {
        switch(category){
            case 0:
                window.location.href = "{{ route('music-show') }}?id=" + detail_id;
                break;
            case 1:
                break;
            case 2:
                window.location.href = "{{ route('album-show') }}?id=" + detail_id;
                break;
            case 3:
                window.location.href = "{{ route('playlist-show') }}?id=" + detail_id;
                break;
            default:
                break;
        }
    }

</script>