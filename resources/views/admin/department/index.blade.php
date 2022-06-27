@extends('layout.template') {{-- Template --}}
@section('title','จัดการข้อมูลฝ่าย') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">ข้อมูลหลัก</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-department-index>
    </it-department-index>
</div>
<script>
    const app = new Vue({
        el: '#app',
    });
</script>
@endsection