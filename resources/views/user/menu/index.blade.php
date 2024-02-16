@extends('layouts.app')

@section('title', 'Menus')

@section('content')
    <div class=" mt-4">
        <a href="{{ route('menus.create') }}" class="btn btn-success text-white">Create Menu</a>
    </div>

    <!-- Checkbox List -->
    <div class="mt-4 border p-4">
        <form id="menuSelectionForm" data-parsley-validate>
            <div class="container border-primary">
                <div class="row">
                    <div class="col col-lg-5">
                        <div class="mb-2">
                            <label>Chọn Menu (1-8)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                Chọn tất cả
                            </label>
                        </div>
                        @foreach ($menuItems as $menuItem)
                            <div class="form-check">
                                <input class="form-check-input menu-item-checkbox" type="checkbox"
                                       value="{{ $menuItem->id }}"
                                       id="menuItemCheckbox{{ $menuItem->id }}" data-title="{{ $menuItem->title }}"
                                    {{ $menuItem->status === 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="menuItemCheckbox{{ $menuItem->id }}">
                                    {{ $menuItem->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="col col-lg-7">
                        <div id="selectedMenuList" class="list-group">
                            @foreach ($menuItems as $menuItem)
                                @if ($menuItem->status === 1)
                                    <div class="list-group-item" data-id="{{ $menuItem->id }}">
                                        {{ $menuItem->title }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col col-lg-12 d-flex align-items-center ">
                        <span style="margin-right: 12px">Chọn Background</span>
                        <input class="pl-4" type="color" id="background-color-picker" name="background" value="">
                    </div>

                </div>
            </div>
            <button type="submit" class="btn btn-secondary mt-4" form="menuSelectionForm">Save</button>
        </form>

    </div>





    <script>
        $(document).ready(function () {
            let sortMenu = [];
            let selectedMenuItems = new Set();

            function initializeOrUpdateSortable() {
                if (window.selectedMenuListSortable) {
                    window.selectedMenuListSortable.option('animation', 150);
                } else {
                    window.selectedMenuListSortable = Sortable.create(document.getElementById('selectedMenuList'), {
                        animation: 150,
                        onUpdate: function (/**Event*/evt) {
                            sortOrder = [];
                            $('#selectedMenuList .list-group-item').each(function (index, element) {
                                sortOrder.push({id: $(element).data('id'), position: index + 1});
                            });

                        }
                    });
                }
            }

            function updateSelectedMenuListAndCheckAll() {
                $('#selectedMenuList').empty();
                let allChecked = true;

                $('.menu-item-checkbox').each(function () {
                    var menuItemId = $(this).val();
                    var menuItemTitle = $(this).data('title');
                    if ($(this).is(':checked')) {
                        $('#selectedMenuList').append(`<div class="list-group-item" data-id="${menuItemId}">${menuItemTitle}</div>`);
                    } else {
                        allChecked = false;
                    }
                });

                $('#selectAll').prop('checked', allChecked);
                initializeOrUpdateSortable();
            }


            $('#selectedMenuList .list-group-item').each(function () {
                selectedMenuItems.add($(this).data('id').toString());
            });


            $('.menu-item-checkbox').change(function () {
                updateSelectedMenuListAndCheckAll();
            });

            $('#selectAll').change(function () {
                $('.menu-item-checkbox').prop('checked', this.checked);
                updateSelectedMenuListAndCheckAll();
            });

            updateSelectedMenuListAndCheckAll();


            $('#menuSelectionForm').on('submit', function (e) {
                e.preventDefault();

                let checkedItemsCount = $('.menu-item-checkbox:checked').length;
                if (checkedItemsCount === 0) {
                    showToastWarning('Vui lòng chọn ít nhất một menu.');
                    return;
                } else if (checkedItemsCount > 8) {
                    showToastWarning('Bạn chỉ có thể chọn tối đa 8 menu.');
                    return;
                }

                $('#selectedMenuList .list-group-item').each(function (index, element) {
                    sortMenu.push({ id: $(element).data('id'), position: index + 1 });
                });

                $.ajax({
                    url: '{{ route("menus.updateMenu") }}',
                    type: 'POST',
                    data: {
                        menu: sortMenu,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        showToast(response.message);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });


            initializeOrUpdateSortable();
        });
    </script>

@endsection
