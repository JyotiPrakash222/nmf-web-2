@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <style>
        #menu-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #menu-list li {
            padding: 12px 16px;
            margin-bottom: 8px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: grab;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        #menu-list li:hover {
            background-color: #e3e3e3;
        }

        #menu-list li.sortable-chosen {
            background-color: #d6f0ff;
            transform: scale(1.02);
        }

        #menu-list li.sortable-ghost {
            opacity: 0.5;
        }

        .locked {
            cursor: not-allowed !important;
            background-color: #f5f5f5 !important;
            opacity: 0.6;
        }
    </style>


    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">Menu Control List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                                <h3 class="card-title mb-0 fs-5">Menu Control List</h3>
                                <div class="card-tool ">
                                </div>
                            </div>
                            <div class="row pt-4 px-5">



                                <div class="row">
                                    <div class="col-md-12">
                                        <ul id="menu-list">
                                            @foreach ($menus as $menu)
                                                <li class="d-flex gap-3 col-12 col-md-4 align-items-center --btn text-dark rounded {{ in_array($menu->menu_name, ['होम']) ? 'locked' : '' }}"
                                                    data-id="{{ $menu->id }}"
                                                    data-locked="{{ in_array($menu->menu_name, ['होम']) ? 'true' : 'false' }}">
                                                    <span class="icon text-dark"><i class="fas fa-grip-lines"></i></span>
                                                    {{ $menu->menu_name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        function isLocked(el) {
            return el.dataset.locked === "true";
        }

        const menuList = document.getElementById('menu-list');
        const originalOrder = Array.from(menuList.children);

        new Sortable(menuList, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            filter: '.locked',
            preventOnFilter: false,
            onMove: function(evt) {
                return !isLocked(evt.related);
            },
            onEnd: function(evt) {
                // Restore locked items to original positions
                const items = Array.from(menuList.children);
                originalOrder.forEach((originalEl, index) => {
                    if (isLocked(originalEl)) {
                        menuList.insertBefore(originalEl, items[index] || null);
                    }
                });

                // Build new order excluding locked items
                let order = [];
                Array.from(menuList.children).forEach((el, index) => {
                    if (!isLocked(el)) {
                        order.push({
                            id: el.getAttribute('data-id'),
                            sequence_id: index + 1
                        });
                    }
                });

                fetch('/menusequence', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }
        });
    </script>
@endsection
