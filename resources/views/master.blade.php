<!Doctype html>
<html lang="{{ app()->getLocale() }}" style="font-size: 37.5px;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0,
          minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!-- uc强制竖屏 -->
    <meta name="screen-orientation" content="portrait">
    <meta name="browsermode" content="application">
    <!-- QQ强制竖屏 -->
    <meta name="x5-orientation" content="portrait">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Hiring App</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body style="font-size: 12px;">
<div id="app">
    <app></app>
</div>
<script src="/js/app.js"></script>
</div>
</body>
</html>
