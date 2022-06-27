@extends('layout.template') {{-- Template --}}
@section('title','ประวัติคำขอ Job Request') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">งานธุรการ</li>
    <li class="breadcrumb-item">Job Request</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-ad-jobreq-history>
    </user-ad-jobreq-history>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
