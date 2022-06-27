@extends('layout.template') {{-- Template --}}
@section('title','บันทึกปัญหาภายนอก') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    {{-- <li class="breadcrumb-item">บันทึกปัญหาภายนอก</li> --}}
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-extpb-index>
    </it-extpb-index>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
