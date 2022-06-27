<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/styles.css')}}" rel="stylesheet">
    <link href="{{asset('css/app-custom.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/fontawesome/css/all.css')}}" rel="stylesheet">
    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>

</head>
<body class="fixed-nav sticky-footer bg-white" >

    <nav class="navbar navbar-expand-lg navbar-amado bg-white fixed-top" id="mainNav">
        <img class="logo-nav" src="{{asset('img/logo-red.png')}}" alt="">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            @php
                use App\Http\Controllers\Security\RoleController;
                $role = new RoleController();
            @endphp


            <ul class="navbar-nav navbar-sidenav scrollbar-custom bg-white" id="menu-nav">

{{---- Menu Administrator - Start ---------------------------------------------------------------------------------------------------------------}}

            @include('layout.menu.mis-administrator')           {{-- MIS - Administrator --}}
            {{-- @include('layout.menu.admin-administrator')     Admin - Administrator --}}

{{---- Menu Administrator - End ---------------------------------------------------------------------------------------------------------------}}



{{---- Menu General User - Start ---------------------------------------------------------------------------------------------------------------}}

            @include('layout.menu.dashboard-user')      {{-- Dashboard - General User --}}
            @include('layout.menu.mis-user')            {{-- MIS - General User --}}
            {{-- @include('layout.menu.admin-user')          Admin - General User --}}
            @include('layout.menu.documentation')       {{-- Documentation - All --}}

{{---- Menu General User - End ---------------------------------------------------------------------------------------------------------------}}
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item my-auto">
                    <span class="text-dark">
                    @if(Session::has('name'))
                        <i class="fas fa-user text-amado-blue mr-2"></i> {{ Session::get('name')}}
                    @else
                        No Session was announced.
                    @endif
                    </span>
                    <span class="text-dark ml-2 mx-3">|</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="ml-2">Logout</span>
                    </a>
                </li>
            </ul>
        </div>

    </nav>


    <div class="content-wrapper">
        <div class="container-fluid">

            <h4 class="p-3 text-black">@yield('title')</h4>

            @yield('content')

            <!-- /Footer-->
{{--            <footer class="sticky-footer">--}}
{{--                <div class="container">--}}
{{--                    <div class="text-center">--}}
{{--                        <small>Copyright © Amado 2020</small>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </footer>--}}

        </div>
    </div>

</body>
</html>

