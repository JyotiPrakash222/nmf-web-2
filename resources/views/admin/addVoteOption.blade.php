<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">Add Vote Option</h3>
                            </div>
                            <form class="form-group" method="post" action="{{ asset('vote/vote-option/add') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="input-field col-md-6">
                                            <input class="at-title" autocomplete="off" type="text" name="name"
                                                value="{{ old('name') }}" id="name"
                                                oninput="clearError('name-error')" />
                                            <label for="name">Vote Option Name <span
                                                    class="text-danger">*</span></label>
                                            @error('name')
                                                <div class="input-group-append" id="name-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('name') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        {{-- <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="option"
                                                id="option" oninput="clearError('option-error')" />
                                            <label for="option">Option Text</label>
                                            @error('option')
                                                <div class="input-group-append" id="option-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('option') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div> --}}
                                        {{-- </div>
                                    <div class="form-group row"> --}}
                                        <div class="input-field col-md-6">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="vote_id" oninput="clearError('vote_id-error')">
                                                <option value="0">Select Vote Title <span class="text-danger">*</span>
                                                </option>
                                                @foreach ($data['get_votes'] as $get_vote)
                                                    <option value="{{ $get_vote->id }}"
                                                        {{ old('vote_id') == $get_vote->id ? 'selected' : '' }}>
                                                        {{ $get_vote->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('vote_id')
                                                <div class="input-group-append" id="vote_id-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('vote_id') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Action button --}}
                                    <div class="button-container row mx-3">
                                        <button class="--btn btn-publish">SUBMIT</button>
                                    </div>
                    </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        function clearError(errorId) {
            const errorElement = document.getElementById(errorId);
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }
    </script>
@endsection
