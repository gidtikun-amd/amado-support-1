@extends('layout.template') {{-- Template --}}
@section('title','จักการเพจและเมนู') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">การกำหนดตัวตนและสิทธิ์</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-role-page-menu>
    </it-role-page-menu>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
