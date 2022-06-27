@extends('layout.template') {{-- Template --}}
@section('title','รายงานสรุปทรัพย์สินที่เกิดขึ้น') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">รายงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-report-tranfer-asset>
    </it-report-tranfer-asset>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
