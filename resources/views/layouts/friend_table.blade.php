
<table class="table table-borderless table-center">
    <tbody>

        @if(isset($search_user_table) && isset($status))
            <td class="col-8">
                {{Str::limit($search_user_table->name, 30, '...')}}
            </td>
            <td class="col-4">
                @if ($status == 'pending')
                    <a class="btn btn-gray" onclick="friend_reqest({{ $search_user_table->id }}, '{{ route('friend-cancel') }}', 'cancel')">
                        キャンセル</a>
                @elseif ($status == 'request')
                <div class="button-group">
                    <a class="btn btn-blue" onclick="friend_reqest({{ $search_user_table->id }}, '{{ route('friend-accept') }}', 'accept')">承諾</a>
                    <a class="btn btn-red" onclick="friend_reqest({{ $search_user_table->id }}, '{{ route('friend-decline') }}', 'decline')">拒否</a>
                </div>
                @elseif ($status == 'declined')
                <div class="button-group">
                    <a class="btn btn-blue" onclick="friend_reqest({{ $search_user_table->id }}, '{{ route('friend-accept') }}', 'accept')">承諾</a>
                    <a class="btn btn-red" onclick="friend_reqest({{ $search_user_table->id }}, '{{ route('friend-cancel') }}', 'del')">削除</a>
                </div>
                @elseif ($status == 'accepted')
                    登録済み
                @else
                    <a class="btn btn-blue" onclick="friend_reqest({{ $search_user_table->id }}, '{{ route('friend-request') }}', 'request')">
                    申請
                    </a>
                @endif
            </td>
        @endif


        @if(isset($friendlist_table) && isset($status))
            @foreach ($friendlist_table as $key => $friend)   
                <tr>
                    <td class="col-8">
                        {{Str::limit($friend->name, 30, '...')}}
                    </td>
                    <td class="col-4">
                        @if ($status == 'pending')
                            <a class="btn btn-gray" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-cancel') }}', 'cancel')">
                                キャンセル</a>
                            
                        @elseif ($status == 'request')
                        <div class="button-group">
                            <a class="btn btn-blue" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-accept') }}', 'accept')">
                                承諾</a>
                            <a class="btn btn-red" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-decline') }}', 'decline')">
                                拒否</a>
                            </div>
                        @elseif ($status == 'declined')
                        <div class="button-group">
                            <a class="btn btn-blue" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-accept') }}', 'accept')">
                                承諾</a>
                            <a class="btn btn-red" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-cancel') }}', 'del')">
                                削除</a>
                            </div>
                        @elseif ($status == 'accepted')
                            <a class="btn btn-red" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-cancel') }}', 'del')">
                                削除</a>
                        @else
                            <a class="btn btn-blue" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-request') }}', 'request')">
                                申請</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif


    </tbody>
</table>

{{--申請・承認・削除用フォーム--}}
<form name="friend_reqest_form" method="" action="">
    @csrf
    <input type="hidden" name="user_id" value="">
</form>


<script>
    
    function friend_reqest(friend_id,route,method){
        if (method === 'del') {
            var rtn = confirm('フレンドから削除してもよろしいですか？');
            if (rtn === false) return false;
        }
        var trg = document.forms["friend_reqest_form"];
        trg.method="post";
        trg.action = route; // 第2引数のrouteをactionに指定
        trg["user_id"].value = friend_id;

        trg.submit();
    }


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