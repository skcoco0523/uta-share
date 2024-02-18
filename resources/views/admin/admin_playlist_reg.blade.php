{{-- プレイリスト登録処理 --}}
<form method="POST" action="{{ route('admin-playlist-reg') }}">
    @csrf
    <div class="row g-3 align-items-end" >
        <div class="col-sm">
            <label for="inputname" class="form-label">ﾌﾟﾚｲﾘｽﾄ名</label>
            <input type="text" name="name" class="form-control" placeholder="name" value="{{$input['name'] ?? ''}}">
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="admin_flg" value="1">
        </div>
        <div class="col-md-2">
        <input type="submit" value="登録" class="btn btn-primary">
    </div>
    </div>
</form>

{{--エラー--}}
@if(isset($msg))
    <div class="alert alert-danger">
        {!! nl2br(e($msg)) !!}
    </div>
@endif

{{--プレイリスト登録履歴--}}
@if(isset($playlist))
    <table class="table table-striped table-hover table-bordered fs-6 ">
        <thead>
        <tr>
            <th scope="col" class="fw-light">ﾌﾟﾚｲﾘｽﾄ名</th>
            <th scope="col" class="fw-light">登録者</th>
            <th scope="col" class="fw-light">登録曲数</th>
            <th scope="col" class="fw-light">データ登録日</th>
            <th scope="col" class="fw-light">データ更新日</th>
        </tr>
        </thead>
        @foreach($playlist as $pl)
            <tr>
                <td class="fw-light">{{$pl->name}}</td>
                <td class="fw-light">{{$pl->user_name}}</td>
                <td class="fw-light">{{$pl->mus_cnt}}</td>
                <td class="fw-light">{{$pl->created_at}}</td>
                <td class="fw-light">{{$pl->updated_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
