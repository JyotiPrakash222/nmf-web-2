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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ asset('/home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ asset('/videofiles') }}">Video Files</a></li>
                            <li class="breadcrumb-item active">Edit Video File</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">Edit Video</h3>
                            </div>
                            <div class="uploads-container row">
                                <form method="post" class="uploads col-md-5"
                                    action="{{ asset('videofiles/edit') }}/{{ $file->id }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="uploads-box">
                                        <span class="-title">Upload Video</span>
                                        <p class="-paragraph px-5">
                                        <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                            <li>Only .mp4 video files are allowed*</li>
                                            <li class="text-left">File size must not exceed 100MB.</li>
                                            <!-- <li class="text-left">Video resolution must not exceed 1080p (1920x1080).</li> -->

                                        </ul>
                                        </p>

                                        <label for="file-input" class="drop-container">
                                            <span class="drop-title">Drop files here</span>
                                            or
                                            <input type="file" accept=".mp4" id="file-input" name="file"
                                                value="{{ isset($file->file_name) ? $file->file_name : '' }}">
                                        </label>
                                        </>
                                        @error('file')
                                            <div class="input-group-append">
                                                <div class="input-group-text text-danger">
                                                    {{ $message }}
                                                    <!-- Error message will display here (e.g., "The video file size must not exceed 100MB.") -->
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
@endsection
