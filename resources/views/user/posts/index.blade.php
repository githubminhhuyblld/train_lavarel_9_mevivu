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
                    <input class="form-check-input" type="checkbox" id="selectAll"/>
                </th>
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
        let table;
        let currentPage = 1;
        /** Data table */
        $(document).ready(function () {
            const columns = [
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return `<input  type="checkbox" class="select-checkbox form-check-input" value="${data}">`;
                    },
                    orderable: false,
                    searchable: false
                },
                {
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
                    render: function (data, type, row) {
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
            ]
            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                bFilter: false,
                ajax: {
                    url: "{{ route('posts.data') }}",
                    data: function (d) {
                        d.title = $('#searchTitle').val();
                        d.excerpt = $('#excerpt').val();
                    }
                },
                columns: columns,
                columnDefs: [{
                    targets: columns.length,
                    render: function (data, type, row) {
                        return `<a href="/posts/${row.id}/edit" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit</a>`;
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

            /**  button search */
            $('#searchButton').on('click', function () {
                table.draw();
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
                    deletePosts(ids);
                }
            });

            /** Delete function */
            function deletePosts(ids) {
                if (confirm('Are you sure you want to delete this post?')) {
                    $.ajax({
                        url: "{{ route('posts.massDelete') }}",
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

                table.ajax.reload(function (json) {
                    if (json.recordsTotal <= 0) {
                        $('#searchTitle').val('');
                        $('#excerpt').val('');
                        table.search('').draw();
                    }
                }, false);

                $('#selectAll').prop('checked', false);
            }
        });

    </script>

@endsection
