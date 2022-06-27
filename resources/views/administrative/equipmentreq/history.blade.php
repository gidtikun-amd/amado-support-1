@extends('layout.template') {{-- Template --}}
@section('title','ประวัติขอเบิกอุปกรณ์สำนักงาน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">งานธุรการ</li>    
    <li class="breadcrumb-item">ใบเบิกอุปกรณ์สำนักงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-ad-equ-history>
    </user-ad-equ-history>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
