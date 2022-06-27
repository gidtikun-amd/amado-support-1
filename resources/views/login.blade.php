<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/styles.css')}}" rel="stylesheet">
    <link href="{{asset('css/app-custom.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/fontawesome/css/all.css')}}" rel="stylesheet">
    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>

</head>

<body class="bg-login">
    <script src="js/jquery.js"></script>
    <div id="app" class="h-100">
        <login>
        </login>
    </div>
    <script>
        const app = new Vue({
            el: "#app"
        });
    </script>
</body>

</html>

