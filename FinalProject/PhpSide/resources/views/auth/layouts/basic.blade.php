<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="theme-color" content="#317EFB">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="@php
        $json = json_decode(file_get_contents(storage_path() . '/configs.json'));
        echo $json->address . $json->nodePort;
    @endphp/stylesheets/auth.css">
    <link rel="apple-touch-icon" href="{{$json->address}}{{$json->phpPort}}/images/pwa/icons/apple-icon-180.png">
</head>
<body>
    @yield('content')
</body>
</html>
