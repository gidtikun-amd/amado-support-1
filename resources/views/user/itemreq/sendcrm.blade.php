@extends('layout.template') {{-- Template --}}
@section('title','ส่งคำขอ CRM Item Request') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">Item Request</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>

<div id="app">
    <user-itemreq-create-crm>
    </user-itemreq-create-crm>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
