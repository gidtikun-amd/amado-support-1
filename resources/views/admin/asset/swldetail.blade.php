@extends('layout.template') {{-- Template --}}
@section('title','รายละเอียด S/W License') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">ทรัพย์สิน</li>
    <li class="breadcrumb-item">Software license control</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-asset-swldetail id="{!!$id!!}">
    </it-asset-swldetail>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection