@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <a href="{{$music->href }}">
            <div class="py-2 d-flex justify-content-center">
                <div class="card" style="width: 80%; height: calc(80% * 1);">
                    <img src="{{ $music->src }}" class="card-img-top" alt="pic" style="object-fit: cover; ">
                </div>
            </div>
        </a>
        <div class="text-center">
            <p class="detail-title">{{ $music->name }}</p>
            <p class="detail-txt">
                <a href="{{ route('album-show',  ['id' => $music->alb_id]) }}" style="text-decoration: none; color: inherit;">{{ $music->alb_name }}</a>
            </p>
            <p class="detail-txt">{{ $music->art_name }}</p>     <!--対象アーティストの曲一覧に遷移させるリンク-->
        </div>
        <!--共有　お気に入り　プレイリスト登録-->
        <table class="table table-borderless table-center">
            <tbody>
                <tr>
                    <td class="col-1"></td>
                    <th class="col-3" onclick="openShareModal('{{ request()->url()}}?id={{ $music->mus_id }}')">
                        <i class="fa-regular fa-share-from-square icon-20 red"></i>
                    </th>
                    
                    <td class="col-3" favorite-id="{{ $music->mus_id }}">
                        
                        @if($music->fav_flag)
                            <i id="favoriteIcon-mus-{{ $music->mus_id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('mus',{{ $music->mus_id }})"></i>
                        @else
                            <i id="favoriteIcon-mus-{{ $music->mus_id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('mus',{{ $music->mus_id }})"></i>
                        @endif
                    </td>
                    <td class="col-3">
                        <i class="fa-regular fa-square-plus icon-20 red"></i>
                    </td>
                    <td class="col-1"></td>
                </tr>
            </tbody>
        </table>

        <!-- シェアポップアップモーダル -->
        @include('modals.share-modal', ['title' => $music->name, 'url' => url()->current()])
        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // お気に入り状態初期値を定義
        setFavoriteActions("mus",{{ $music->mus_id }}, {{$music->fav_flag}});
    });
</script>
