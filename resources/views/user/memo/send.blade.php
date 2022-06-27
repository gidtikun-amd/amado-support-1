@extends('layout.template') {{-- Template --}}
@section('title','ส่งคำขอ Memo') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">Memo</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-memo-create>
    </user-memo-create>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection