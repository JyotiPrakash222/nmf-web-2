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
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <!-- Main content -->
        <section class="content ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">ADD Trending Topic Tag</h3>
                            </div>
                            <div class="uploads-container row">
                                <form action="{{ asset('posts/trendingtopictag/add') }}" method="post" class="uploads col-md-6 mt-3"
                                    style="max-width: 500px">
                                    @csrf
                                    <div class=" input-field mb-4">
                                        <input placeholder="" autocomplete="off" type="text" name="name"
                                            value="{{ old('name') }}" id="name" oninput="clearError('name-error')">
                                        <label for="name">Trending Tag Name <span class="text-danger">*</span></label>
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
                                    <button class="--btn btn-submit mt-4 " type="submit">Submit</button>
                                </form>
                            </div>
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
