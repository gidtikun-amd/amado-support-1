@extends('layout.template') {{-- Template --}}
@section('title','ประเมินความพึงพอใจ') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">Ticket</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-ticket-survey>
    </user-ticket-survey>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>











@endsection
