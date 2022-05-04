@extends('auth.layouts.basic')
@section('title', 'Login success')
@section('content')
    <div class="main">
        @php
            session_start();
            $data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
            $url = $data['address'] . $data['nodePort'] . "/login";
        @endphp

        <script>
            fetch('{{ $url }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: 'user_id={{ $id }}&key={{ session_id() }}'
            }).then(function (response) {
                return response.json();
            }).then(function (data) {
                console.log(data);
                window.location.replace('{{$data['address'] . $data['nodePort']}}');
            });

        </script>
    </div>
@stop
