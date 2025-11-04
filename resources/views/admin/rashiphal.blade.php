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
        {{-- <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">Rashiphal List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section> --}}

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                                <h3 class="card-title mb-0 fs-5">Rashiphal List</h3>
                                <div class="card-tool ">
                                    <div class="input-group input-group-sm float-right  ">
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                {{-- <div class="col-sm-12 col-md-6">
                              <div class="dataTables_length" id="dataTableExample_length">
                                  <label class="d-inline-flex gap-1 align-items-center">Show <select
                                          name="dataTableExample_length" aria-controls="dataTableExample"
                                          class="form-select select-down ">
                                          <option value="10">10</option>
                                          <option value="30">30</option>
                                          <option value="50">50</option>
                                          <option value="-1">All</option>
                                      </select> entries</label>
                              </div>
                          </div> --}}
                                {{-- <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                              <form class="form-wrapper btn-group d-flex gap-2 ">
                                  <div class="group">

                                      <input id="query" class="input" type="text" placeholder="Enter Title"
                                          name="searchbar" />
                                  </div>
                                 
                                  <button class="btn btn-outline-primary" type="submit"><i
                                          class="fa-solid fa-magnifying-glass"></i></button>
                              </form>
                          </div> --}}
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive px-5 py-2">
                                <table class="table text-nowrap image-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Rashi</th>
                                            <th>Description</th>
                                            <th style="width: 150px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rashiphal as $rashi)
                                            <tr>
                                                <td>{{ $rashi->id }}</td>
                                                <td>{{ $rashi->name }}</td>
                                                <td>{{ $rashi->description }}
                                                </td>
                                                <td>
                                                    <a class="act_btn"
                                                        href="{{ asset('rashiphal/edit') }}/{{ $rashi->id }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row pagination-block px-5 pb-3">
                                <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dataTableExample_paginate">
                                        <ul class="pagination">
                                            {{ $rashiphal->links() }}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection
