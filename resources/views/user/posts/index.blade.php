@extends('layouts.app')

@section('title', 'home')

@section('content')
<div class=" mt-4">
    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create a New Post</a>
</div>
<div class="row mt-4">
    <div class="col-md-6 mx-auto">
        <form id="searchForm">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control" placeholder="Search for a post">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
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
        <tbody>
            @include('user.posts.partials.post_list', ['posts' => $posts])
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('.delete-post-button').on('click', function() {
            var postId = $(this).data('post-id');
            var confirmDelete = confirm('Are you sure you want to delete this post?');
            var button = $(this);
            if (confirmDelete) {
                removePost(postId);
            }
        });

        $('#searchButton').on('click', function() {
            var searchTerm = $('#searchInput').val();
            searchPosts(searchTerm);
        });
    });

    function searchPosts(searchTerm) {
        $.ajax({
            url: '/posts/search',
            method: 'GET',
            data: {
                search: searchTerm
            },
            success: function(response) {
                console.log(response);
                updateTable(response);
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    }
    function updateTable(data) {
    $('.post-list table tbody').html(data);
}

    function removePost(postId) {
        $.ajax({
            url: '/posts/' + postId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                id: postId
            },
            success: function(response) {
                showToast('Post deleted successfully');
                $('.delete-post-button[data-post-id="' + postId + '"]').closest('tr').remove();
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    }
</script>


@endsection