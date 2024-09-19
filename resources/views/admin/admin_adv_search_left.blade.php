{{--検索--}}
<form method="GET" action="{{ route('admin-adv-search') }}">

    検索条件
    <div class="row g-3 align-items-end">
        <div class="col-sm-12">
            ・広告名
            <input type="text" name="search_adv" class="form-control" value="{{$input['search_adv'] ?? ''}}">
        </div>
        
        
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success">検索</button>
        </div>
    </div>
</form>
