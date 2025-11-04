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
                                <h3 class="card-title mb-0 fs-5">VIDEO LIST</h3>

                                <div class="card-tool ">
                                    <div class="input-group input-group-sm float-right  ">

                                        <a href="{{ asset('videofiles') }}/add" class="--btn" style="background: #28364f">
                                            ADD VIDEO FILE
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="dataTableExample_length">
                                        <label class="d-inline-flex gap-1 align-items-center">Show <select
                                                name="dataTableExample_length" aria-controls="dataTableExample"
                                                class="form-select select-down"
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
                                                name="searchbar" />
                                        </div>

                                        <button class="btn btn-outline-primary" type="submit"><i
                                                class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- /.card-header -->
                                <div class="card-body table-responsive px-1 py-2">
                                    <table class="table text-nowrap video-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>File Name</th>
                                                <th>Video Link</th>
                                                <th>File Size</th>
                                                <th>File Type</th>
                                                <th>Duration</th>
                                                <th>Preview</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($data['files']) > 0)
                                                @foreach ($data['files'] as $file)
                                                    <?php
                                                    $videoPath = $file->full_path;
                                                    // $videoFilePath = $videoPath;
                                                    if (strpos($videoPath, 'file') !== false) {
                                                        $findFilePos = strpos($videoPath, 'file');
                                                        $videoFilePath = substr($videoPath, $findFilePos);
                                                        $videoFilePath = $videoFilePath . '/' . $file->file_name;
                                                    }
                                                    ?>
                                                    <tr>
                                                        @if ($file->file_type == 'video/mp4')
                                                            <td>{{ $file->id }}</td>
                                                            <td>{{ $file->file_name }}</td>
                                                            <td>
                                                                <a href="{{ asset($videoFilePath) }}" target="_blank">
                                                                    {{ asset($videoFilePath) }} </a>
                                                            </td>
                                                            <td>
                                                                <div class="status file-siz-status">
                                                                    {{ $file->formatted_file_size }}</div>
                                                            </td>
                                                            <td>{{ $file->file_type }}</td>
                                                            <td>{{ $file->duration }}</td>
                                                            <td>
                                                                <div class="video-preview">
                                                                    <video width="100%" height="100%" controls>
                                                                        <source src="{{ asset($videoFilePath) }}"
                                                                            type="video/mp4">
                                                                    </video>
                                                                </div>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <!-- <a class="action_btn btn_view" >View</a> -->
                                                                <a class="act_btn"
                                                                    href="{{ asset('videofiles/edit') }}/{{ $file->id }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                                {{-- <a class="act_btn"
                                                                    href="{{ asset('videofiles/delete/') }}/{{ $file->id }}"><i class="fa-solid fa-trash"></i></a> --}}
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr colspan="5">
                                                    <td>No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

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
        </section>
    </div>
@endsection
