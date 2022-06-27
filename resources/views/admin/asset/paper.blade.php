@extends('layout.template') {{-- Template --}}
@section('title','ค้นหาเอกสารทรัพย์สิน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">ทรัพย์สิน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-asset-paper>
    </it-asset-paper>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection