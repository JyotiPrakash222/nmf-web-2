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

        .status-unpublished {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-archive {
            background-color: #e0e0e0;
            color: #555;
        }

        .status-unknown {
            background-color: #e2e3e5;
            color: #6c757d;
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
                                <h3 class="card-title mb-0 fs-5">Party seats</h3>
                                <div class="card-tool ">

                                </div>
                            </div>
                            <div class="row pt-4 px-5">


                                <!--<div class="group">
                                                                <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                                                                    <g>
                                                                        <path
                                                                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                                                                        </path>
                                                                    </g>
                                                                </svg>

                                                                <input id="query" class="input" type="search" placeholder="Search..."
                                                                    name="searchbar" />
                                                            </div>-->
                            </div>

                            <form action="/submit" method="post">
                                <div class="card-body py-2">
                                    <table class="table text-nowrap table-bordered">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>NDA</th>
                                                <th>RJD+</th>
                                                <th>JS</th>
                                                <th>OTH</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                                <td><input type="number" class="form-control" placeholder="Total seats"
                                                        name="seat1"></td>
                                                <td><input type="number" class="form-control" placeholder="Total seats"
                                                        name="seat2"></td>
                                                <td><input type="number" class="form-control" placeholder="Total seats"
                                                        name="seat2"></td>
                                                <td><input type="number" class="form-control" placeholder="Total seats"
                                                        name="seat3"></td>
                                            </tr>
                                        
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Submit Button -->
                                <div class="text-center p-3">
                                    <button type="submit" class="--btn pb-2" style="background: #28364f;">Submit</button>
                                </div>

                            </form>

                          
                            <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                                <div class="dataTables_paginate paging_simple_numbers" id="dataTableExample_paginate">
                                    <ul class="pagination">

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
    </div>
    </section>
    </div>
    @push('custom-scripts')
    @endpush
@endsection
