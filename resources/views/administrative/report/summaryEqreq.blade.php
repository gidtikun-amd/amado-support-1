@extends('layout.template') {{-- Template --}}
@section('title','รายงานสรุปการเบิกอุปกรณ์สำนักงาน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">รายงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <admin-report-summary-eqreq>
    </admin-report-summary-eqreq>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
