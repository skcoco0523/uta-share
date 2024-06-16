@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　未使用ブレード?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <div class="py-2 d-flex justify-content-center">
            <p class="card-text">{{ $playlist['list']['name'] }}</p>

        </div>
        <div class="text-center ">
            <table class="table table-borderless">
                <tbody>
                @foreach ($playlist['list']['detail'] as $key => $detail)
                    <tr>
                        <td class="col-1" onclick="redirectToPlaylistShow({{ $detail['id'] }})">{{ $key + 1 }}</th>
                        <td class="col-9" onclick="redirectToPlaylistShow({{ $detail['id'] }})">{{ Str::limit($detail['name'], 30, '...') }}</td>
                        <td class="col-1">
                            <img src="{{ asset('img/icon/fav_red1.png') }}" alt="アイコン" class="icon-20">
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
    function redirectToPlaylistShow(id) {
        window.location.href = "{{ route('playlist-show') }}?id=" + id;
    }
</script>