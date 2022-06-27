@extends('layout.template') {{-- Template --}}
@section('title','การกำหนดสิทธิ์เข้าถึงการใช้งาน') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">การกำหนดตัวตนและสิทธิ์</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-role-management>
    </it-role-management>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
