@extends('layout.template') {{-- Template --}}
@section('title','รายการยังไม่ได้อัพโหลดเอกสาร') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">Job Request</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-jobreq-files>
    </it-jobreq-files>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection