<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Jalen 的博客</title>
    <link rel="shortcut icon" href="/favicon.ico" sizes="32x32">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{mix('css/app.css','assets')}}">

    <style>
        html, body {
            /*background-color: #fff;*/
            color: #636b6f;
            height: 100vh;
            margin: 0;
        }
        a{
            text-decoration:none
        }
    </style>
</head>
<body>
<div id="app">
</div>
<script src="{{mix('js/app.js','assets')}}"></script>
</body>
</html>
