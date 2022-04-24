@section("sidebarElements")
    @for ($i = 0; $i < 10; $i++)
        <li>
            <a href="{{$home}}{{$nodePort}}/user"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-download fa-stack-1x "></i></span>Directs</a>
        </li>
        <li>
            <a href="{{$home}}{{$nodePort}}/user"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-download fa-stack-1x "></i></span>Directs</a>
        </li>
        <li>
            <a href="{{$home}}{{$nodePort}}/user"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-download fa-stack-1x "></i></span>Directs</a>
        </li>  
    @endfor
        <li>
            <a href="{{$home}}{{$nodePort}}/user"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-download fa-stack-1x "></i></span>---Servers---</a>
        </li>
    @for ($i = 0; $i < 10; $i++)
        <li>
            <a href="{{$home}}{{$nodePort}}/user"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-download fa-stack-1x "></i></span>Directs</a>
        </li>
        <li>
            <a href="{{$home}}{{$nodePort}}/user"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-download fa-stack-1x "></i></span>Directs</a>
        </li>
        <li>
            <a href="{{$home}}{{$nodePort}}/user"><span class="fa-stack fa-lg pull-left"><i class="fa fa-cloud-download fa-stack-1x "></i></span>Directs</a>
        </li>  
    @endfor
@stop