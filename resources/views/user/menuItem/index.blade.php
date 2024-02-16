@extends('layouts.app')

@section('title', 'Menu Item')

@section('content')
    <div class=" mt-4">
        <a href="{{ route('menus.create') }}" class="btn btn-primary">Create Menu Item</a>

    </div>

    <div class="post-list">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>
                    <input class="form-check-input" type="checkbox" id="selectAll"/>
                </th>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">Excerpt</th>
                <th scope="col">Create At</th>
                <th scope="col">Post At</th>
                <th scope="col">Status</th>
                <th scope="col">Function</th>
            </tr>
            </thead>
        </table>
    </div>



@endsection
