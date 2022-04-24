<script>
   function setDefaultImage(obj){
       obj.error = null;
       obj.src = "{{$home}}{{$nodePort}}/images/profile_image.png";
   }
</script>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <link rel="icon" type="image/x-icon" href="{{$home}}{{$nodePort}}/images/logos/favicon.png">
   <title>@yield('title')</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="{{$home}}{{$nodePort}}/stylesheets/global.css">
   @stack('scripts')
</head>
<body>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
   <link rel="stylesheet" href="{{$home}}{{$nodePort}}/stylesheets/user.css" />
    <div id="wrapper">
        <div id="sidebar-wrapper" class="scrollable-menu">
           <ul class="sidebar-nav nav-pills nav-stacked" id="menu">
               <li>
                  <a href="{{$home}}{{$phpPort}}/dashboard" style="@if ($route == 'dashboard') background: var(--channelSelected); @endif">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-speedometer" viewBox="0 0 16 16">
                        <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
                        <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z"/>
                      </svg><span class="fa-stack fa-lg pull-left" style="padding: 1%;"><i class="fa fa-cloud-download fa-stack-1x "></i></span>Dashboard</a>
               </li>
              <li>
                 <a style="@if ($route == 'edit') background: var(--channelSelected); @endif"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                 <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434l.071-.286zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5zm0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78h4.723zM5.048 3.967c-.03.021-.058.043-.087.065l.087-.065zm-.431.355A4.984 4.984 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8 4.617 4.322zm.344 7.646.087.065-.087-.065z"/>
                 </svg><span class="fa-stack fa-lg pull-left" style="padding: 1%;"><i class="fa fa-dashboard fa-stack-1x "></i></span>Profile settings</a>
                 <ul class="nav-pills nav-stacked" style="list-style-type:none;">
                     <li><a href="{{$home}}{{$phpPort}}/user/edit" style="@if ($route == 'edit') background: var(--channelSelected); @endif">Edit profile</a></li>
                     <li><a href="{{$home}}{{$phpPort}}/auth/logout">Logout</a></li>
                 </ul>
              </li>
              <li>
                  <a href="{{$home}}{{$phpPort}}/chat" style="@if ($route == 'directs') background: var(--channelSelected); @endif">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                  <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                  <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z"/>
                  </svg><span class="fa-stack fa-lg pull-left" style="padding: 1%;"><i class="fa fa-cloud-download fa-stack-1x "></i></span>Directs</a>
               </li>
              @yield('sidebarElements')
           </ul>

           <nav class="navbar navbar-dark no-margin bg-dark bottom-connected">
               <div class="navbar-item">
                  <button type="button" name="sidebarExpand" class="navbar-toggle collapsed btn rounded-circle shadow-lg side-expansion position-relative top-0 right-0" data-toggle="collapse" id="menu-toggle" style="height: 38px;">
                     <svg style="color:white;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                     </svg>
                  </button>
               </div>
               <div class="navbar-item">
                  <a href="{{$home}}{{$nodePort}}" class="navbar-brand" style="display: flex">
                     <p class="pr-3">Stuful Home page</p>
                     <img src="{{$home}}{{$nodePort}}/images/logos/logo.png" alt="logo" class="img-fluid" style="height: 38px;">
                  </a>
               </div>
            </nav>
        </div>
     </div>
     @yield('content')
  <script defer>
     (() => {
        let opened = false;
        const sideBar = document.getElementById('sidebar-wrapper');
        document.getElementById('menu-toggle').addEventListener('click', ()=>{
           if(opened || sideBar.offsetWidth === '0px' || sideBar.offsetWidth === 0){
              sideBar.style.width = "300px";
              opened = false;
           }
           else{
              sideBar.style.width = "0px";
              opened = true;
           }
        });
     })();
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="{{$home}}{{$nodePort}}/javascripts/user.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>
