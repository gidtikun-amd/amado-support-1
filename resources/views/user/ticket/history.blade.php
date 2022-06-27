@extends('layout.template') {{-- Template --}}
@section('title','ประวัติคำขอ Ticket') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">Ticket</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-ticket-history></user-ticket-history>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
