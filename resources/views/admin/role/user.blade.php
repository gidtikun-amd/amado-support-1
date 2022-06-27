@extends('layout.template') {{-- Template --}}
@section('title','กำหนดสิทธิ์ผู้ใช้') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">การกำหนดตัวตนและสิทธิ์</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-role-user>
    </it-role-user>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
