@extends('layout.template') {{-- Template --}}
@section('title','รายงานสรุปผลการ Support') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">รายงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-report-summary-support>
    </it-report-summary-support>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
