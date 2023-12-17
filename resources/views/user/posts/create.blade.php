@extends('layouts.app')

@section('title', 'Create New Post')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">Create a New Post</div>
                <div class="back">
                    <a class="btn btn-secondary" href="{{ route('posts.index') }}">Back</a>
                </div>
                <div class="card-body">
                    <form id="postForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title">
                            <div id="titleError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug">
                            <div id="slugError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <input type="text" class="form-control" id="excerpt" name="excerpt">
                            <div id="excerptError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="5"></textarea>
                            <div id="contentError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label><br />
                            <img class="post-image" id="preview-image" src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png" alt="Product Image" class="img-fluid mb-2">
                            <input type="file" class="form-control" id="image" name="image" onchange="previewImage()">
                            <div id="imageError"></div>
                        </div>
                        <button type="button" class="btn btn-warning" onclick="resetFormData()">Reset</button>
                        <button type="button" class="btn btn-primary ml-4" onclick="createPost('CREATE')">Create</button>
                        <button type="button" class="btn btn-success ml-4" onclick="createPost('CREATE_PUBLISH')">Create And Publish</button>
                        <input type="hidden" id="publishInput" name="publish">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function previewImage() {
        var input = document.getElementById('image');
        var output = document.getElementById('preview-image');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                output.src = e.target.result;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function resetFormData() {
        $('#postForm').trigger("reset");
        document.getElementById('publishInput').value = '';
        document.getElementById('image').value = '';
        document.getElementById('preview-image').src = 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png';
        $('.error').remove();
    }

    function createPost(action) {
        $(".alert").remove();
        document.getElementById('publishInput').value = action;
        var formData = new FormData(document.getElementById('postForm'));
        // for (var pair of formData.entries()) {
        //     console.log(pair[0] + ': ' + pair[1]);
        // }
        $.ajax({
            url: "{{ route('posts.store') }}",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                action === "CREATE" ? showToast('created post successfully') :
                    showToast('created and publish post successfully');
                $('.error').remove();
                document.getElementById('publishInput').value = '';
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            $("#" + key + "Error").html('<p class="error">' + errors[key][0] + '</p>');
                        }
                    }
                }
            }
        });
    }
</script>
@endsection