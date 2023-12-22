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
    <div class="mt-4">
        <button class="btn btn-danger btn-sm delete-post-button">
            <i class="fas fa-trash-alt"></i> Delete
        </button>
    </div>


    <div class="post-list">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" id="selectAll"/>
                </th>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Slug</th>
                <th scope="col">status</th>
                <th scope="col">Action</th>

            </tr>
            </thead>
        </table>
    </div>

    <script>
        let table;
        let currentPage = 1;
        $(document).ready(function () {
            /** Data table */
            const columns = [
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return `<input type="checkbox" class="select-checkbox" value="${data}">`;
                    },
                    orderable: false,
                    searchable: false
                },
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'slug', name: 'slug'},
                {data: 'status', name: 'status'}
            ];
            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                ajax: "{{ route('categories.data') }}",
                columns: columns,
                columnDefs: [{
                    targets: columns.length,
                    render: function (data, type, row) {
                        return `<a class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit</a> `;

                    }
                }]
            });
            /**  checkbox select all */
            $('#selectAll').click(function () {
                const checkboxes = $(this).closest('table').find(':checkbox');
                checkboxes.prop('checked', $(this).is(':checked'));
            });

            $('table').on('click', '.select-checkbox', function () {
                const allCheckboxes = $(this).closest('table').find('.select-checkbox');
                const allChecked = allCheckboxes.length === allCheckboxes.filter(':checked').length;

                $('#selectAll').prop('checked', allChecked);
            });


            /** Button delete */
            $('.delete-post-button').click(function () {
                const ids = [];
                currentPage = table.page.info().page + 1;
                $('.select-checkbox:checked').each(function () {
                    ids.push($(this).val());
                });
                if (ids.length === 0) {
                    showToastWarning("Delete item has not been selected");
                } else {
                    deleteCategories(ids);
                }
            });

            /** Delete function */
            function deleteCategories(ids) {
                if (confirm('Are you sure you want to delete this post?')) {
                    $.ajax({
                        url: "{{ route('categories.massDelete') }}",
                        type: 'POST',
                        data: {
                            ids: ids,
                            '_token': "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            refreshTableAfterDeletion();
                            showToast("Deleted successfully");
                        },
                        error: function (xhr) {
                            alert(xhr);
                        }
                    });
                }
            }

            /** Refresh table */
            function refreshTableAfterDeletion() {
                table.page(currentPage - 1).draw('page');
                table.row('.selected').remove().draw(false);
                $('#selectAll').prop('checked', false);
            }
        });
    </script>

@endsection
