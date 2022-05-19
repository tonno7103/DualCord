@php
    $json = json_decode(file_get_contents(storage_path() . '/configs.json'));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="theme-color" content="#317EFB">
    <meta name="description" content="Simple discord like application">

    <link rel="manifest" href="{{$json->address}}{{$json->phpPort}}/manifest.json"/>
    <script type="module">
        import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate@0.2.1/dist/pwa-update.js';
        const el = document.createElement('pwa-update');
        document.body.appendChild(el);
    </script>

    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.5/dist/flowbite.min.css" />
    <script src="https://unpkg.com/flowbite@1.4.5/dist/flowbite.js"></script>
    <link rel="icon" href="{{$json->address}}{{$json->phpPort}}/images/pwa/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="{{$json->address}}{{$json->nodePort}}/stylesheets/global.css">
    <link rel="stylesheet" href="{{$json->address}}{{$json->nodePort}}/stylesheets/auth.css">
    <link rel="apple-touch-icon" href="{{$json->address}}{{$json->phpPort}}/images/pwa/icons/apple-icon-180.png">
</head>
<body>
<nav class="border-gray-200 px-2 sm:px-4 py-2.5 rounded dark:bg-gray-800">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
        <a href="{{$json->address}}{{$json->nodePort}}" class="flex items-center">
            <img src="{{$json->address}}{{$json->nodePort}}/images/logos/logo.png" class="mr-3 h-6 sm:h-9" alt="Flowbite Logo" />
            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">DualCord</span>
        </a>
        <div class="flex md:order-2">
            <button data-collapse-toggle="mobile-menu-3" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-3" aria-expanded="false">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div class="hidden justify-between items-center w-full md:flex md:w-auto md:order-1" id="mobile-menu-3">
            <ul class="flex flex-col mt-4 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium" style="position: absolute; right: 0; margin-right: 50px">
                @if (!Auth::user())
                <li>
                    <a href="{{$json->address}}{{$json->phpPort}}/auth/login" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Login</a>
                </li>
                <li>
                    <a href="{{$json->address}}{{$json->phpPort}}/auth/register" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Register</a>
                </li>
                @else
                <li style="display: flex;justify-content: center;align-items: center;">
                    <a href="{{$json->address}}{{$json->phpPort}}/user/edit" class="mr-4">Welcome, {{Auth::user()->username}}</a>
                    <img src="{{$json->address}}{{$json->phpPort}}/images/{{Auth::id()}}.{{Auth::user()->image_format}}"
                         onclick="window.location.replace('{{$json->address}}{{$json->phpPort}}/user/edit')" style="border-radius: 50%; width: 50px; height:50px " alt=""/>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
    @yield('content')
</body>
</html>
