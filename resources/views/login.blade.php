<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js"></script>
</head>
<body>
    <form method="POST" action="/demo-login">
        {{ csrf_field() }}
    </form>

    <script>
        $(document).ready(function () {
            $('form').submit();
        });
    </script>
</body>
