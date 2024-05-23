<link rel="stylesheet" href="{{ asset('/css/style.css') }}">

@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <a href="{{$album->href }}">
        <div class="py-2 d-flex justify-content-center">
                <div class="card" style="width: 80%; height: calc(80% * 1);">
                    <img src="{{ $album->src }}" class="card-img-top" alt="pic" style="object-fit: cover; ">
                </div>
        </div>
            </a>
        <div class="text-center ">
            <p class="card-text">{{ $album->name }}</p>
            <p class="card-text">{{ $album->art_name }}</p>     <!--対象アーティストの曲一覧に遷移させるリンク-->
            <p>{{ $album->release_date }}：{{$album->mus_cnt }}曲</p>
            <table class="table table-borderless">
                <tbody>
                    @for ($i=0; $i < $album->mus_cnt; $i++)
                        <tr>
                            <th class="col-1" onclick="redirectToMusicShow({{$album->music[$i]->id}})">{{$i+1}}</th>
                            <td class="col-9" onclick="redirectToMusicShow({{$album->music[$i]->id}})">{{Str::limit($album->music[$i]->name, 30, '...')}}</td>
                            <td class="col-1">♡</td>
                            <td class="col-1">＋</td>
                        </tr>
                    @endfor
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