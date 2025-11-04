<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

@extends('layouts.adminNew')

@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <style>
        .action_btn {
            padding-block: 3.5px;
            padding-inline: 8.5px;
            border-radius: 4px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
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

        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-2.5">
                                <h3 class="card-title mb-0 fs-5">Election Results</h3>

                                <div class="card-tool">
                                    <div class="input-group input-group-sm float-right">
                                        <a href="{{ route('addElection') }}" class="--btn" style="background: #28364f">
                                            ADD Election Data
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive px-5 py-2">
                                <div id="loader-overlay">
                                    <div class="loader"></div>
                                </div>

                              <table class="table text-nowrap image-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Party Name</th>
                                            <th>Abbreviation</th>
                                            <th>Alliance</th>
                                            <th>Seats Won</th>
                                            <th>Percentage</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($results as $result)
                                            <tr>
                                                <td>{{ $result->id }}</td>
                                                <td>{{ $result->party_name }}</td>
                                                <td>{{ $result->abbreviation }}</td>
                                                <td>{{ $result->alliance }}</td>
                                                <td>{{ $result->seats_won }}</td>
                                                <td>{{ $result->percentage ?? 'N/A' }}</td>
                                                <td>
                                                    <a class="act_btn" href="{{ asset('election/edit') }}/{{ $result->id }}">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <a class="act_btn" href="{{ asset('election/delete') }}/{{ $result->id }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Election Results Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- Display total at middle-bottom -->
                               <div class="card-tool d-flex align-items-center" style="margin: 10px 0;">
                                    <span style="background: #28364f; 
                                                color: #fff; 
                                                border-radius: 5px; 
                                                font-weight: bold; 
                                                padding: 6px 14px; 
                                                display: inline-block;">
                                        Total Seats Won: {{ $totalSeats }}
                                    </span>
                                </div>




                            </div>

    

                        </div><!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
