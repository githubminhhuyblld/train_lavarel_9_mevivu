@extends('layouts.app')

@section('title', 'home')

@section('content')
    <div style="height: 100vh">
        <h3>Home Page </h3>
        <a href="{{route('posts.index')}}">Manage Post</a>
    </div>

@endsection
