@extends('layouts.app')

@section('title', 'Create New Post')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-4">
                    <div class="card-header">Create a New Category</div>
                    <div class="back">
                        <a class="btn btn-secondary" href="{{ route('categories.index') }}">Back</a>
                    </div>
                    <div class="card-body">
                        <form data-parsley-validate
                              action="{{ route('posts.store') }}"
                              id="postForm"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                       data-parsley-trigger="keyup" data-parsley-minlength="3"
                                       data-parsley-maxlength="255">
                                <div id="nameError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" required
                                       data-parsley-trigger="keyup">
                                <div id="slugError"></div>
                            </div>
                            <button type="button" class="btn btn-warning" onclick="resetFormData()">Reset</button>
                            <button type="button" class="btn btn-primary ml-4" onclick="createPost()">Create
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        /**Reset Data */
        function resetFormData() {
            $('#postForm').trigger("reset");
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
        function createPost() {
            $(".alert").remove();
            var postData = {
                name: $('#name').val(),
                slug: $('#slug').val()

            };
            if ($('#postForm').parsley().validate()) {
                console.log('success');
                $.ajax({
                    url: "{{ route('categories.store') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    data: JSON.stringify(postData),
                    contentType: "application/json",
                    success: function (response) {
                        showToast('created  successfully');
                        $('.error').remove();
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
