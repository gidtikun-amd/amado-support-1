@extends('layout.template') {{-- Template --}}
@section('title','ประวัติขอ CRM Item Request') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">CRM Item Request</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <user-itemreq-history-crm>
    </user-itemreq-history-crm>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
