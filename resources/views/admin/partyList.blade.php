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



        .tb-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            /* smooth scroll on mobile */
        }

        .el-table {
            min-width: 600px;
            /* ensures scrollable area */
            border-collapse: collapse;
        }

        /* Simple toggle switch */
        .switch3 {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 24px;
        }

        .switch3 input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider3 {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            /* green for enable */
            transition: 0.4s;
            border-radius: 24px;
        }

        .slider3::before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        /* Checked state */
        input:checked+.slider3 {
            background-color: #28a745;
        }

        input:checked+.slider3::before {
            transform: translateX(16px);
        }

        .row-act {
            width: 75px;
            display: flex;
            justify-content: space-between;
        }
    </style>



    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-2.5">
                                <h3 class="card-title mb-0 fs-5">PARTY LIST</h3>
                                <a href="{{ route('addParty') }}" class="--btn pt-2" style="background:#28364f">ADD
                                    PARTY</a>
                            </div>

                            <div class="tb-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Party Name</th>
                                            <th>Abbreviation</th>
                                            <th>Alliance</th>
                                            <th>Logo</th>
                                            {{-- <th>Party Status</th> --}}
                                            <th>Action</th>
                                            {{-- <th>Enable</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($parties as $index => $party)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $party->party_name }}</td>
                                                <td>{{ $party->abbreviation ?? 'N/A' }}</td>
                                                <td>{{ $party->alliance ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($party->party_logo)
                                                        <img src="{{ asset($party->party_logo) }}" width="50"
                                                            height="50">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>

                                                <!-- Status Badge -->
                                                {{-- <td>
                                                    <span
                                                        class="status {{ $party->status == 1 ? 'status-active' : 'status-inactive' }}">
                                                        {{ $party->status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td> --}}

                                                <!-- Action Buttons: View + Edit -->
                                                <td>
                                                    <div class="row-act">
                                                        <a class="act_btn" href="{{ route('party.edit', $party->id) }}">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>

                                                        <form action="{{ route('party.destroy', $party->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this party?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="act_btn p-0 border-0" type="submit">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>


                                                <!-- Toggle Enable -->
                                                {{-- <td>
                                                    <form action="{{ route('party.updateStatus', $party->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <label class="switch3 ms-4">
                                                            <input type="checkbox" name="status" value="1"
                                                                {{ $party->status ? 'checked' : '' }}
                                                                onchange="this.form.submit()">
                                                            <span class="slider3"></span>
                                                        </label>
                                                    </form>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
