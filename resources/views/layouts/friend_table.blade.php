
<table class="table table-borderless table-center">
    <tbody>

        @if(isset($search_user_table) && isset($status))
            <td class="col-9">
                {{Str::limit($search_user_table->name, 30, '...')}}
            </td>
            <td class="col-3">
                @if ($status == 'pending')
                    <form action="{{ route('friend-cancel') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$search_user_table->id}}">
                        <button type="submit" class="btn btn-secondary">キャンセル</button>
                    </form>
                @elseif ($status == 'request')
                    <form action="{{ route('friend-accept') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$search_user_table->id}}">
                        <button type="submit" class="btn btn-primary">承諾</button>
                    </form>
                    <form action="{{ route('friend-decline') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$search_user_table->id}}">
                        <button type="submit" class="btn btn-danger">拒否</button>
                    </form>
                @elseif ($status == 'declined')
                    拒否済み
                    <form action="{{ route('friend-accept') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$search_user_table->id}}">
                        <button type="submit" class="btn btn-primary">承諾</button>
                    </form>
                    <form action="{{ route('friend-cancel') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$search_user_table->id}}">
                        <button type="submit" class="btn btn-secondary">削除</button>
                    </form>
                @elseif ($status == 'accepted')
                    フレンド
                    <form action="{{ route('friend-cancel') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$search_user_table->id}}">
                        <button type="submit" class="btn btn-secondary">削除</button>
                    </form>
                @else
                    <form action="{{ route('friend-request') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$search_user_table->id}}">
                        <button type="submit" class="btn btn-primary">フレンド申請</button>
                    </form>
                @endif
            </td>
        @endif


        @if(isset($friendlist_table) && isset($status))
            @foreach ($friendlist_table as $key => $friend)   
                <tr>
                    <td class="col-9">
                        {{Str::limit($friend->name, 30, '...')}}
                    </td>
                    <td class="col-3">
                        @if ($status == 'pending')
                            <form action="{{ route('friend-cancel') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$friend->id}}">
                                <button type="submit" class="btn btn-secondary">キャンセル</button>
                            </form>
                        @elseif ($status == 'request')
                            <form action="{{ route('friend-accept') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$friend->id}}">
                                <button type="submit" class="btn btn-primary">承諾</button>
                            </form>
                            <form action="{{ route('friend-decline') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$friend->id}}">
                                <button type="submit" class="btn btn-danger">拒否</button>
                            </form>
                        @elseif ($status == 'declined')
                            拒否済み
                            <form action="{{ route('friend-accept') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$friend->id}}">
                                <button type="submit" class="btn btn-primary">承諾</button>
                            </form>
                            <form action="{{ route('friend-cancel') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$friend->id}}">
                                <button type="submit" class="btn btn-secondary">削除</button>
                            </form>
                        @elseif ($status == 'accepted')
                            フレンド
                            <form action="{{ route('friend-cancel') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$friend->id}}">
                                <button type="submit" class="btn btn-secondary">削除</button>
                            </form>
                        @else
                            <form action="{{ route('friend-request') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$friend->id}}">
                                <button type="submit" class="btn btn-primary">フレンド申請</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif


    </tbody>
</table>

<script>
    
    document.addEventListener('DOMContentLoaded', function() {


        
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
            default:
                break;
        }
    }

</script>