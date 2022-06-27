@extends('layout.template') {{-- Template --}}
@section('title','Dashboard') {{-- Title --}}

@section('content') {{-- Content --}}
<div id="app">
    <it-admin-index>
    </it-admin-index>
</div>
<script>
    const app = new Vue({
        el: "#app"
    });
</script>
@endsection
