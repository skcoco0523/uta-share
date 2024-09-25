
<!--<link rel="stylesheet" href="{{ asset('/css/style.css') }}">-->
@extends('admin.app')

@section('content')

@php
    //メニュー切り替え
    $segments = request()->segments();

    // `tab` パラメータを取得（クエリパラメータとして）
    $tab = request()->query('tab');   

    // `tab` パラメータが存在しない場合に URL のセグメントを取得
    if (!$tab) $tab = $segments[1] ?? null;

    //admin_home_right.blade.php　で表示する情報
    $method = $segments[2] ?? null;

@endphp

<div class="container-fluid" style="width: 100%;">
    <div class="row">
        {{-- メニュー選択したタブによって切り替え --}}
        <div class="col-12 col-md-2">
            <div class="rounded border p-3">
                @include('admin.admin_home_left1')
            </div>
            <div class="rounded border p-3">
                @include('admin.admin_home_left2')
            </div>
        </div>
        <!--メイン-->
        <div class="col-12 col-md-10">
            <div class="rounded border p-3">
                @include('admin.admin_home_right')
            </div>
        </div>
    </div>
</div>
@endsection
