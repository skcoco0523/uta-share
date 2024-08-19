{{--検索--}}
<form method="GET" action="{{ route('admin-playlist-search') }}">

    検索条件
    <div class="row g-3 align-items-end">
    <div class="col-sm-12">
        <input type="text" id="keyword" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="検索(ﾌﾟﾚｲﾘｽﾄ名)">
    </div>
    <div class="col-md-12">
        <select id="inputState" name="admin_flag" class="form-select">
            <option value="1" {{ ($input['admin_flag'] ?? '') == '1' ? 'selected' : '' }}>管理者</option>
            <option value="0" {{ ($input['admin_flag'] ?? '') == '0' ? 'selected' : '' }}>ユーザー</option>
        </select>
    </div>
    <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-success">検索</button>
    </div>
</div>
</form>