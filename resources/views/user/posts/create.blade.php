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
                        <form data-parsley-validate id="postForm" action="{{ route('posts.store') }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required
                                       data-parsley-trigger="keyup" data-parsley-minlength="3"
                                       data-parsley-maxlength="255">
                                <div id="titleError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" required
                                       data-parsley-trigger="keyup">
                                <div id="slugError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt</label>
                                <input type="text" class="form-control" id="excerpt" name="excerpt" required
                                       data-parsley-trigger="keyup">
                                <div id="excerptError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="5" required
                                          data-parsley-trigger="keyup" data-parsley-minlength="3"></textarea>
                                <div id="contentError" class="parsley-errors-list"></div>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label><br/>
                                <img class="post-image" id="preview-image"
                                     src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png"
                                     alt="Product Image" class="img-fluid mb-2">
                                <input type="text" class="form-control" id="image" name="image" readonly>
                                <button type="button" class="btn btn-info" onclick="openCKFinder()">Select Image
                                </button>
                                <div id="imageError"></div>
                            </div>

                            <div class="mb-3">
                                <label for="is_featured" class="form-label">Post Type</label>
                                <select class="form-select" id="is_featured" name="is_featured">
                                    <option value="NORMAL">NORMAL</option>
                                    <option value="FEATURED">FEATURED</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="category_id">Categories</label>
                                <select class="form-select" id="category_id" name="category_id" multiple data-parsley-required="true">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="button" class="btn btn-warning" onclick="resetFormData()">Reset</button>
                            <button type="button" class="btn btn-primary ml-4" onclick="createPost('CREATE')">Create
                            </button>
                            <button type="button" class="btn btn-success ml-4" onclick="createPost('CREATE_PUBLISH')">
                                Create And Publish
                            </button>
                            <input type="hidden" id="publishInput" name="publish">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        /** Select 2 */
        $('#category_id').select2();
        /**Ckeditor */
        CKEDITOR.replace('content');
        CKEDITOR.instances.content.on('change', function () {
            $('#content').val(CKEDITOR.instances.content.getData());

            $('#content').parsley().validate();
        });
        CKEDITOR.instances.content.on('change', function () {

            var errorMessage = $('#content').parsley().getErrorsMessages();
            if (errorMessage.length > 0) {
                $('#contentError').html(errorMessage[0]);
            } else {
                $('#contentError').empty();
            }
        });

        /**Reset Data */
        function resetFormData() {
            $('#postForm').trigger("reset");
            CKEDITOR.instances.content.setData('');
            document.getElementById('publishInput').value = '';
            document.getElementById('image').value = '';
            document.getElementById('content').value = '';
            document.getElementById('preview-image').src = 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png';
            $('.error').remove();
        }

        /**Validate */
        $(document).ready(function () {
            $('#postForm').parsley({
                errorsContainer: function (parsleyField) {
                    return parsleyField.$element.closest('.form-group');
                },
                errorsWrapper: '<div class="parsley-errors-list"></div>',
                errorTemplate: '<div></div>'
            }).on('field:validated', function (parsleyField) {
                if (parsleyField.validationResult === true) {
                    parsleyField.$element.removeClass('input-error');
                } else {
                    parsleyField.$element.addClass('input-error');
                }
            });
        });

        /** Create Post*/
        function createPost(action) {
            $(".alert").remove();
            document.getElementById('publishInput').value = action;
            $('#content').val(CKEDITOR.instances.content.getData());
            var postData = {
                title: $('#title').val(),
                content: $('#content').val(),
                slug: $('#slug').val(),
                excerpt: $('#excerpt').val(),
                image: $('#image').val(),
                is_featured: $('#is_featured').val(),
                category_id: $('#category_id').val(),
                publish: action
            };
            console.log(postData);
            if ($('#postForm').parsley().validate()) {
                console.log('success');
                $.ajax({
                    url: "{{ route('posts.store') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    data: JSON.stringify(postData),
                    contentType: "application/json",
                    success: function (response) {
                        action === "CREATE" ? showToast('created post successfully') :
                            showToast('created and publish post successfully');
                        $('.error').remove();
                        document.getElementById('publishInput').value = '';
                        console.log(response);
                    },
                    error: function (xhr, status, error) {
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
