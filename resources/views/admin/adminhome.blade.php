
<link rel="stylesheet" href="{{ asset('/css/style.css') }}">
@extends('admin.app')

@section('content')
<div class="container-fluid" style="width: 100%;">
    <div class="row">
        {{-- メニュー選択したタブによって切り替え --}}
        <div class="col-2">
            @include('admin.admin_menu_left')
        </div>
        <!--メイン-->
        <div class="col-10">
            <div class="rounded border p-3">
                @include('admin.admin_menu_right')
            </div>
        </div>
    </div>
</div>
@endsection
