@extends('layouts.app')

@section('title', 'Posts')

@section('content')
<div class=" mt-4">
    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create a New Post</a>
    <a href="{{ route('categories.index') }}" class="btn btn-info text-white">Manage Categories</a>
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

<script>
    var table;
    var currentPage = 1;
    $(document).ready(function() {
        table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            bFilter: false,
            ajax: {
                url: "{{ route('posts.data') }}",
                data: function(d) {
                    d.title = $('#searchTitle').val();
                    d.excerpt = $('#excerpt').val();
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'excerpt',
                    name: 'excerpt'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'posted_at',
                    name: 'posted_at',
                    render: function(data, type, row) {
                        if (data === null) {
                            return 'Not Posted Yet';
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: 'status',
                    name: 'status'
                },
            ],
            columnDefs: [{
                targets: 6,
                render: function(data, type, row) {
                    return `<a href="/posts/${row.id}/edit" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit</a>
                            <button class="btn btn-danger btn-sm delete-post-button" data-post-id="${row.id}">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>`;
                }
            }]
        });
        $('#searchButton').on('click', function() {
            table.draw();
        });

        $('.table tbody').on('click', '.delete-post-button', function() {
            var postId = $(this).data('post-id');
            currentPage = table.page.info().page + 1;

            if (confirm('Are you sure you want to delete this post?')) {
                $.ajax({
                    url: "/posts/" + postId,
                    type: 'DELETE',
                    data: {
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function(result) {
                        table.row($(this).closest('tr')).remove().draw(false);
                        table.page(currentPage - 1).draw('page');
                        showToast(result.message);
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred while deleting the post.';
                        alert(errorMsg);
                    }
                });
            }
        });
    });
</script>

</script>

@endsection
