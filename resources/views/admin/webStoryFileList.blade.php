<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-2.5">
                                <h3 class="card-title mb-0 fs-5">Web Story File List</h3>
                                <div class="card-tool ">
                                    <div class="input-group input-group-sm float-right  ">
                                        <a href="{{ asset('webstory/webstory-files/add?id=' . $data['webstory_id']) }}"
                                            class="--btn" style="background: #28364f">
                                            Add Web Story File
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="dataTableExample_length">
                                        <label class="d-inline-flex gap-1 align-items-center">Show <select name="files"
                                                aria-controls="dataTableExample" class="form-select select-down "
                                                onchange="window.location.href = '?perPage=' + this.value + '&page=1{{ isset($data['title']) && $data['title'] !== '' ? '&title=' . $data['title'] : '' }}';">
                                                <option value="20" <?php if ($data['perPage'] == '20') {
                                                    echo 'selected';
                                                } ?>>20</option>
                                                <option value="30" <?php if ($data['perPage'] == '30') {
                                                    echo 'selected';
                                                } ?>>30</option>
                                            </select> entries</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
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
                                    <table class="table text-nowrap webstory-file-list">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <span class="drag-handle"><i class="fa-solid fa-bars"></i></span>
                                                </th>
                                                <th>ID</th>
                                                <th>File Name</th>
                                                <th class="text-nowrap">Web Stories Id</th>
                                                <th>Description</th>
                                                <th>Preview</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="webstory-file-tbody" class="sortable">
                                            @if (count($data['files']) > 0)
                                                @foreach ($data['files'] as $file)
                                                    <?php
                                                    $imagePath = $file->filepath;
                                                    if (strpos($imagePath, 'file') !== false) {
                                                        $findFilePos = strpos($imagePath, 'file');
                                                        $imageFilePath = substr($imagePath, $findFilePos);
                                                        $imageFilePath = $imageFilePath . '/' . $file->filename;
                                                    }
                                                    ?>
                                                    <tr data-id="{{ $file->id }}">
                                                        <td class="drag-handle"><i class="fa-solid fa-bars"></i></td>
                                                        <td>{{ $file->id }}</td>
                                                        <td>{{ $file->filename }}</td>
                                                        {{-- <td>
                                                            <div class="status file-siz-status">
                                                                {{ $file->formatted_file_size }}</div>
                                                        </td> --}}
                                                        <td>{{ $file->webstories_id }}</td>
                                                        <td>{{ $file->description }}</td>
                                                        <td>
                                                            <div class="webstory-prev" controls>
                                                                <img src="{{ asset($imageFilePath) }}" />
                                                            </div>
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <a class="act_btn"
                                                                href="{{ asset('webstory/webstory-files/edit') }}/{{ $file->id }}?t={{ time() }}">
                                                                <i class="fa-solid fa-pen-to-square"></i></a>
                                                            <a class="act_btn"
                                                                href="{{ asset('webstory/webstory-files/delete/') }}/{{ $file->id }}" onclick="openDeleteModal(this,'Are you sure you want to delete this item permanently?'); return false;"><i class="fa-solid fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr colspan="5">
                                                    <td>No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    @if (count($data['files']) > 0)
                                        <div class="text-end mt-3">
                                            <form method="POST"
                                                action="{{ route('webstory.publish', ['id' => $data['webstory_id'] ?? 0]) }}">
                                                @csrf
                                                <button type="submit" class="--btn"
                                                    style="background: #28364f; opacity: {{ isset($data['status']) && $data['status'] === 1 ? '0.5' : '1' }};"
                                                    @if (isset($data['status']) && $data['status'] === 1) disabled @endif>
                                                    <i class="fa-solid fa-cloud-upload-alt"></i> Publish Webstory
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                                <div class="row pagination-block">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" id="dataTableExample_info" role="status"
                                            aria-live="polite">
                                            @if ($data['files']->count() > 0)
                                                Showing {{ $data['files']->firstItem() }} to
                                                {{ $data['files']->lastItem() }} of {{ $data['files']->total() }} entries
                                            @else
                                                Showing 0 entries
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="dataTableExample_paginate">
                                            <ul class="pagination">
                                                {{ $data['files']->links() }}
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
@endsection

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.getElementById('webstory-file-tbody');

        new Sortable(tableBody, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                let webstoryFileOrder = [];

                document.querySelectorAll('#webstory-file-tbody tr').forEach((row, index) => {
                    webstoryFileOrder.push({
                        webstoryFile_id: row.getAttribute('data-id'),
                        position: index + 1,
                    });
                });

                fetch('/webstory/file-sequence-update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            webstoryFiles: webstoryFileOrder
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
