
<table class="table table-borderless table-center">
    <tbody>
    
        <?//おすすめテーブル?>
        @if(isset($recommnd_table))
            @foreach ($recommnd_table->detail as $key => $detail)   
                <tr>
                    <td class="col-1" onclick="redirectToDetailShow({{ $detail->detail_id }}, {{ $recommnd->category }})">
                        {{ $key + 1 }}
                    </td>
                    <td class="col-9" onclick="redirectToDetailShow({{ $detail->detail_id }}, {{ $recommnd->category }})">
                        {{ Str::limit($detail->name, 30, '...') }}
                    </td>
                    <td class="col-1" favorite-id="{{ $detail->detail_id }}">
                        @if($detail->fav_flag)
                            <i id="favoriteIcon-{{ $recommnd->category }}-{{ $detail->detail_id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->detail_id }}, {{ $recommnd->category }})"></i>
                        @else
                            <i id="favoriteIcon-{{ $recommnd->category }}-{{ $detail->detail_id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->detail_id }}, {{ $recommnd->category }})"></i>
                        @endif
                    </td>
                    <td class="col-1">
                        <img src="{{ asset('img/icon/add.png') }}" alt="アイコン" class="icon-20">
                    </td>
                </tr>
            @endforeach
        @endif

        <?//ミュージックテーブル　　　アイコンの表示非表示を切り替える?>
        @if(isset($music_table))
            @foreach ($music_table as $key => $detail)   
                <tr>
                    <td class="col-1" onclick="redirectToDetailShow({{$detail->id}},0)">
                        {{$key+1}}
                    </th>
                    <td class="col-9" onclick="redirectToDetailShow({{$detail->id}},0)">
                        {{Str::limit($detail->name, 30, '...')}}
                    </td>
                    <td class="col-1" favorite-id="{{ $detail->id }}">
                        @if($detail->fav_flag)
                            <i id="favoriteIcon-0-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->id }}, 0)"></i>
                        @else
                            <i id="favoriteIcon-0-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->id }}, 0)"></i>
                        @endif
                    </td>
                    <td class="col-1">
                        <img src="{{ asset('img/icon/add.png') }}" alt="アイコン" class="icon-20">
                    </td>
                </tr>
            @endforeach
        @endif

        <?//アルバム　　　アイコンの表示非表示を切り替える?>
        @if(isset($album_table))
            @foreach ($album_table as $key => $detail)   
                <tr>
                    <td class="col-1" onclick="redirectToDetailShow({{$detail->id}},2)">
                        {{$key+1}}
                    </th>
                    <td class="col-9" onclick="redirectToDetailShow({{$detail->id}},2)">
                        {{Str::limit($detail->name, 30, '...')}}
                    </td>
                    <td class="col-1" favorite-id="{{ $detail->id }}">
                        @if($detail->fav_flag)
                            <i id="favoriteIcon-2-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->id }}, 2)"></i>
                        @else
                            <i id="favoriteIcon-2-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->id }}, 2)"></i>
                        @endif
                    </td>
                    <td class="col-1">
                        <img src="{{ asset('img/icon/add.png') }}" alt="アイコン" class="icon-20">
                    </td>
                </tr>
            @endforeach
        @endif

        <?//プレイリスト　　　アイコンの表示非表示を切り替える?>
        @if(isset($playlist_table))
            @foreach ($playlist_table as $key => $detail)   
                <tr>
                    <td class="col-1" onclick="redirectToDetailShow({{$detail->id}},3)">
                        {{$key+1}}
                    </th>
                    <td class="col-9" onclick="redirectToDetailShow({{$detail->id}},3)">
                        {{Str::limit($detail->name, 30, '...')}}
                    </td>
                    <td class="col-1" favorite-id="{{ $detail->id }}">
                        @if($detail->fav_flag)
                            <i id="favoriteIcon-3-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->id }}, 3)"></i>
                        @else
                            <i id="favoriteIcon-3-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->id }}, 3)"></i>
                        @endif
                    </td>
                    <td class="col-1">
                        <img src="{{ asset('img/icon/add.png') }}" alt="アイコン" class="icon-20">
                    </td>
                </tr>
            @endforeach
        @endif

    </tbody>
</table>

<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        
        <?php if(isset($recommnd_table)){ ?>
            // お気に入り状態初期値を定義
            <?php foreach ($recommnd_table->detail as $detail): ?>
                setFavoriteActions({{ $recommnd_table->category }}, {{ $detail->detail_id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>
        <?php if(isset($music_table)){ ?>
            // お気に入り状態初期値を定義
            <?php foreach ($music_table as $detail): ?>
                setFavoriteActions(0,{{ $detail->id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>
        <?php if(isset($album_table)){ ?>
            // お気に入り状態初期値を定義
            <?php foreach ($album_table as $detail): ?>
                setFavoriteActions(2,{{ $detail->id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>
        <?php if(isset($playlist_table)){ ?>
            // お気に入り状態初期値を定義
            <?php foreach ($playlist_table as $detail): ?>
                setFavoriteActions(3,{{ $detail->id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>

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