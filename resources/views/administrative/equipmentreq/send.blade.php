@extends('layout.template') {{-- Template --}}
@section('title','ขอเบิกอุปกรณ์สำนักงาน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">งานธุรการ</li>    
    <li class="breadcrumb-item">ใบเบิกอุปกรณ์สำนักงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-ad-equ-create>
    </user-ad-equ-create>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
