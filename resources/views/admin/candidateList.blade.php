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


        .switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }

        .switch input {
            display: none;
        }

        .slider2 {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider2:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider2 {
            background-color: #28a745;
        }

        input:checked+.slider2:before {
            transform: translateX(22px);
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
        .switch2 {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 24px;
        }

        .switch2 input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider2 {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d1d1d1;
            transition: 0.4s;
            border-radius: 24px;
        }

        .slider2::before {
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
        input:checked+.slider2 {
            background-color: #28a745;
        }

        input:checked+.slider2::before {
            transform: translateX(16px);
        }

        .row-act {
            width: 75px;
            display: flex;
            justify-content: space-between;
        }
    </style>
    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-2.5">
                                <h3 class="card-title mb-0 fs-5">Candidate List</h3>
                                <div class="card-tool ">
                                    <div class="input-group input-group-sm float-right  ">
                                        {{-- <a href="{{ asset('menu') }}/add" class="--btn" style="background: #28364f">
                                            Add Menu
                                        </a> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row px-sm-5 pt-sm-4 px-0 pt-0">
                                <div class="tb-responsive">

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>#</th>
                                                <th>Candidate Name</th>
                                                <th>Party</th>
                                                <th>Area</th>
                                                <th>Candidate's Status</th>
                                                <th>Image</th>
                                                {{-- <th>Status</th> --}}

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($candidates as $index => $candidate)
                                                <tr>
                                                    <td>
                                                        <div class="row-act">
                                                            <a class="act_btn edit"
                                                                href="{{ route('candidates.edit', $candidate->id) }}">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </a>

                                                            <form action="{{ route('candidate.destroy', $candidate->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this candidate?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="act_btn bg-transparent delete border-0"
                                                                    type="submit">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </form>

                                                            {{-- <form
                                                                action="{{ route('candidates.updateStatus', $candidate->id) }}"
                                                                method="POST" style="display:inline-block;">
                                                                @csrf
                                                                <label class="switch2">
                                                                    <input type="checkbox" name="status" value="1"
                                                                        {{ $candidate->is_active ? 'checked' : '' }}
                                                                        onchange="this.form.submit()">
                                                                    <span class="slider2"></span>
                                                                </label>

                                                            </form> --}}
                                                        </div>
                                                    </td>
                                                    

                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $candidate->candidate_name }}</td>
                                                    <td>{{ $candidate->party->party_name ?? 'N/A' }}</td>
                                                    <td>{{ $candidate->area }}</td>
                                                    {{-- candidates status --}}
                                                <td>
    <form
        action="{{ route('candidates.updateCandidateStatus', $candidate->id) }}"
        method="POST"
        class="d-flex align-items-center gap-2 candidate-status-form">
        @csrf
        <select name="status"
            class="form-select py-1 form-select-sm candidate-status"
            style="width:160px; font-size: 14px;">
            @foreach ($candidateStatuses as $status)
                <option value="{{ $status }}"
                    {{ $candidate->c_status == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>

        {{-- Initially hidden Save button --}}
        <button type="submit"
            class="btn btn-sm btn-dark px-3 py-1 save-btn"
            style="display: none;">
            Save
        </button>
    </form>
</td>

                                                    <td>
                                                        @if ($candidate->candidate_image)
                                                            <img class="object-cover"
                                                                src="{{ asset($candidate->candidate_image) }}"
                                                                width="50" height="50" style="object-fit: cover;">
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    {{-- <td>
                                                        <span
                                                            class="status {{ $candidate->is_active ? 'status-active' : 'status-inactive' }}">
                                                            {{ $candidate->is_active ? 'Active' : 'Inactive' }}
                                                        </span>

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
            </div>
        </div>
    </div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all dropdowns
    const statusDropdowns = document.querySelectorAll('.candidate-status');

    statusDropdowns.forEach(dropdown => {
        const form = dropdown.closest('.candidate-status-form');
        const saveBtn = form.querySelector('.save-btn');
        const originalValue = dropdown.value; // Store initial value

        dropdown.addEventListener('change', function() {
            // Show Save button only if value changed
            if (dropdown.value !== originalValue) {
                saveBtn.style.display = 'inline-block';
            } else {
                saveBtn.style.display = 'none';
            }
        });
    });
});
</script>
