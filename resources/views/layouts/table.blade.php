
<table class="table table-borderless table-center">
    <tbody>
    
        <?//おすすめテーブル?>
        @if(isset($recommnd_table))
            @foreach ($recommnd_table->detail as $key => $detail)   
                <tr>
                    <td class="col-1" onclick="redirectToDetailShow({{ $detail->detail_id }}, '{{ $recommnd->table }}')">
                        {{ $key + 1 }}
                    </td>
                    <td class="col-9" onclick="redirectToDetailShow({{ $detail->detail_id }}, '{{ $recommnd->table }}')">
                        {{ Str::limit($detail->name, 30, '...') }}
                    </td>
                    <td class="col-1" favorite-id="{{ $detail->detail_id }}">
                        @if($detail->fav_flag)
                            <i id="favoriteIcon-{{ $recommnd->table }}-{{ $detail->detail_id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('{{ $recommnd->table }}', {{ $detail->detail_id }})"></i>
                        @else
                            <i id="favoriteIcon-{{ $recommnd->table }}-{{ $detail->detail_id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('{{ $recommnd->table }}', {{ $detail->detail_id }})"></i>
                        @endif
                    </td>
                    <td class="col-1">
                        <i class="fa-regular fa-square-plus icon-20 red"></i>
                    </td>
                </tr>
            @endforeach
        @endif

        <?//ミュージックテーブル　　　アイコンの表示非表示を切り替える?>
        @if(isset($music_table))
            @foreach ($music_table as $key => $detail)   
                <tr>
                    <td class="col-1" onclick="redirectToDetailShow({{$detail->id}},'mus')">
                        {{$key+1}}
                    </th>
                    <td class="col-9" onclick="redirectToDetailShow({{$detail->id}},'mus')">
                        {{Str::limit($detail->name, 30, '...')}}
                    </td>
                    <td class="col-1" favorite-id="{{ $detail->id }}">
                        @if($detail->fav_flag)
                            <i id="favoriteIcon-mus-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('mus', {{ $detail->id }})"></i>
                        @else
                            <i id="favoriteIcon-mus-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('mus', {{ $detail->id }})"></i>
                        @endif
                    </td>
                    <td class="col-1">
                        <i class="fa-regular fa-square-plus icon-20 red"></i>
                    </td>
                </tr>
            @endforeach
        @endif

        <?//アルバム　　　アイコンの表示非表示を切り替える?>
        @if(isset($album_table))
            @foreach ($album_table as $key => $detail)   
                <tr>
                    <td class="col-1" onclick="redirectToDetailShow({{$detail->id}},'alb')">
                        {{$key+1}}
                    </th>
                    <td class="col-9" onclick="redirectToDetailShow({{$detail->id}},'alb')">
                        {{Str::limit($detail->name, 30, '...')}}
                    </td>
                    <td class="col-1" favorite-id="{{ $detail->id }}">
                        @if($detail->fav_flag)
                            <i id="favoriteIcon-alb-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('alb',{{ $detail->id }})"></i>
                        @else
                            <i id="favoriteIcon-alb-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('alb',{{ $detail->id }})"></i>
                        @endif
                    </td>
                    <td class="col-1">
                        <i class="fa-regular fa-square-plus icon-20 red"></i>
                    </td>
                </tr>
            @endforeach
        @endif

        <?//プレイリスト　　　アイコンの表示非表示を切り替える?>
        @if(isset($playlist_table))
            @foreach ($playlist_table as $key => $detail)   
                <tr>
                    <td class="col-1" onclick="redirectToDetailShow({{$detail->id}},'pl')">
                        {{$key+1}}
                    </th>
                    <td class="col-9" onclick="redirectToDetailShow({{$detail->id}},'pl')">
                        {{Str::limit($detail->name, 30, '...')}}
                    </td>
                    <td class="col-1" favorite-id="{{ $detail->id }}">
                        @if($detail->fav_flag)
                            <i id="favoriteIcon-pl-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('pl', {{ $detail->id }})"></i>
                        @else
                            <i id="favoriteIcon-pl-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('pl', {{ $detail->id }})"></i>
                        @endif
                    </td>
                    <td class="col-1">
                        <i class="fa-regular fa-square-plus icon-20 red"></i>
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
                setFavoriteActions('{{ $recommnd_table->table }}', {{ $detail->detail_id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>
        <?php if(isset($music_table)){ ?>
            // お気に入り状態初期値を定義
            <?php foreach ($music_table as $detail): ?>
                setFavoriteActions("mus",{{ $detail->id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>
        <?php if(isset($album_table)){ ?>
            // お気に入り状態初期値を定義
            <?php foreach ($album_table as $detail): ?>
                setFavoriteActions("alb",{{ $detail->id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>
        <?php if(isset($playlist_table)){ ?>
            // お気に入り状態初期値を定義
            <?php foreach ($playlist_table as $detail): ?>
                setFavoriteActions("pl",{{ $detail->id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>

    });

    function redirectToDetailShow(detail_id,table) {
        switch(table){
            case "mus":
                window.location.href = "{{ route('music-show') }}?id=" + detail_id;
                break;
            case 1:
                break;
            case "alb":
                window.location.href = "{{ route('album-show') }}?id=" + detail_id;
                break;
            case "pl":
                window.location.href = "{{ route('playlist-show') }}?id=" + detail_id;
                break;
            default:
                break;
        }
    }

</script>