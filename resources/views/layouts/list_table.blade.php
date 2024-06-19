
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
                    <td class="col-1" favorite-id="{{ $recommnd->table }}-{{ $detail->detail_id }}">
                        @if($detail->fav_flag)
                            <i data-favorite-id="{{ $recommnd->table }}-{{ $detail->detail_id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('{{ $recommnd->table }}', {{ $detail->detail_id }})"></i>
                        @else
                            <i data-favorite-id="{{ $recommnd->table }}-{{ $detail->detail_id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('{{ $recommnd->table }}', {{ $detail->detail_id }})"></i>
                        @endif
                    </td>
                    <td class="col-1 mus_only">
                        <i class="fa-regular fa-square-plus icon-20 red"></i>
                    </td>
                </tr>
            @endforeach
        @endif

        <?//曲　アルバム　プレイリスト?>
        @if(isset($detail_table) && isset($table))
            @foreach ($detail_table as $key => $detail)   
                <tr>
                    <td class="col-1" onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
                        {{$key+1}}
                    </th>
                    <td class="col-9" onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
                        {{Str::limit($detail->name, 30, '...')}}
                    </td>
                    <td class="col-1" favorite-id="{{ $table }}-{{ $detail->id }}">
                        @if($detail->fav_flag)
                            <i data-favorite-id="{{ $table }}-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail->id }})"></i>
                        @else
                            <i data-favorite-id="{{ $table }}-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail->id }})"></i>
                        @endif
                    </td>
                    <td class="col-1" mus-only-id="{{ $table }}-{{ $detail->id }}">
                        <i class="fa-regular fa-square-plus icon-20 red"></i>
                    </td>
                </tr>
            @endforeach
        @endif


    </tbody>
</table>

<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        const allMusOnlyElements = document.querySelectorAll('.mus_only');
        // お気に入り状態初期値を定義
        <?php if(isset($recommnd_table)){ ?>
            <?php foreach ($recommnd_table->detail as $detail): ?>
                setFavoriteActions('{{ $recommnd_table->table }}', {{ $detail->detail_id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>

        <?php if(isset($detail_table)){ ?>
            <?php foreach ($detail_table as $detail): ?>
                setFavoriteActions('{{$table}}',{{ $detail->id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>
        
        // mus以外はプレイリストメニューは無し
        let tables = document.querySelectorAll("[mus-only-id]");
        tables.forEach(table => {
            let tableType = table.getAttribute("mus-only-id").split("-")[0];
            if (tableType !== 'mus') {
                table.style.display = 'none';
            }
        });

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