@extends('layout.template') {{-- Template --}}
@section('title','รายการ Log Error') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">รายงาน</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-exception>
    </it-exception>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
