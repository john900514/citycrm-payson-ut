<html lang="en-US">
<head>
    @include('payson.includes.head')
</head>
<body class="home page-template-default page page-id-7 fl-builder fl-preset-default fl-full-width" itemscope="itemscope" itemtype="http://schema.org/WebPage">
<div class="fl-page">
    @include('payson.includes.header')

    @yield('content')

    @include('payson.includes.footer')
    @include('payson.includes.after_scripts')
</div><!-- .fl-page -->

</body>
</html>
