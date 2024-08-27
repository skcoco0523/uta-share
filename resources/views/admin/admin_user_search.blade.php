
{{-- アーティスト情報更新処理 --}}
<form id="user_chg_form" method="POST" action="{{ route('admin-user-chg') }}">
    @csrf
    {{--検索条件--}}
    <input type="hidden" name="search_name" value="{{$input['search_name'] ?? ''}}">
    <input type="hidden" name="search_email" value="{{$input['search_email'] ?? ''}}">
    <input type="hidden" name="search_friendcode" value="{{$input['search_friendcode'] ?? ''}}">
    <input type="hidden" name="search_gender" value="{{$input['search_gender'] ?? ''}}">
    <input type="hidden" name="search_release_flag" value="{{$input['search_release_flag'] ?? ''}}">
    <input type="hidden" name="search_mail_flag" value="{{$input['search_mail_flag'] ?? ''}}">
    <input type="hidden" name="search_admin_flag" value="{{$input['search_admin_flag'] ?? ''}}">
    <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
    {{--対象データ--}}
    <input type="hidden" name="id" value="{{$select->id ?? ''}}">
    
    <div class="row g-3 align-items-start" >
        <div class="col-sm-6 col-lg-4">
            <label for="inputname" class="form-label">ﾕｰｻﾞｰ名</label>
            <input type="text" name="name" class="form-control" placeholder="name" value="{{$select->name ?? ''}}">
        </div>
        <div class="col-sm-6 col-lg-4">
            <label for="" class="form-label">ｱﾄﾞﾚｽ</label>
            <input type="email" name="email" class="form-control" placeholder="XXX@gmail.com" value="{{$select->email ?? ''}}">
        </div>
        <div class="col-md-6 col-lg-4">
            <label for="inputbirth" class="form-label">誕生日</label>
            <input type="date" max="9999-12-31" name="birthdate" class="form-control" value="{{$select->birthdate ?? ''}}">
        </div>
    </div>
    <div class="row g-3 align-items-end" >
        <div class="col-4">
            <label for="inputsex" class="form-label">性別</label>
            <select name="gender" class="form-select">
                <option value="0" {{ ($select->gender ?? '') == '0' ? 'selected' : '' }}>男性</option>
                <option value="1" {{ ($select->gender ?? '') == '1' ? 'selected' : '' }}>女性</option>
            </select>
        </div>
        <div class="col-4">
            <label for="inputsex" class="form-label">公開</label>
            <select name="release_flag" class="form-select">
                <option value="0" {{ ($select->release_flag ?? '') == '0' ? 'selected' : '' }}>許可</option>
                <option value="1" {{ ($select->release_flag ?? '') == '1' ? 'selected' : '' }}>拒否</option>
            </select>
        </div>
        <div class="col-4">
            <label for="inputsex" class="form-label">性別</label>
            <select name="mail_flag" class="form-select">
                <option value="0" {{ ($select->mail_flag ?? '') == '0' ? 'selected' : '' }}>許可</option>
                <option value="1" {{ ($select->mail_flag ?? '') == '1' ? 'selected' : '' }}>拒否</option>
            </select>
        </div>
    </div>
    <div class="mt-3 text-end">
        <input type="submit" value="更新" class="btn btn-primary">
    </div>
</form>

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
                    <td class="fw-light">{{$user->birthdate}}</td>
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

    const form = document.getElementById('user_chg_form');
    //更新フォームを非表示
    form.style.display = 'none';

    // テーブルの各行にクリックイベントリスナーを追加
    document.querySelectorAll('table tr').forEach(row => {
        row.addEventListener('click', () => {
            //更新フォームを表示
            form.style.display = 'block';
            // クリックされた行からデータを取得
            const cells         = row.querySelectorAll('td');
            const id            = cells[0].textContent;
            const name          = cells[1].textContent;
            const email         = cells[2].textContent;
            const birthdate     = cells[4].textContent;

            // フォームの対応するフィールドにデータを設定
            document.querySelector('input[name="id"]').value = id;
            document.querySelector('input[name="name"]').value = name;
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="birthdate"]').value = birthdate;

            // 性別の選択肢を設定
            const gender = document.querySelector('select[name="gender"]');
            gender.value = (cells[5].textContent.trim() === '男性') ? '0' : '1';

            // リリースフラグの選択肢を設定
            const release_flag = document.querySelector('select[name="release_flag"]');
            release_flag.value = (cells[6].textContent.trim() === '許可') ? '0' : '1';

            // メールフラグの選択肢を設定
            const mail_flag = document.querySelector('select[name="mail_flag"]');
            mail_flag.value = (cells[7].textContent.trim() === '許可') ? '0' : '1';

        });
    });
});
</script>