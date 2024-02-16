@extends('layouts.app')

@section('title', 'Create New Post')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-4">
                    <div class="card-header">Create a New Post</div>
                    <div class="back">
                        <a class="btn btn-secondary" href="{{ route('menus.index') }}">Back</a>
                    </div>
                    <div class="card-body">
                        <form data-parsley-validate
                              id="postForm"
                              action="{{ route('menus.store') }}"
                              method="POST"
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


                            <button type="button" class="btn btn-success ml-4" onclick="create()">
                                Create
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        /** Create */
        function create() {
            $(".alert").remove();
            var data = {
                title: $('#title').val(),
                slug: $('#slug').val()

            };
            if ($('#postForm').parsley().validate()) {
                $.ajax({
                    url: "{{ route('menus.store') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    data: JSON.stringify(data),
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
