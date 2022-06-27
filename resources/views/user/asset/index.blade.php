@extends('layout.template') {{-- Template --}}
@section('title','การถือครองทรัพย์สิน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-asset-index>
    </user-asset-index>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection