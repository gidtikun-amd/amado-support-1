@extends('layout.template') {{-- Template --}}
@section('title','รายการยังไม่ได้อัพโหลดเอกสาร') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">IT Memo</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-itmemo-files>
    </it-itmemo-files>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
