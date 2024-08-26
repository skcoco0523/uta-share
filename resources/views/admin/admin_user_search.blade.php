
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
        $page_prm = $input ?? '';
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $user_list,'page_prm' => $page_prm,])
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6">
            <thead>
            <tr>
                <th scope="col" class="fw-light">ID</th>
                <th scope="col" class="fw-light">ﾕｰｻﾞｰ名</th>
                <th scope="col" class="fw-light">ｱﾄﾞﾚｽ</th>
                <th scope="col" class="fw-light">ﾌﾚﾝﾄﾞｺｰﾄﾞ</th>
                <th scope="col" class="fw-light">誕生日</th>
                <th scope="col" class="fw-light">性別</th>
                <th scope="col" class="fw-light">公開</th>
                <th scope="col" class="fw-light">ﾒｰﾙ送信</th>
                <th scope="col" class="fw-light">ﾛｸﾞｲﾝ数</th>
                <th scope="col" class="fw-light">お気に入り数</th>
                <th scope="col" class="fw-light">ﾌﾚﾝﾄﾞ数</th>
                <th scope="col" class="fw-light">ﾌﾟﾚｲﾘｽﾄ数</th>
                <th scope="col" class="fw-light">最終ﾛｸﾞｲﾝ日</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
            </tr>
            </thead>
            @foreach($user_list as $user)
                <tr>
                <td class="fw-light">{{$user->id}}</td>
                    <td class="fw-light">{{$user->name}}</td>
                    <td class="fw-light">{{$user->email}}</td>
                    <td class="fw-light">{{$user->friend_code}}</td>
                    <td class="fw-light">{{$user->birthdate}}
                    <td class="fw-light">{{$user->gender == '0' ? '男性' : '女性' }}</td>
                    <td class="fw-light">{{$user->release_flag == '0' ? '許可' : '拒否' }}</td>
                    <td class="fw-light">{{$user->mail_flag == '0' ? '許可' : '拒否' }}</td>
                    <td class="fw-light">{{$user->login_cnt}}</td>
                    <td class="fw-light">{{$user->favorite_cnt}}</td>
                    <td class="fw-light">{{$user->friend_cnt}}</td>
                    <td class="fw-light">{{$user->playlist_cnt}}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $user->last_login_date) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $user->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $user->updated_at) !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $user_list,'page_prm' => $page_prm,])

@endif



<script>
    document.addEventListener('DOMContentLoaded', function() {
    });
</script>