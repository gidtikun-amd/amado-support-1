@extends('layout.template') {{-- Template --}}
@section('title','จัดการข้อมูลประเภททรัพย์สิน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">ข้อมูลหลัก</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-assettype-index>
    </it-assettype-index>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection