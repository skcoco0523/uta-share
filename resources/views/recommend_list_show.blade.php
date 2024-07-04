@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="py-2 d-flex justify-content-center">
    <p class="card-text">{{ $recommend_list->name }}</p>
</div>

<?//テーブルリストは別ファイルで管理?>   
@include('layouts.list_table', ['recommend_list_table' => $recommend_list])


{{--ﾊﾟﾗﾒｰﾀ--}}
@php
    $additionalParams = [
    ];
@endphp
{{--ﾍﾟｰｼﾞｬｰ--}}
@include('layouts.pagination', ['paginator' => $recommend_list,'additionalParams' => $additionalParams,])

@endsection


<script>
</script>