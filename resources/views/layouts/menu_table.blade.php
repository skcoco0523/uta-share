
<table class="table table-borderless table-center">
    <tbody>
        <tr>
            <?//music playlistメニュー?>
            @if(isset($detail_id) && isset($table))
            <td class="col-1"></td>
            <th class="col-3" onclick="openShareModal('{{ request()->url()}}?id={{ $detail_id }}')">
                <i class="fa-regular fa-share-from-square icon-20 red"></i>
            </th>
            <td class="col-3" favorite-id="{{ $table }}-{{ $detail_id }}">
                @if($fav_flag)
                    <i data-favorite-id="{{ $table }}-{{ $detail_id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail_id }})"></i>
                @else
                    <i data-favorite-id="{{ $table }}-{{ $detail_id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail_id }})"></i>
                @endif
            </td>
            <td class="col-3">
                <i class="fa-regular fa-square-plus icon-20 red"></i>
            </td>
            <td class="col-1"></td>
            @endif
        </tr>
    </tbody>
</table>

 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // お気に入り状態初期値を定義
        <?php if(isset($detail_id) && isset($table)){ ?>
            setFavoriteActions('{{$table}}',{{ $detail_id }}, {{$fav_flag}});
        <?php } ?>
    });

</script>