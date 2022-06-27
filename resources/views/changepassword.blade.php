<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - Change Password</title>

    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/styles.css')}}" rel="stylesheet">
    <link href="{{asset('css/app-custom.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/fontawesome/css/all.css')}}" rel="stylesheet">
    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>

</head>

<body class="bg-login vh-100">
    {{-- <script src="js/jquery.js"></script> --}}
    <div id="app">
        <change-password>
        </change-password>
    </div>
    <script>
        const app = new Vue({
            el: "#app"
        });
    </script>
</body>

</html>

