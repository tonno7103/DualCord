<!doctype html>
@extends('layouts.basic')
@section('title', 'Edit Profile')

<link rel="stylesheet"  href="{{$home}}{{$nodePort}}/stylesheets/profileSettings.css"/>
@php
    $defaultImage = false;
    $user = Auth::user();
    $username = $user->username;
    $email = $user->email;
    $imageFormat = $user->image_format;
    if($imageFormat == null){
        $defaultImage = true;
    }
@endphp

@section('content')
    <div class="container-xl px-4 mt-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session()->has('editProfileSuccess'))
            <div class="alert alert-success">
                {{ session()->get('editProfileSuccess') }}
            </div>
            {{ session()->forget('editProfileSuccess') }}
        @endif
        <hr class="mt-0 mb-4"/>
        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header" style="color: white">Profile Picture</div>
                    <div class="card-body text-center">
                        <img class="img-account-profile rounded-circle mb-2" src="{{$home}}{{$phpPort}}/images/{{$user->id}}.{{$imageFormat}}" alt="Profile Image" id="image" onerror="setDefaultImage(this)">
                        <p class="small font-italic mb-4" style="color: white; font-size: 80%">Supported: JPEG, PNG, JPG, GIF, SVG, WEBP, BMP</p>
                        <div>
                            @if(!$defaultImage)
                                <form action="{{$home}}{{$phpPort}}/user/removeImage" method="post">
                                    @csrf
                                    <label class="btn btn-danger">
                                        <input id="imageRemover" name="imageRemover" style="display: none" type="submit">
                                        Remove your current image
                                    </label>
                                </form>
                            @endif
                            <form action="{{$home}}{{$phpPort}}/user/uploadImage" method="post" enctype="multipart/form-data">
                                @csrf
                                <label for="imageInput" class="btn btn-success">
                                    <input id="imageInput" name="imageInput" required accept="image" type="file">
                                    Select Image
                                </label>
                                <label for="submitImage" class="btn btn-primary">
                                    <input type="submit" id="submitImage" style="display: none" value="Upload Image">
                                    Upload Image
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header" style="color: white">Account Details</div>
                    <div class="card-body">
                        <form action="" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="small mb-1" for="inputUsername">Username</label>
                                <input class="form-control shadow-none" name="username" id="inputUsername" type="text" placeholder="Enter your username" value="{{$username}}">
                            </div>
                            <div class="row gx-3 mb-3 mt-2">
                                <div class="col-md-12">
                                    <label class="small mb-1" for="inputEmail">Email</label>
                                    <input class="form-control shadow-none" id="inputEmail" name="email" type="text" placeholder="E-mail" value="{{$email}}">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="small mb-1" for="inputPassword">Change Password</label>
                                    <input class="form-control shadow-none" name="passwordChange" id="inputPassword" type="password" placeholder="Enter your password">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="small mb-1" for="inputPasswordConfirm">Confirm Password</label>
                                    <input class="form-control shadow-none" name="passwordConfirm" id="inputPasswordConfirm" type="password" placeholder="Confirm your password">
                                </div>
                                <div class="col-md-12 mt-4">
                                    <label class="small mb-1" for="currentPassword">Current password (Required)</label>
                                    <input class="form-control shadow-none" name="currentPassword" id="currentPassword" type="password" required placeholder="Enter your password">
                                </div>
                            </div>
                            <input class="btn btn-primary shadow-none" type="submit" value="Save changes">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script defer>
 (() =>{
        const imageInput = document.getElementById('imageInput');
        const image = document.getElementById('image');
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                image.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    })()
</script>
@stop
