@extends('layout.template') {{-- Template --}}
@section('title','จัดการอุปกรณ์สำนักงาน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">งานธุรการ</li>   
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <admin-equ-items>
    </admin-equ-items>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection