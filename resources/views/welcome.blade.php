<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{!! env('APP_NAME') !!} | Welcome</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: gray;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        img {
            width: 75%;
        }

        .links > a {
            color: white;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('backpack.auth.login'))
        <div class="top-right links">
            @if(backpack_user() != null)
                <a href="{{ url('/dashboard') }}">Home</a>
            @else
                <a href="{{ route('backpack.auth.login') }}">Login</a>

                @if (Route::has('backpack.auth.register'))
                <!-- <a href="{{ route('backpack.auth.register') }}">Register</a> -->
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            <img src="https://amchorcms-assets.s3.amazonaws.com/anchorCMSLogo.png">
        </div>

        <div class="links">
            <a href="https://capeandbay.com">Cape&Bay</a>
        </div>
    </div>
</div>
</body>
</html>
