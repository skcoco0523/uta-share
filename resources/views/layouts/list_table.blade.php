

<?//一覧テーブル?>
@if(isset($recommend_list_table))
                            <table id="recommend-list" class="table table-borderless table-center">
                                <tbody>
    @foreach ($recommend_list_table as $key => $detail)
                                    <tr>
                                        <td class="col-2" onclick="redirectToDetailShow({{ $detail->id }}, 'recom')">
        @if(isset($detail->detail[0]->src)) <img src="{{ $detail->detail[0]->src }}" class="icon-55">
        @elseif(isset($detail->detail[0]->music[0]->src))
                                            <img src="{{ $detail->detail[0]->music[0]->src }}" class="icon-55">
        @else                               <p style="margin: 0 auto; text-align: center;">{{ $key + 1 }}</p>
        @endif
                                        </td>
                                        <td class="col-9" onclick="redirectToDetailShow({{ $detail->id }}, 'recom')">
                                            {{ Str::limit($detail->name, 30, '...') }}
                                        </td>
                                    </tr>
    @endforeach
                                </tbody>
                            </table>
@endif

<?//おすすめテーブル?>
@if(isset($recommend_table))
                                                    
                            <table id="recommend-list" class="table table-borderless table-center">
                                <tbody>
    @foreach ($recommend_table as $key => $detail)   
                                    <tr>
                                        <td class="col-2" onclick="redirectToDetailShow({{ $detail->id }}, '{{ $recommend->table }}')">
        @if(isset($detail->src))            <img src="{{ $detail->src }}" class="icon-55">
        @else                               <p style="margin: 0 auto; text-align: center;">{{ $key + 1 }}</p>
        @endif
                                        </td>
                                        <td class="col-9" onclick="redirectToDetailShow({{ $detail->id }}, '{{ $recommend->table }}')">
                                            {{ Str::limit($detail->name, 30, '...') }}
        @if(isset($detail->art_name))   <br><p class="sub-title">{{Str::limit($detail->art_name, 30, '...')}}</p>
        @endif
                                        </td>
                                        <td class="col-1" favorite-id="{{ $recommend->table }}-{{ $detail->id }}">
        @if($detail->fav_flag)              <i data-favorite-id="{{ $recommend->table }}-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('{{ $recommend->table }}', {{ $detail->id }})"></i>
        @else                               <i data-favorite-id="{{ $recommend->table }}-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('{{ $recommend->table }}', {{ $detail->id }})"></i>
        @endif
                                        </td>
                                        <td class="col-1" pl-menu-id="{{ $recommend->table }}-{{ $detail->id }}">
                                            <i class="fa-regular fa-square-plus icon-20 red"></i>
                                        </td>
                                    </tr>
    @endforeach
                                </tbody>
                            </table>
@endif

<?//曲　アルバム　プレイリスト?>
@if(isset($detail_table) && isset($table))
                        <table class="table table-borderless table-center">
                            <tbody>
    @foreach ($detail_table as $key => $detail)         
                                <tr>
                                    <td class="col-2" onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
        @if(isset($detail->src))        <img src="{{ $detail->src }}" class="icon-55">
        @elseif(isset($detail->src))         
                                        <img src="{{ $detail->src }}" class="icon-55">
        @else                           <p style="margin: 0 auto; text-align: center;">{{ $key + 1 }}</p>
        @endif
                                    </th>
                                    <td class="col-9" onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
                                        {{Str::limit($detail->name, 30, '...')}}
        @if(isset($detail->art_name))   <br><p class="sub-title">{{Str::limit($detail->art_name, 30, '...')}}</p>
        @endif
                                    </td>
                                    <td class="col-1" favorite-id="{{ $table }}-{{ $detail->id }}">
        @if($detail->fav_flag)          <i data-favorite-id="{{ $table }}-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail->id }})"></i>
        @else                           <i data-favorite-id="{{ $table }}-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail->id }})"></i>
        @endif
                                    </td>
                                    <td class="col-1" pl-menu-id="{{ $table }}-{{ $detail->id }}">
                                        <i class="fa-regular fa-square-plus icon-20 red"></i>
                                    </td>
                                </tr>
    @endforeach
                            </tbody>
                        </table>
@endif


<?//フレンド情報表示用?>
@if(isset($friend_table) && isset($table))
                        <table class="table table-borderless table-center">
                            <tbody>
    @foreach ($friend_table as $key => $detail)   
                                <tr>
                                    <td class="col-2" onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
    @if(isset($detail->src))            <img src="{{ $detail->src }}" class="icon-55">
    @else                               <p style="margin: 0 auto; text-align: center;">{{ $key + 1 }}</p>
    @endif
                                    </th>
                                    <td class="col-9" onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
                                        {{Str::limit($detail->name, 30, '...')}}
    @if(isset($detail->art_name))       <br><p class="sub-title">{{Str::limit($detail->art_name, 30, '...')}}</p>
    @endif
                                    </td>
                                </tr>
    @endforeach
                            </tbody>
                        </table>
@endif






<script>
    
    document.addEventListener('DOMContentLoaded', function() {
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
        let pl_table = document.querySelectorAll("[pl-menu-id]");
        pl_table.forEach(table => {
            let tableType = table.getAttribute("pl-menu-id").split("-")[0];
            if (tableType !== 'mus') {
                table.style.display = 'none';
            }
        });
        // artはメニューなし
        let fav_table = document.querySelectorAll("[favorite-id]");
        fav_table.forEach(table => {
            let tableType = table.getAttribute("favorite-id").split("-")[0];
            if (tableType === 'art') {
                console.log("Hiding element:", table); // デバッグ用にログを出力
                table.style.display = 'none';
            }
        });

    });

    function redirectToDetailShow(detail_id,table) {
        switch(table){
            case "art":
                window.location.href = "{{ route('artist-show') }}?id=" + detail_id;
                break;
            case "mus":
                window.location.href = "{{ route('music-show') }}?id=" + detail_id;
                break;
            case "alb":
                window.location.href = "{{ route('album-show') }}?id=" + detail_id;
                break;
            case "pl":
                window.location.href = "{{ route('playlist-show') }}?id=" + detail_id;
                break;
            case "recom":
                window.location.href = "{{ route('recommend-show') }}?id=" + detail_id;
                break;
            default:
                break;
        }
    }

</script>