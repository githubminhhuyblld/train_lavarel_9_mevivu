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
                        <div style="max-height: 300px; overflow-y: auto;">
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
                    <div class="col col-lg-12 mt-4 pd-4 d-flex align-items-center ">
                        <span style="margin-right: 12px">Chọn Background</span>
                        <input class="pl-4" type="color" id="background-color-picker" name="background" value={{$menu->background}}>
                    </div>
                    <div class="col mt-4 col-lg-12 d-flex align-items-center ">
                        <span style="margin-right: 12px">Chọn Color</span>
                        <input class="pl-4" type="color" id="menu_color" name="menu_color" value={{$menu->menu_color}}>
                    </div>
                    <div class="col col-lg-12 d-flex align-items-center mt-3">
                        <label for="menu_font" style="margin-right: 12px">Chọn Font Size:</label>
                        <div class="d-flex align-items-center">
                            <input type="number" id="menu_font" name="menu_font" class="form-control" value={{$menu->menu_font}} min="10" max="36" style="width: 80px;">
                            <span class="ms-2">pt</span>
                        </div>
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
                let backgroundColor = $('#background-color-picker').val();
                let menuFont = $('#menu_font').val();
                let menuColor = $('#menu_color').val();
                let checkedItemsCount = $('.menu-item-checkbox:checked').length;
                if (checkedItemsCount === 0) {
                    showToastWarning('Vui lòng chọn ít nhất một menu.');
                    return;
                } else if (checkedItemsCount > 8) {
                    showToastWarning('Bạn chỉ có thể chọn tối đa 8 menu.');
                    return;
                }

                $('#selectedMenuList .list-group-item').each(function (index, element) {
                    sortMenu.push({id: $(element).data('id'), position: index + 1});
                });

                $.ajax({
                    url: '{{ route("menus.updateMenu") }}',
                    type: 'POST',
                    data: {
                        menu: sortMenu,
                        background: backgroundColor,
                        menu_font: menuFont,
                        menu_color: menuColor,
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
