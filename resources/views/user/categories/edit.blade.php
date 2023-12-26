@extends('layouts.app')

@section('Edit', 'Edit Category')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">Edit Category</div>
                <div class="card-body">
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="{{ route('categories.index') }}">Back</a>
                    </div>
                    <form id="postForm" action="{{ route('categories.update', ['id' => $category->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Title</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name" required
                                   value="{{ $category->name }}"
                                   data-parsley-trigger="keyup"
                                   data-parsley-minlength="3"
                                   data-parsley-maxlength="255">
                            <div id="nameError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text"
                                   class="form-control"
                                   id="slug" name="slug"
                                   value="{{ $category->slug }}"
                                   required
                                   data-parsley-trigger="keyup"
                                   data-parsley-minlength="3"
                                   data-parsley-maxlength="255">
                            <div id="slugError"></div>
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
        document.getElementById('name').value = '';
        document.getElementById('slug').value = '';
        $('.error').remove();
    }

    /**Update Category */
    function updatePost() {
        event.preventDefault();
        $(".alert").remove();

        const data = {
            name: $('#name').val(),
            slug: $('#slug').val(),
        };
        if ($('#postForm').parsley().validate()) {
            $.ajax({
                url: "{{ route('categories.update', ['id' => $category->id]) }}",
                method: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function(response) {
                    showToast('Updated category successfully');
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
