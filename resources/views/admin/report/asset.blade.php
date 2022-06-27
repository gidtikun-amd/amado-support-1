@extends('layout.template') {{-- Template --}}
@section('title','รายงานทรัพย์สิน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">รายงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-report-asset>
    </it-report-asset>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
