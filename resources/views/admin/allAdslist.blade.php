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
                                <h3 class="card-title mb-0 fs-5">Advertisement List</h3>
                                <div class="card-tool ">
                                    <div class="input-group input-group-sm float-right  ">
                                        <a href="{{ asset('ads') }}/add" class="--btn"
                                            style="background: #28364f">
                                            New Advertise
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="dataTableExample_length">
                                        <label class="d-inline-flex gap-1 align-items-center">Show <select name="webstories"
                                                aria-controls="dataTableExample" class="form-select select-down "
                                                onchange="window.location.href = '?perPage=' + this.value + '&page=1{{ isset($location) && $location !== '' ? '&location=' . $location : '' }}';">
                                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                                <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                                            </select> entries</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                    <form class="form-wrapper btn-group d-flex gap-2 ">
                                        <div class="group">
                                            <input id="query" class="input" type="text" placeholder="Enter Location"
                                                value="{{ $location }}" name="location" />
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
                                    <table class="table text-nowrap webs-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Location</th>
                                                <th>Page</th>
                                                <th>Ads Type</th>
                                                <th>Client Id</th>
                                                <th>Slot Id</th>
                                                <th>Custom File</th>
                                                <th>Custom Link</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($ads) > 0)
                                                @foreach ($ads as $ad)
                                                    <tr>
                                                        <td>{{ $ad->id }}</td>
                                                        <td>{{ $ad->location }}</td>
                                                        <td>{{ ucfirst(strtolower($ad->page_type)) }}</td>
                                                        <td>{{ $ad->is_google_ad == 1 ? 'Google' : 'Custom' }}</td>
                                                        <td>{{ $ad->google_client }}</td>
                                                        <td>{{ $ad->google_slot }}</td>
                                                        <td>
                                                            @if(!empty($ad->custom_image) && file_exists(public_path($ad->file_path . '/' . $ad->custom_image)))
                                                                <a href="{{ asset($ad->file_path . '/' . $ad->custom_image) }}" target="_blank">{{ $ad->custom_image }}</a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!empty($ad->custom_link))
                                                                <a href="{{ $ad->custom_link }}" target="_blank">{{ $ad->custom_link }}</a>
                                                            @endif
                                                        </td>
                                                        
                                                        <td class="text-nowrap">
                                                            <a class="act_btn"
                                                                href="{{ asset('ads/edit') }}/{{ $ad->id }}?t={{time()}}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                            {{-- <a class="act_btn"
                                                                href="{{ asset('webstory/delete/') }}/{{ $webstory->id }}" onclick="openDeleteModal(this,'Are you sure you want to delete this item permanently?'); return false;"><i class="fa-solid fa-trash"></i></a> --}}
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
                                </div>

                                <div class="row pagination-block">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" id="dataTableExample_info" role="status"
                                            aria-live="polite">
                                            @if ($ads->count() > 0)
                                                Showing {{ $ads->firstItem() }} to
                                                {{ $ads->lastItem() }} of
                                                {{ $ads->total() }} entries
                                            @else
                                                Showing 0 entries
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="dataTableExample_paginate">
                                            <ul class="pagination">
                                                {{ $ads->links() }}
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