@extends('auth.layouts.basic')
@section('title', 'Logout')
@section('content')
    <h1>Logout success</h1>
    @php
           session_start();
           $data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
           $url = $data['address'] . $data['nodePort'] . "/logout";
           $ch = curl_init();

           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, 1);
           curl_setopt($ch, CURLOPT_POSTFIELDS, "&key=" . session_id());
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           $result = curl_exec($ch);
           $result = json_decode($result, true);
           curl_close($ch);
    @endphp
    {{ Redirect::to($data['address'] . $data['nodePort'])->send() }}
    <script defer>
        window.location.replace = "{{ $data['address'] . $data['nodePort'] }}";
    </script>
@stop
