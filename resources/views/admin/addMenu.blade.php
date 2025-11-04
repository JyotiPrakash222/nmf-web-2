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
                            <li class="breadcrumb-item"><a href="{{ asset('/menu') }}">Menus</a></li>
                            <li class="breadcrumb-item active">Add Menu</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section> --}}
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">Add Menu</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-group" method="post" aaction="{{ asset('menu/add') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="input-field col-md-4">
                                            <input placeholder="" autocomplete="off" type="text" name="name"
                                                id="name" oninput="clearError('name-error')"  value="{{ old('name') }}"/>
                                            <label for="name">Menu Name <span class="text-danger">*</span></label>
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
                                        <div class="input-field col-md-4">
                                            <input placeholder="" autocomplete="off" type="text" name="link"
                                                id="link" oninput="clearError('link-error')" value="{{ old('link') }}" />
                                            <label for="link">Menu Link <span class="text-danger">*</span></label>
                                            @error('link')
                                                <div class="input-group-append" id="link-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('link') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="input-field col-md-4 d-none">
                                            <input placeholder="" autocomplete="off" type="text" name="class"
                                                id="class" oninput="clearError('class-error')" />
                                            <label for="class">Menu Class</label>
                                            @error('class')
                                                <div class="input-group-append" id="class-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('class') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="input-field col-md-4">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="menu" oninput="clearError('menu-error')">
                                                <option value="0">Select Menus</option>
                                                @foreach ($data['menus'] as $menu)
                                                       <option value="{{ $menu->id }}" {{ old('menu') == $menu->id ? 'selected' : '' }}>
                                                            {{ $menu->menu_name }}
                                                        </option>
                                                @endforeach
                                            </select>
                                            @error('menu')
                                                <div class="input-group-append" id="menu-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('menu') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="input-field col-md-4">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="type" oninput="clearError('type-error')">
                                                <option value="0">Select Menu Types <span class="text-danger">*</span></option>
                                                @foreach ($data['types'] as $type)
                                                    <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>
                                                            {{ $type->type }}
                                                        </option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="input-group-append" id="type-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('type') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="input-field col-md-4">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="category" oninput="clearError('category-error')">
                                                <option value="0">Select Menu Category <span class="text-danger">*</span></option>
                                                @foreach ($data['categories'] as $category)
                                                       <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->category }}
                                                        </option>
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="input-group-append" id="category-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('category') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- --------------------------------------- Uploads container---------------------- --}}
                                {{-- <div class="uploads-container mt-0 row">
                                    <div class="uploads col-md-4" style="padding-top: 35px">
                                        <div class="uploads-box">
                                            <span class="-title">Upload Reel Video</span>
                                            <p class="-paragraph px-5">
                                            <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                <li>Only .mp4 video files are allowed*</li>
                                                <li class="text-left">File size must not exceed 50MB.</li>
                                            </ul>
                                            </p>
                                            <label for="file-input" class="drop-container" style="margin-top: 60px;">
                                                <span class="drop-title">Drop files here</span>
                                                or
                                                <input type="file" accept=".mp4" id="file-input" name="file"
                                                    oninput="clearError('file-error')">
                                            </label>
                                            </>
                                            @error('file')
                                                <div class="input-group-append" id="file-error">
                                                    <div class="input-group-text text-danger">
                                                        {{ $message }}
                                                        <!-- Error message will display here (e.g., "The video file size must not exceed 30MB.") -->
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="uploads col-md-4" style="padding-top: 35px">
                                        <div class="uploads-box">
                                            <span class="-title">Upload Thumbnail</span>
                                            <p class="-paragraph px-5">
                                            <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                <li>Only .jpeg, .jpg, and .png files are allowed*</li>
                                                <li class="text-left">File size does not exceed 5MB.</li>
                                            </ul>
                                            </p>
                                            <label for="file-input" class="drop-container " style="margin-top: 60px;">
                                                <span class="drop-title">Drop files here</span>
                                                or
                                                <input type="file" accept=".jpeg,.jpg,.png" name="thumbnail"
                                                    id="file-input" oninput="clearError('thumbnail-error')">
                                            </label>
                                            @error('thumbnail')
                                                <div class="input-group-append" id="thumbnail-error">
                                                    <div class="input-group-text text-danger">
                                                        {{ $message }}
                                                        <!-- Error message will display here (e.g., "The thumb image size must not exceed 5MB.") -->
                                                    </div>
                                                </div>
                                            @enderror
                                            </>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- Action button --}}

                                <div class="button-container row mx-3">
                                    <button class="--btn btn-publish">SUBMIT</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
