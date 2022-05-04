@extends('auth.layouts.basic')
@section('title', 'Logout')
@section('content')
    @php
        session_start();
        $data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
        $url = $data['address'] . $data['nodePort'] . "/logout";
    @endphp
    <div class="main">
        <script>
            fetch('{{ $url }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: 'key={{ session_id() }}'
            }).then(function (response) {
                return response.json();
            }).then(function (data) {
                console.log(data);
                window.location.replace('{{$data['address'] . $data['nodePort']}}');
            });
        </script>
    </div>
@stop
