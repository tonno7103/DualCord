@extends('auth.layouts.basic')
@section('title', 'Register')
@section('content')
<div class="main">
    <div class="container" style="text-align: center;">
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <div id="login">
            <form action="" method="post">
                @csrf
                <p><span class="fa fa-user"></span>
                        <input type="text" name="email" Placeholder="E-mail" required>
                    </p>
                    <p><span class="fa fa-user"></span>
                        <input type="text" name="username" Placeholder="username" required>
                    </p>
                <p><span class="fa fa-lock"></span>
                    <input type="password" name="password" Placeholder="Password" required>
                    </p>
                <p><span class="fa fa-lock"></span>
                    <input type="password" name="password_confirmation" Placeholder="Confirm password" required>
                    </p>
                <div>
                    <span style="width:100%; text-align:right;  display: grid;"><input type="submit" value="Sign Up"></span>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
