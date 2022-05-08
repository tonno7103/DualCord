<!DOCTYPE html>
<script>
   function setDefaultImage(obj){
       obj.error = null;
       obj.src = "{{$home}}{{$nodePort}}/images/profile_image.png";
   }
</script>
<html lang="en">
<head>
    <link rel="manifest" href="{{$home}}{{$phpPort}}/manifest.json"/>
    <script type="module">
        import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate@0.2.1/dist/pwa-update.js';
        const el = document.createElement('pwa-update');
        document.body.appendChild(el);
    </script>
    <meta name="description" content="Discord like application">
    <meta name="csrf-token" content="{{ csrf_token() }}">
   <link rel="icon" type="image/x-icon" href="{{$home}}{{$nodePort}}/images/logos/favicon.png">
   <title>@yield('title')</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="{{$home}}{{$nodePort}}/stylesheets/global.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{$home}}{{$nodePort}}/stylesheets/user.css">
    <link rel="stylesheet" href="{{$home}}{{$phpPort}}/css/sidebar.css">

   @stack('scripts')
</head>
<body id="body-pd">
   <header class="header" id="header">
       <div class="header_toggle"> <i class='bx bx-menu text-white' id="header-toggle"></i></div>
   </header>
   <div class="l-navbar" id="nav-bar">
       <nav class="nav">
           <div> <a href="{{$home . $nodePort}}" class="nav_logo"><i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">DualCord</span> </a>
               <div class="nav_list">
                   <a href="{{$home . $phpPort}}/dashboard" class="nav_link @if($route == 'dashboard') active @endif" data-toggle="tooltip" data-placement="right" title="Dashboard">
                       <i class='bx bx-grid-alt nav_icon'></i>
                       <span class="nav_name" >Dashboard</span>
                   </a>
                   <a href="{{$home . $phpPort}}/user/edit" class="nav_link @if($route == 'edit') active @endif" data-toggle="tooltip" data-placement="right" title="Users">
                       <i class='bx bx-user nav_icon'></i>
                       <span class="nav_name">Users</span>
                   </a>
                   <a href="{{$home . $phpPort}}/chat" class="nav_link @if($route == 'chat') active @endif" data-toggle="tooltip" data-placement="right" title="Messages">
                       <i class='bx bx-message-square-detail nav_icon'></i>
                       <span class="nav_name">Messages</span>
                   </a>
                   <a href="{{$home . $phpPort}}/guilds" class="nav_link @if($route == 'voice') active @endif" data-toggle="tooltip" data-placement="right" title="Voice Guilds">
                       <i class='bx bxs-user-voice nav_icon'></i>
                       <span class="nav_name">Voice Guilds</span>
                   </a>
               </div>
           </div> <a href="{{$home}}{{$phpPort}}/auth/logout" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">SignOut</span> </a>
       </nav>
   </div>

   <div class="bg-light bg-transparent">
       @yield('content')
   </div>

  <script>
      document.addEventListener("DOMContentLoaded", function(event) {

         const showNavbar = (toggleId, navId, bodyId, headerId) =>{
             const toggle = document.getElementById(toggleId),
                 nav = document.getElementById(navId),
                 bodypd = document.getElementById(bodyId),
                 headerpd = document.getElementById(headerId)

             if(toggle && nav && bodypd && headerpd){
                 toggle.addEventListener('click', ()=>{
                     nav.classList.toggle('show')
                     toggle.classList.toggle('bx-x')
                     bodypd.classList.toggle('body-pd')
                     headerpd.classList.toggle('body-pd')
                 })
             }
         }

         showNavbar('header-toggle','nav-bar','body-pd','header')

         const linkColor = document.querySelectorAll('.nav_link')

         function colorLink(){
             if(linkColor){
                 linkColor.forEach(l=> l.classList.remove('active'))
                 this.classList.add('active')
             }
         }
         linkColor.forEach(l=> l.addEventListener('click', colorLink))
     });
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <script src="{{$home}}{{$nodePort}}/javascripts/user.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
