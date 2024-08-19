
{{--エラー--}}
@if(isset($msg))
    <div class="alert alert-danger">
        {!! nl2br(e($msg)) !!}
    </div>
@endif


{{--ユーザー一覧--}}
@if(isset($user_list))
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['keyword' => $input['keyword'] ?? '',];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $user_list,'additionalParams' => $additionalParams,])
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6">
            <thead>
            <tr>
                <th scope="col" class="fw-light">ID</th>
                <th scope="col" class="fw-light">ﾕｰｻﾞｰ名</th>
                <th scope="col" class="fw-light">ｱﾄﾞﾚｽ</th>
                <th scope="col" class="fw-light">ﾌﾚﾝﾄﾞｺｰﾄﾞ</th>
                <th scope="col" class="fw-light">性別</th>
                <th scope="col" class="fw-light">誕生日</th>
                <th scope="col" class="fw-light">公開状態</th>
                <th scope="col" class="fw-light">ﾒｰﾙ送信ﾌﾗｸﾞ</th>
                <th scope="col" class="fw-light">お気に入り数</th>
                <th scope="col" class="fw-light">ﾌﾚﾝﾄﾞ数</th>
                <th scope="col" class="fw-light">ﾌﾟﾚｲﾘｽﾄ数</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
                <th scope="col" class="fw-light"></th>
            </tr>
            </thead>
            @foreach($user_list as $user)
                <tr>
                <td class="fw-light">{{$user->id}}</td>
                    <td class="fw-light">
                        <input id="name-{{$user->id}}" type="text" name="name" class="form-control" placeholder="name" value="{{$user->name}}">
                    </td>
                    <td class="fw-light">
                        <input id="email-{{$user->id}}" type="email" name="email" class="form-control" placeholder="email" value="{{$user->email}}">
                    </td>
                    <td class="fw-light">{{$user->friend_code}}</td>
                    <td class="fw-light"> 
                        <select id="gender-{{$user->id}}" name="gender" class="form-select">
                            <option value="0" {{ $user->gender == '0' ? 'selected' : '' }}>男性</option>
                            <option value="1" {{ $user->gender == '1' ? 'selected' : '' }}>女性</option>
                        </select>
                    </td>
                    <td class="fw-light">{{$user->birthdate}}
                        
                    <td class="fw-light"> 
                        <select id="release_flag-{{$user->id}}" name="release_flag" class="form-select">
                            <option value="0" {{ $user->release_flag == '0' ? 'selected' : '' }}>公開</option>
                            <option value="1" {{ $user->release_flag == '1' ? 'selected' : '' }}>非公開</option>
                        </select>
                    </td>
                    <td class="fw-light"> 
                        <select id="mail_flag-{{$user->id}}" name="mail_flag" class="form-select">
                            <option value="0" {{ $user->mail_flag == '0' ? 'selected' : '' }}>許可</option>
                            <option value="1" {{ $user->mail_flag == '1' ? 'selected' : '' }}>拒否</option>
                        </select>
                    </td>
                    <td class="fw-light">{{$user->favorite_cnt}}</td>
                    <td class="fw-light">{{$user->friend_cnt}}</td>
                    <td class="fw-light">{{$user->playlist_cnt}}</td>
                    <td class="fw-light">{{$user->created_at}}</td>
                    <td class="fw-light">{{$user->updated_at}}</td>
                    <td class="fw-light">
                        <input type="button" class="btn btn-primary" value="更新" onclick="user_chg_fnc('{{$user->id}}');" >
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $user_list,'additionalParams' => $additionalParams,])


    {{--更新--}}
    <form name="user_chg_form" method="POST" action="{{ route('admin-user-change') }}">
        @csrf
        <input type="hidden" name="id" value="">
        <input type="hidden" name="name" value="">
        <input type="hidden" name="disp_flag" value="">
    </form>
@endif



<script>
    //ユーザー情報更新
    function user_chg_fnc(id){
        var trg = document.forms["user_chg_form"];

        trg.method="post";
        document.user_chg_form["id"].value  = id;

        after_name = document.getElementById("name-" + id).value;
        //after_email = document.getElementById("email-" + id).value;

        document.user_chg_form["name"].value = after_name;
        //document.user_chg_form["email"].value = after_email;
        trg.submit();
    }


    document.addEventListener('DOMContentLoaded', function() {
    });
</script>