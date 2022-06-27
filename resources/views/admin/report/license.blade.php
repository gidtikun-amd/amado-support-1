@extends('layout.template') {{-- Template --}}
@section('title','รายงานการถือครอง License') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">รายงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-report-license>
    </it-report-license>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
