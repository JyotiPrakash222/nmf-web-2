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
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">ADD Reels</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-group" method="post" aaction="{{ asset('reel-videos/add') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="name"
                                                value="{{ old('name') }}" id="name"
                                                oninput="clearError('name-error')" />
                                            <label for="name">Clip Title <span class="text-danger">*</span></label>
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
                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="eng_name"
                                                value="{{ old('eng_name') }}" id="eng_name"
                                                oninput="clearError('eng_name-error')" />
                                            <label for="eng_name">Clip Title (English) <span
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
                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="description"
                                                value="{{ old('description') }}" id="description"
                                                oninput="clearError('description-error')" />
                                            <label for="description">Description <span class="text-danger">*</span></label>
                                            @error('description')
                                                <div class="input-group-append" id="description-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('description') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="subtitle"
                                                value="{{ old('subtitle') }}" id="subtitle"
                                                oninput="clearError('subtitle-error')" />
                                            <label for="subtitle">Sub Title</label>
                                            @error('subtitle')
                                                <div class="input-group-append" id="subtitle-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('subtitle') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="input-field col-md-4">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="category" oninput="clearError('category-error')">
                                                <option value="0">Select Category <span class="text-danger">*</span>
                                                </option>
                                                @foreach ($data['categories'] as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
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
                                        <div class="input-field col-md-4">
                                            <input placeholder="" autocomplete="off" type="number" name="likes"
                                                value="{{ old('likes') }}" id="likes"
                                                oninput="clearError('likes-error')" />
                                            <label for="likes">Likes</label>
                                            @error('likes')
                                                <div class="input-group-append" id="likes-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('likes') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="input-field col-md-4">
                                            <input placeholder="" autocomplete="off" type="number" name="shares"
                                                value="{{ old('shares') }}" id="shares"
                                                oninput="clearError('shares-error')" />
                                            <label for="shares">Shares</label>
                                            @error('shares')
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="shares-error">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('shares') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="form-group row justify-content-center">
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <div class="checkbox-wrapper-46 mt-1">
                                                <input type="checkbox" id="add_to_Sequence" name="add_to_Sequence" value="1"
                                                    {{ old('add_to_Sequence') ? 'checked' : '' }}
                                                    class="inp-cbx" oninput="clearError('add-to-sequence-error')">

                                                <label for="add_to_Sequence" class="cbx">
                                                    <span>
                                                        <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                        </svg>
                                                    </span>
                                                    <span>Add To Sequence</span>
                                                </label>

                                                @error('add_to_Sequence')
                                                    <div class="input-group-append" id="add-to-sequence-error">
                                                        <div class="input-group-text">
                                                            <span class="me-1">
                                                                <i class="fa-solid fa-circle-exclamation"></i>
                                                                {{ $message }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- --------------------------------------- Uploads container---------------------- --}}
                                <div class="uploads-container mt-0 row">
                                    <div class="uploads col-md-4" style="padding-top: 35px">
                                        <div class="uploads-box">
                                            <h3 class="-title">Upload Reel Video <span class="text-danger">*</span>
                                            </h3>
                                            <p class="-paragraph px-5">
                                            <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                <li>Only .mp4 video files are allowed*</li>
                                                <li class="text-left">The file size must not exceed 20 MB.</li>
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
                                                        <!-- Error message will display here (e.g., "The reel video file size must not exceed 20 MB.") -->
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="uploads col-md-4" style="padding-top: 35px">
                                        <div class="uploads-box">
                                            <h3 class="-title">Upload Thumbnail <span class="text-danger">*</span>
                                            </h3>
                                            <p class="-paragraph px-5">
                                            <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                <li>Only .jpeg, .jpg, and .png files are allowed*</li>
                                                <li class="text-left">The image size must not exceed 100 KB.</li>
                                                <li class="text-left">Image dimensions must be exactly 430x750 pixels.</li>
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
                                                        <!-- Error message will display here (e.g., "The thumb image size must not exceed 100 KB.") -->
                                                    </div>
                                                </div>
                                            @enderror
                                            </>
                                        </div>
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
