@extends('layouts.app')

@section('title', 'home')

@section('content')
<div class=" mt-4">
    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create a New Post</a>
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
                <th scope="col">Function</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <th scope="row">{{ $post->id }}</th>
                <td>{{ $post->title }}</td>
                <td>{{ $post->excerpt }}</td>
                <td>{{ $post->created_at }}</td>
                <td>
                    @if(is_null($post->posted_at))
                    Not Posted Yet
                    @else
                    {{ $post->posted_at }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm delete-post-button" data-post-id="{{ $post->id }}">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>

                </td>

            </tr>
            @endforeach
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
    });

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