{{--検索--}}
<form method="GET" action="{{ route('admin-user-search') }}">

    検索条件
    <div class="row g-3 align-items-end">
    <div class="col-sm-12">
        <input type="text" id="search_name" name="search_name" class="form-control" value="{{$input['search_name'] ?? ''}}" placeholder="ユーザー名">
    </div>
    <div class="col-sm-12">
        <input type="text" id="search_email" name="search_email" class="form-control" value="{{$input['search_email'] ?? ''}}" placeholder="アドレス">
    </div>
    <div class="col-sm-12">
        <input type="text" id="search_friendcode" name="search_friendcode" class="form-control" value="{{$input['search_friendcode'] ?? ''}}" placeholder="ﾌﾚﾝﾄﾞｺｰﾄﾞ">
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