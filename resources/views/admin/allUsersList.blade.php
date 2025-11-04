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
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                                <h3 class="card-title mb-0 fs-5">All USERS LIST</h3>
                                <div class="card-tool ">
                                    <div class="input-group input-group-sm float-right  ">
                                        <a href="{{ asset('alluserslist') }}/add" class="--btn"
                                            style="background: #28364f">
                                            ADD USER
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="dataTableExample_length">
                                        <label class="d-inline-flex gap-1 align-items-center">Show <select
                                                name="dataTableExample_length" aria-controls="dataTableExample"
                                                class="form-select select-down "
                                                onchange="window.location.href = '?perPage=' + this.value + '&page=1{{ isset($data['title']) && $data['title'] !== '' ? '&title=' . $data['title'] : '' }}';">
                                                <option value="30" <?php if ($data['perPage'] == '30') {
                                                    echo 'selected';
                                                } ?>>30</option>
                                                <option value="50" <?php if ($data['perPage'] == '50') {
                                                    echo 'selected';
                                                } ?>>50</option>
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
                                <div class="card-body table-responsive px-1 py-2 mt-1">
                                    <div id="loader-overlay">
                                        <div class="loader"></div>
                                    </div>
                                    <table class="table userlist-table text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>ID</th>
                                                <th>UserName</th>
                                                <th>User Type</th>
                                                <th>Enable</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($data['users']  as $user)
                                                <tr>
                                                    <td>
                                                        <a class="act_btn"
                                                            href="{{ asset('alluserslist/edit') }}/{{ $user->id }}"><i
                                                                class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        {{-- <a class="btn_del"
                                                            href="{{ asset('alluserslist/delete') }}/{{ $user->id }}"><i class="fa-solid fa-trash"></i>
                                                        </a> --}}
                                                        <a class="act_btn"
                                                            href="{{ asset('alluserslist/allusers-reset-password') }}/{{ $user->id }}"><i
                                                                class="fa-solid fa-rotate-left"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ $user->id }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->role_name }}</td>
                                                    <td class="mx-auto">
                                                        <div class="form-check form-switch ms-4">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="toggleActiveStatus{{ $user->id }}"
                                                                name="active_status_{{ $user->id }}"
                                                                {{ $user->status == 1 ? 'checked' : '' }}
                                                                onchange="updateUserActiveStatus({{ $user->id }}, this.checked)">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row pagination-block">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" id="dataTableExample_info" role="status"
                                            aria-live="polite">
                                            @if ($data['users']->count() > 0)
                                                Showing {{ $data['users']->firstItem() }} to {{ $data['users']->lastItem() }} of
                                                {{ $data['users']->total() }} entries
                                            @else
                                                Showing 0 entries
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="dataTableExample_paginate">
                                            <ul class="pagination">
                                                {{ $data['users']->links() }}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
<script>
    function updateUserActiveStatus(userId, isChecked) {
        const status = isChecked ? 1 : 0;

        // Show loader overlay
        document.getElementById('loader-overlay').style.display = 'flex';

        fetch('/alluserslist/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_id: userId,
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
