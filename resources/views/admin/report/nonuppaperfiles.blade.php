@extends('layout.template') {{-- Template --}}
@section('title','รายงานเอกสารยังไม่ได้อัพโหลด') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">รายงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-report-nouppaper>
    </it-report-nouppaper>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
