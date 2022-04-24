@extends('auth.layouts.basic')
@section('title', 'Login success')
@section('content')
    <div class="main">
        @php
            session_start();
            $data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
            $url = $data['address'] . $data['nodePort'] . "/login";
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "user_id=" . $id . "&key=" . session_id());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $result = json_decode($result, true);
            curl_close($ch);
        @endphp

        <script defer>
            window.location.replace('{{$data['address'] . $data['nodePort']}}');
        </script>
    </div>
@stop
