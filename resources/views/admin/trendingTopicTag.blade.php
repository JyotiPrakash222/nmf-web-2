@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <style>
        /* action buttons */
        .action_btn {
            padding-block: 3.5px;
            padding-inline: 8.5px;
            border-radius: 4px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;

        }

        .btn_view {
            background-color: #0381cf;

            &:hover {
                background-color: #0577bd;
                color: #fff;
            }

        }

        .btn_edit {
            background-color: #0381cf;
            margin-left: 1.5px
        }

        .btn_edit:hover {
            background-color: #0577bd;
            color: #fff;
        }

        .btn_delete {
            background-color: #ff0000;
            margin-left: 1.5px
        }

        .btn_delete:hover {
            background-color: #ca0808;
            color: #fff;
        }

        @media (max-width: 500px) {
            .btn_edit {
                margin-left: 5px
            }

            .btn_delete {
                margin-left: 5px
            }
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
    </style>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                                <h3 class="card-title mb-0 fs-5">Trending Tags Control List</h3>
                                <div class="card-tool ">
                                    <div class="input-group input-group-sm float-right  ">
                                        <a href="{{ asset('posts/trendingtopictag') }}/add" class="--btn"
                                            style="background: #28364f">
                                            Add Trending Tag
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                 <div class="col-sm-12 col-md-6 d-flex justify-content-start">
                                    <form class="form-wrapper btn-group d-flex gap-2 ">
                                        <div class="group">
                                            <input id="query" class="input" type="text" placeholder="Enter Title"
                                                value="{{ $data['title'] }}" name="title" />
                                        </div>
                                        <button class="btn btn-outline-primary" type="submit"><i
                                                class="fa-solid fa-magnifying-glass"></i></button>
                                    </form>
                                </div>

                                <!-- /.card-header -->
                                <div class="card-body table-responsive px-1 py-2">
                                    <div id="loader-overlay">
                                        <div class="loader"></div>
                                    </div>
                                    <table class="table text-nowrap webstory-file-list">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                {{-- <th>Action</th> --}}
                                                <th>Enable</th>
                                            </tr>
                                        </thead>
                                        <tbody id="trending-tag-tbody" class="sortable">
                                            @if (count($data['tags']) > 0)
                                                @foreach ($data['tags'] as $index => $tag)
                                                    <tr data-id="{{ $tag['id'] }}">
                                                        <td>{{ $tag['name'] }}</td>
                                                        {{-- <td class="text-nowrap">
                                                            <a class="act_btn"
                                                                href="{{ asset('posts/trendingtopictag/edit') }}/{{ $index }}?t={{ time() }}">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </a>
                                                        </td> --}}
                                                        <td class="mx-auto">
                                                            <div class="form-check form-switch ms-4">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="toggleTagStatus{{ $index }}"
                                                                    name="active_status_{{ $index }}"
                                                                    {{ $tag['status'] ? 'checked' : '' }}
                                                                    onchange="updateTagStatus('{{ $tag['name'] }}', this.checked)">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3">No Trending Tags Found</td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
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
        function updateTagStatus(tag, isChecked) {
            const status = isChecked ? 1 : 0;

            // Show loader overlay
            document.getElementById('loader-overlay').style.display = 'flex';

            fetch('/posts/trendingtopictag/update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        tag: tag,
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('trending-tag-tbody');

            new Sortable(tableBody, {
                animation: 200,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function() {
                    let trendingTagOrder = [];

                    document.querySelectorAll('#trending-tag-tbody tr').forEach((row, index) => {
                        trendingTagOrder.push({
                            trendingTag_id: row.getAttribute('data-id'),
                            position: index + 1,
                        });
                    });

                    fetch('/posts/trendingtopictag/trending-tag-sequence-update', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                trendingTags: trendingTagOrder
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        })
                        .catch(err => console.error('Order update failed:', err));
                }
            });
        });
    </script>

@endsection
