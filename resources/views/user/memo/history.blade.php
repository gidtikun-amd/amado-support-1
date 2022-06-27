@extends('layout.template') {{-- Template --}}
@section('title','ประวัติคำขอ Memo') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">Memo</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-memo-history>
    </user-memo-history>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection