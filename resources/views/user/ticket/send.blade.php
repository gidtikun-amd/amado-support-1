@extends('layout.template') {{-- Template --}}
@section('title','สร้างคำขอ Ticket') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">Ticket</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-ticket-create></user-ticket-create>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
