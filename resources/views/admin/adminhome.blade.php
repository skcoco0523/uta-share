
<link rel="stylesheet" href="{{ asset('/css/style.css') }}">
@extends('admin.app')

@section('content')
<div class="container">
    <div class="row">
        {{-- メニュー選択したタブによって切り替え --}}
        <div class="col-md-2">
            @include('admin.admin_menu_left')
        </div>
        <!--メイン-->
        <div class="col-md-8">
            <div class="rounded border p-3">
                @include('admin.admin_menu_right')
            </div>
        </div>
    </div>
</div>
@endsection
