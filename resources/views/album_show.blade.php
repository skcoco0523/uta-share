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
                            <td class="col-9" onclick="redirectToMusicShow({{$album->music[$i]->id}})">{{Str::limit($album->music[$i]->name, 20, '...')}}</td>
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
              +"id": 9
  +"name": "二度寝/Bling-Bang-Bang-Born"
  +"art_id": 3
  +"release_date": "2024-01-29"
  +"aff_id": 9
  +"created_at": "2024-02-08 20:11:43"
  +"updated_at": "2024-02-08 20:11:43"
  +"art_name": "Creepy Nuts"
  +"music": 
Illuminate\Support
\
Collection {#369 ▼
    #items: array:2 [▼
      0 => {#1137 ▼
        +"id": 101
        +"alb_id": 9
        +"art_id": 3
        +"name": "二度寝"
        +"release_date": null
        +"link": null
        +"aff_id": null
        +"created_at": "2024-02-08 20:11:43"
        +"updated_at": "2024-02-08 20:11:43"
      }
      1 => {#1356 ▶}
    ]
    #escapeWhenCastingToString: false
  }
  +"mus_cnt": 2
  +"href": "
https://hb.afl.rakuten.co.jp/ichiba/391491f7.14cbe979.391491f8.285a1313/?pc=https%3A%2F%2Fitem.rakuten.co.jp%2Fbook%2F17740830%2F&link_type=pict&ut=eyJwYWdlIjoi
 ▶
"
  +"src": "
https://hbb.afl.rakuten.co.jp/hgb/391491f7.14cbe979.391491f8.285a1313/?me_id=1213310&item_id=21180045&pc=https%3A%2F%2Fthumbnail.image.rakuten.co.jp%2F%400_mall
 ▶
"
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