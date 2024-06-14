@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <div class="py-2 d-flex justify-content-center">
            <p class="card-text">{{ $music['list']['name'] }}</p>

        </div>
        <div class="text-center ">
            <table class="table table-borderless table-center">
                <tbody>
                @foreach ($music['list']['detail'] as $key => $detail)
                    <tr>
                        <td class="col-1" onclick="redirectToMusicShow({{ $detail['id'] }})">
                            {{ $key + 1 }}
                        </td>
                        <td class="col-9" onclick="redirectToMusicShow({{ $detail['id'] }})">
                            {{ Str::limit($detail['name'], 30, '...') }}
                        </td>
                        <td class="col-1">
                            @if($detail["fav_flag"])
                                <i id="favoriteIcon" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite({{ $detail['mus_id'] }}, 0)"></i>
                            @else
                                <i id="favoriteIcon" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite({{ $detail['mus_id'] }}, 0)"></i>
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
        

        <!--
            一覧
            共有　お気に入り　プレイリスト登録
        -->

        

        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection

<script>
    function redirectToMusicShow(id) {
        window.location.href = "{{ route('music-show') }}?id=" + id;
    }
</script>