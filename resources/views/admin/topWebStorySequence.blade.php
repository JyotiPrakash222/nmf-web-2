@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <style>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                                <h3 class="card-title mb-0 fs-5">Top News Webstory List</h3>
                                <div class="card-tool ">
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                <div id="loader-overlay">
                                    <div class="loader"></div>
                                </div>
                                <div class="col-md-12">
                                    <div
                                        class="form-check form-switch d-flex align-items-center p-2 border rounded bg-light shadow-sm mb-2">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="toggleActiveStatus{{ $homeSectionStatus->id }}"
                                            name="active_status_{{ $homeSectionStatus->id }}"
                                            style="margin-left: 0px;"
                                            {{ $homeSectionStatus->status == 1 ? 'checked' : '' }}
                                            onchange="updateShowWebStoryStatus({{ $homeSectionStatus->id }}, this.checked)">

                                        <label class="form-check-label ms-3 fw-semibold text-dark" style="font-size: 1rem;"
                                            for="toggleActiveStatus{{ $homeSectionStatus->id }}">
                                            Show Web Story in <span class="text-primary">Top News</span>
                                        </label>
                                    </div>

                                    <div class="col-md-12">
                                        <h4 class="headerList">Manage Sequence</h4>
                                        <ul id="webstory-list">
                                            @foreach ($webstories as $webstory)
                                                <li class="d-flex gap-3 col-12 col-md-4 align-items-center --btn text-dark rounded"
                                                    data-id="{{ $webstory->id }}"
                                                    data-category-id="{{ $webstory->categories_id }}">

                                                    <span class="icon text-dark me-2"><i
                                                            class="fas fa-grip-lines"></i></span>

                                                    <div class="row w-100">
                                                        <div class="col-6">
                                                            {{ $webstory->name }}
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
        new Sortable(document.getElementById('webstory-list'), {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function(evt) {
                let webstoryOrder = [];
                document.querySelectorAll('#webstory-list li').forEach((el, index) => {
                    webstoryOrder.push({
                        id: el.getAttribute('data-id'),
                        section_order: index + 1,
                    });
                });

                fetch('/webstory/top-webstory-update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order: webstoryOrder,
                            type: 'webstory'
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

    <script>
        function updateShowWebStoryStatus(sectionId, isChecked) {
            const status = isChecked ? 1 : 0;

            // Show loader overlay
            document.getElementById('loader-overlay').style.display = 'flex';
            fetch('/webstory/showWebStoryInTopUpdate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        section_id: sectionId,
                        active_status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    } else {
                        document.getElementById('loader-overlay').style.display = 'none';
                    }
                })
                .catch(error => {
                    document.getElementById('loader-overlay').style.display = 'none';
                });
        }
    </script>
@endsection
