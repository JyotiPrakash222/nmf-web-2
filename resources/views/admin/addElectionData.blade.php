<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
@extends('layouts.adminNew')

@push('style')
<link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div>
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title mb-0 fs-5">Add Election Data</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('saveElectionData') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    
                                    <label for="party_name" class="form-label">Party Name</label>
                                    <input type="text" class="form-control" id="party_name" name="party_name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="abbreviation" class="form-label">Abbreviation</label>
                                    <input type="text" class="form-control" id="abbreviation" name="abbreviation" required>
                                </div>

                                <div class="mb-3">
                                    <label for="alliance" class="form-label">Alliance</label>
                                    <input type="text" class="form-control" id="alliance" name="alliance" required>
                                </div>

                                <div class="mb-3">
                                    <label for="seats_won" class="form-label">Seats Won</label>
                                    <input type="number" class="form-control" id="seats_won" name="seats_won" required>
                                </div>

                               

                                <button type="submit" class="--btn bg-dark fs-4 pb-3">Save</button>
                            </form>
                              @if(session('error'))
                                <div class="alert alert-danger mt-3">
                                    {{ session('error') }}
                                </div>
                            @endif  
                            @if(session('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
