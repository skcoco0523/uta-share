@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
<div class="py-2 d-flex justify-content-center">
    <p class="card-text">{{ $recommnd->name }}</p>

</div>
    
<?//テーブルリストは別ファイルで管理?>   
@include('layouts.list_table', ['recommnd_table' => $recommnd])


{{--ﾊﾟﾗﾒｰﾀ--}}
@php
    $additionalParams = [
        'recom_id' => $recommnd->id ?? '',
        'category' => $recommnd->category ?? '',
    ];
@endphp
{{--ﾍﾟｰｼﾞｬｰ--}}
@include('layouts.pagination', ['paginator' => $recommnd->detail,'additionalParams' => $additionalParams,])

@endsection


<script>
</script>