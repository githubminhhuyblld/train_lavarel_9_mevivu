@extends('layouts.app')

@section('Edit', 'Create New Post')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">Edit Post</div>
                <div class="card-body">
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="{{ route('posts.index') }}">Back</a>
                    </div>
                    <form id="postForm" action="{{ route('posts.update', ['id' => $post->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required data-parsley-trigger="keyup" data-parsley-minlength="3" data-parsley-maxlength="255">
                            <div id="titleError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ $post->slug }}" required data-parsley-trigger="keyup" data-parsley-minlength="3" data-parsley-maxlength="255">
                            <div id="slugError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <input type="text" class="form-control" id="excerpt" name="excerpt" value="{{ $post->excerpt }}" required data-parsley-trigger="keyup" data-parsley-minlength="3" data-parsley-maxlength="255">
                            <div id="excerptError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="5">{{ $post->content }}</textarea>
                            <div id="contentError" class="parsley-errors-list"></div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label></br />
                            <img class="post-image" id="preview-image" src="@if($post->image){{ asset($post->image) }}@else{{ 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png' }}@endif" alt="Post Image" class="img-fluid mb-2">
                            <input type="file" class="form-control" id="image" name="image">
                            <div id="imageError"></div>
                        </div>
                        <button type="button" class="btn btn-warning" onclick="resetFormData()">Reset</button>
                        <button type="button" onclick="updatePost()" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**Ckeditor */
    CKEDITOR.replace('content');
    CKEDITOR.instances.content.on('change', function() {
        $('#content').val(CKEDITOR.instances.content.getData());

        $('#content').parsley().validate();
    });
    CKEDITOR.instances.content.on('change', function() {

        var errorMessage = $('#content').parsley().getErrorsMessages();
        if (errorMessage.length > 0) {
            $('#contentError').html(errorMessage[0]);
        } else {
            $('#contentError').empty();
        }
    });


    /**Validate */
    $(document).ready(function() {
        $('#postForm').parsley({
            errorsContainer: function(parsleyField) {
                return parsleyField.$element.closest('.form-group');
            },
            errorsWrapper: '<div class="parsley-errors-list"></div>',
            errorTemplate: '<div></div>'
        }).on('field:validated', function(parsleyField) {
            if (parsleyField.validationResult === true) {
                parsleyField.$element.removeClass('input-error');
            } else {
                parsleyField.$element.addClass('input-error');
            }
        });
    });

    /**Reset form */
    function resetFormData() {
        $('#postForm').trigger("reset");
        CKEDITOR.instances.content.setData('');
        document.getElementById('title').value = '';
        document.getElementById('slug').value = '';
        document.getElementById('image').value = '';
        document.getElementById('excerpt').value = '';
        document.getElementById('content').value = '';
        document.getElementById('preview-image').src = 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png';
        $('.error').remove();
    }
    document.getElementById('image').addEventListener('change', function(event) {
        var output = document.getElementById('preview-image');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        }
    });

    /**Update post */
    function updatePost() {
        event.preventDefault();
        $(".alert").remove();
        if (CKEDITOR.instances.content) {
            CKEDITOR.instances.content.updateElement();
        }
        var formData = new FormData(document.getElementById('postForm'));
        if ($('#postForm').parsley().validate()) {
            $.ajax({
                url: "{{ route('posts.update', ['id' => $post->id]) }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    showToast('Updated post successfully');
                    $('.error').remove();
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

    }
</script>
@endsection