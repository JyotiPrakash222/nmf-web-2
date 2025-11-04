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

        .drag_webstorys li {
            padding: 6px 16px;
            width: 100%;
            font-size: 14px;
            line-height: 31px;
            min-height: 30px;
            height: 100%;
            margin-bottom: 10px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: grab;
            white-space: normal;
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

        .form-select {
            padding: 12px 32px 8px 14px;
        }

        #webstory-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #webstory-list li {
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

        #webstory-list li:hover {
            background-color: #e3e3e3;
        }

        #webstory-list li.sortable-chosen {
            background-color: #d6f0ff;
            transform: scale(1.02);
        }

        #webstory-list li.sortable-ghost {
            opacity: 0.5;
        }

        .headerList {
            padding: 10px;
            padding-left: 0px;
        }

        .drag-handle {
            cursor: grab;
        }

        .sortable tr {
            transition: background-color 0.2s ease;
            cursor: grab;
        }

        .sortable tr.dragging {
            background-color: #f0f0f0;
        }

        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            /* <-- nice semi-transparent black */
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        .form-check-input {
            width: 2.5em !important;
            height: 1.5em !important;
        }
    </style>
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                                <h3 class="card-title mb-0 fs-5">Short Video List</h3>
                                <div class="card-tool ">
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                <div class="row">
                                    <div id="loader-overlay">
                                        <div class="loader"></div>
                                    </div>
                                    <div class="col-md-12">

                                        <ul class="drag_webstorys" id="menu-list">
                                            @foreach ($clips as $item)
                                                <li class="d-flex gap-3 col-12 col-md-9 align-items-center --btn text-dark rounded"
                                                    data-id="{{ $item->id }}">
                                                    <span class="icon text-dark">
                                                        <i class="fas fa-grip-lines"></i>
                                                    </span>

                                                    {{-- Title --}}
                                                    {{ $item->SortOrder }}. {{ $item->title }}

                                                    @if ($loop->iteration > 10)
                                                        <button onclick="removeSequence({{ $item->id }})"
                                                            class="btn btn-sm btn-danger"
                                                            style="margin-left:10px;">&times;</button>
                                                    @endif
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
    </div>
    </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuList = document.getElementById('menu-list');
            if (menuList) {
                new Sortable(menuList, {
                    animation: 150,
                    onEnd: function() {
                        const order = Array.from(document.querySelectorAll('#menu-list li')).map((el,
                            index) => ({
                            id: el.getAttribute('data-id'),
                            sequence_id: index + 1
                        }));

                        fetch('{{ route('updateReelVideoSequence') }}', {
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
                                if (data.success) location.reload();
                            }).catch(err => console.error('Order update failed:', err));
                    }
                });
            }
        });

        // --- Remove Sequence ---
        function removeSequence(itemId) {
            if (confirm("Are you sure you want to reset the sequence?")) {
                fetch(`/reel-videos/sequence/reset/${itemId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`[data-id='${itemId}']`)?.remove();
                            location.reload();
                        } else {
                            alert("Failed to update sequence.");
                        }
                    });
            }
        }
    </script>
@endsection
