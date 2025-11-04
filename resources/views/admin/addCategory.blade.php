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
        {{-- <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ asset('/home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ asset('/categories') }}">Category</a></li>
                            <li class="breadcrumb-item">Add Category</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section> --}}
        <!-- Main content -->
        <!-- Main content -->
        <section class="content ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header py-2.5">
                                <h3 class="card-title mb-0 fs-5">ADD Category</h3>
                            </div>
                            <div class="uploads-container row px-1">
                                <form method="post" action="{{ asset('categories/add') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row ">
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="name"
                                                value="{{ old('name') }}" id="name"
                                                oninput="clearError('name-error')">
                                            <label for="name">Category Name <span class="text-danger">*</span></label>
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
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="eng_name"
                                                value="{{ old('eng_name') }}" id="eng_name"
                                                oninput="clearError('eng_name-error')">
                                            <label for="name">Category English Name <span
                                                    class="text-danger">*</span></label>
                                            @error('eng_name')
                                                <div class="input-group-append" id="eng_name-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('eng_name') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class=" input-field col-md-6">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="category">
                                                <option value="0">Select Category</option>
                                                @foreach ($data['categories'] as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('type') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        {{-- <div class=" input-field col-md-6">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="file">
                                                <option value="0">Select File</option>
                                                @foreach ($data['files'] as $file)
                                                    <option value="{{ $file->id }}">{{ $file->file_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('file')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('file') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div> --}}
                                    </div>
                                    <div class="checkbox-wrapper-46">
                                        <input type="checkbox" id="cbx-46" name="home_page_status" value="1"
                                            {{ old('home_page_status') ? 'checked' : '' }} class="inp-cbx" />
                                        <label for="cbx-46" class="cbx"><span>
                                                <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                </svg></span><span>Display on Homepage</span>
                                        </label>
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
