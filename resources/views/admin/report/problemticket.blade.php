@extends('layout.template') {{-- Template --}}
@section('title','รายงานสรุปปัญหาที่เกิดขึ้น') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">รายงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-report-problemticket>
    </it-report-problemticket>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
