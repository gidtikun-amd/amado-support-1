@extends('layout.template') {{-- Template --}}
@section('title','ส่งคำขอ Job Request') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">Job Request</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-jobreq-create>
    </user-jobreq-create>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
