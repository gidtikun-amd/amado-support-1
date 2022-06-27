@extends('layout.template') {{-- Template --}}
@section('title','รายการคำขอ Memo') {{-- Title --}}

@section('content') {{-- Content --}}
<ol class="breadcrumb bg-white">
    <li class="breadcrumb-item">IT Memo</li>
    <li class="breadcrumb-item">@yield('title')</li>
</ol>
<div id="app">
    <it-itmemo-list>
    </it-itmemo-list>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection