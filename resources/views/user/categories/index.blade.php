@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    <div class=" mt-4">
        <a href="{{ route('posts.index') }}" class="btn btn-primary">Back</a>

    </div>
    <div class="filter-container mt-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="searchTitle" class="form-control" placeholder="Search by Title">
            </div>
            <div class="col-md-4">
                <input type="text" id="excerpt" class="form-control" placeholder="Search by Excerpt">
            </div>
            <div class="col-md-4">
                <button id="searchButton" class="btn btn-primary">Search</button>
            </div>
        </div>
    </div>


    <div class="post-list">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Slug</th>
                <th scope="col">status</th>

            </tr>
            </thead>
        </table>
    </div>

    <script>
        let table;
        $(document).ready(function () {
           table =  $('.table').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                ajax: "{{ route('categories.data') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'slug', name: 'slug'},
                    {data: 'status', name: 'status'}
                ]
            });
        });
    </script>

@endsection
