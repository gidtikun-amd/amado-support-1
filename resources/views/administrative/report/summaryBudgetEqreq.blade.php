@extends('layout.template') {{-- Template --}}
@section('title','รายงานสรุป Budget การเบิกอุปกรณ์สำนักงาน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">รายงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <admin-report-summary-budget-eqreq>
    </admin-report-summary-budget-eqreq>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
