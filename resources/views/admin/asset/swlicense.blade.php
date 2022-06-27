@extends('layout.template') {{-- Template --}}
@section('title','Software license control') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">ทรัพย์สิน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-asset-swlicense>
    </it-asset-swlicense>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection