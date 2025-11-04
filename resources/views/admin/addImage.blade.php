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
                            <div class="card-header py-2.5">
                                <h3 class="card-title mb-0 fs-5">ADD IMAGE</h3>
                            </div>
                            <div class="uploads-container row">
                                <form method="post" class="uploads col-md-5" action="{{ asset('files/add') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="uploads-box">
                                        <span class="-title">Upload Image</span>
                                        <p class="-paragraph px-5">
                                        <ul class="-paragraph-content text-start" style="color: #ff3131;">
                                            <li>Only .jpeg, .jpg, and .png files are allowed*</li>
                                            <li class="text-left">The image size must not exceed 200 KB.</li>
                                            <li class="text-left">Image dimension should be (800x450) px.</li>
                                        </ul>
                                        </p>

                                        <label for="file-input" class="drop-container">
                                            <span class="drop-title">Drop files here</span>
                                            or
                                            <input type="file" accept=".jpeg,.jpg,.png" name="file" id="file-input">
                                        </label>
                                        @error('file')
                                            <div class="input-group-append" id="file-error">
                                                <div class="input-group-text text-danger">
                                                    {{ $message }}
                                                    <!-- Error message will display here (e.g., "The thumb image size must not exceed 200 KB.") -->
                                                </div>
                                            </div>
                                        @enderror
                                        </>
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
@endsection
