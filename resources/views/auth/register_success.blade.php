@extends('layouts.app')

@section('title', 'Register success')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="margin-top: 20px">
                <div class="panel-heading">Register success</div>
                <div class="panel-body">
                    <p>Username: {{ $user['email'] }}</p>
                    <p>Password: {{ $user['password'] }}</p>
                    <a href="/register" class="btn btn-default">Add new user</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
