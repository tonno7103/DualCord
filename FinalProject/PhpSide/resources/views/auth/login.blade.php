@extends('auth.layouts.basic')
@section('title', 'Login')
@section('content')
<div class="main">
    <div class="container" style="text-align: center;">
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif
        <div class="middle">
            <div id="login">
                <form action="" method="post">
                    @csrf
                    <fieldset class="clearfix">
                        <p><span class="fa fa-user"></span><label>
                                <input type="text" name="email" Placeholder="E-Mail" required>
                            </label></p>
                        <p><span class="fa fa-lock"></span><label>
                                <input type="password" name="password" Placeholder="Password" required>
                            </label></p>
                        <div>
                            <span><input type="submit" value="Sign In"></span>
                        </div>

                    </fieldset>
                    <div class="clearfix"></div>
                </form>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@stop