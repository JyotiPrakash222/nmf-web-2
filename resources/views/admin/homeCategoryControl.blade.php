@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <style>
        #category-list,
        #sidebar-category-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #category-list li,
        #sidebar-category-list li {
            padding: 12px 16px;
            margin-bottom: 8px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: grab;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        #category-list li:hover,
        #sidebar-category-list li:hover {
            background-color: #e3e3e3;
        }

        #category-list li.sortable-chosen,
        #sidebar-category-list li.sortable-chosen {
            background-color: #d6f0ff;
            transform: scale(1.02);
        }

        #category-list li.sortable-ghost,
        #sidebar-category-list li.sortable-ghost {
            opacity: 0.5;
        }

        .headerList {
            padding: 10px;
            padding-left: 0px;
        }

        .locked {
            cursor: not-allowed !important;
            background-color: #f5f5f5 !important;
            opacity: 0.6;
        }
    </style>


    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                                <h3 class="card-title mb-0 fs-5">Home Category List</h3>
                                <div class="card-tool ">
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                {{-- <div class="row"> --}}
                                <div class="col-md-6">
                                    <h4 class="headerList">Main Category Sequence</h4>
                                    <ul id="category-list">
                                        @foreach ($categories as $category)
                                            <li class="d-flex gap-3 col-12 col-md-4 align-items-center --btn text-dark rounded {{ in_array($category->title, ['Section4', 'Section5']) ? 'locked' : '' }}"
                                                data-id="{{ $category->id }}" data-category-id="{{ $category->catid }}"
                                                data-locked="{{ in_array($category->title, ['Section4', 'Section5']) ? 'true' : 'false' }}">

                                                <span class="icon text-dark me-2"><i class="fas fa-grip-lines"></i></span>

                                                <div class="row w-100">
                                                    <div class="col-6">
                                                        {{ $category->title }}
                                                    </div>
                                                    <div class="col-6">
                                                        {{ $category->category->name ?? 'N/A' }}({{ $category->catid }})
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                {{-- </div> --}}
                                {{-- <div class="row mt-5"> --}}
                                <div class="col-md-6">
                                    <h4 class="headerList">Sidebar Category Sequence</h4>
                                    <ul id="sidebar-category-list">
                                        @foreach ($sidebarCategories as $sidebar)
                                            <li class="d-flex gap-3 col-12 col-md-4 align-items-center --btn text-dark rounded"
                                                data-id="{{ $sidebar->id }}" data-category-id="{{ $sidebar->catid }}">

                                                <span class="icon text-dark me-2"><i class="fas fa-grip-lines"></i></span>

                                                <div class="row w-100">
                                                    <div class="col-6">
                                                        {{ $sidebar->title }}
                                                    </div>
                                                    <div class="col-6">
                                                        {{ $sidebar->category->name ?? 'N/A' }}({{ $sidebar->catid }})
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                {{-- </div> --}}
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

        const categoryList = document.getElementById('category-list');
        const originalOrder = Array.from(categoryList.children);

        new Sortable(categoryList, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            filter: '.locked',
            preventOnFilter: false,
            onMove: function(evt) {
                return !isLocked(evt.related);
            },
            onEnd: function(evt) {
                // Restore locked items to their original positions
                const items = Array.from(categoryList.children);
                originalOrder.forEach((originalEl, index) => {
                    if (isLocked(originalEl)) {
                        categoryList.insertBefore(originalEl, items[index] || null);
                    }
                });

                // Prepare new order excluding locked items
                let order = [];
                Array.from(categoryList.children).forEach((el, index) => {
                    if (!isLocked(el)) {
                        order.push({
                            id: el.getAttribute('data-id'),
                            section_order: index + 1,
                        });
                    }
                });

                fetch('/home-category/sequence', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order,
                            type: 'main'
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

        new Sortable(document.getElementById('sidebar-category-list'), {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function(evt) {
                let sidebarOrder = [];
                document.querySelectorAll('#sidebar-category-list li').forEach((el, index) => {
                    sidebarOrder.push({
                        id: el.getAttribute('data-id'),
                        section_order: index + 1,
                    });
                });

                fetch('/home-category/sequence', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order: sidebarOrder,
                            type: 'sidebar'
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