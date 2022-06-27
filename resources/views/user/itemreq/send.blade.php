@extends('layout.template') {{-- Template --}}
@section('title','ส่งคำขอ Item Request') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">Item Request</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>

<div id="app">
    <user-itemreq-create>
    </user-itemreq-create>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
