@extends('layout.template') {{-- Template --}}
@section('title','หน้าหลัก') {{-- Title --}}

@section('content') {{-- Content --}}
<div id="app">
    <user-index>
    </user-index>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
