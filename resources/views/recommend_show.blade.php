@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
<div class="py-2 d-flex justify-content-center">
    <p class="card-text">{{ $recommend->name }}</p>

</div>
    
<?//テーブルリストは別ファイルで管理?>   
@include('layouts.list_table', ['recommend_table' => $recommend])


{{--ﾊﾟﾗﾒｰﾀ--}}
@php
    $additionalParams = [
        'id' => $recommend->id ?? '',
        'category' => $recommend->category ?? '',
    ];
@endphp
{{--ﾍﾟｰｼﾞｬｰ--}}
@include('layouts.pagination', ['paginator' => $recommend->detail,'additionalParams' => $additionalParams,])

@endsection


<script>
</script>