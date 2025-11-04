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
                                <h3 class="card-title mb-0 fs-5">Add Web Story</h3>
                            </div>
                            <form class="form-group" method="post" action="{{ asset('/webstory/add') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="input-field col-md-6">
                                            <input class="at-title" autocomplete="off" type="text" name="name"
                                                value="{{ old('name') }}" id="name"
                                                oninput="clearError('name-error')" />
                                            <label for="name">Web Story Name <span class="text-danger">*</span></label>
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
                                            <label for="eng_name">Web Story English Name <span
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
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="category" oninput="clearError('category-error')">
                                                <option value="0">Select Category <span
                                                        class="text-danger">*</span></label>
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
                                        <div class="col-md-6">
                                            <div class="checkbox-wrapper-46 mt-2">
                                                <input type="checkbox" id="show_on_top" name="show_on_top" value="1"
                                                    {{ old('show_on_top') ? 'checked' : '' }}
                                                    class="inp-cbx" oninput="clearError('show-on-top-error')">

                                                <label for="show_on_top" class="cbx">
                                                    <span>
                                                        <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                        </svg>
                                                    </span>
                                                    <span>Show on Top News</span>
                                                </label>

                                                @error('show_on_top')
                                                    <div class="input-group-append" id="show-on-top-error">
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

                                    <div class="row align-items-center justify-content-center mb-3" style="gap: 20px;">
                                        <div class="col-auto p-0">
                                            <img id="image-preview" src="#" alt="Preview"
                                                style="display: none; max-width: 260px; max-height: 100%; border-radius: 6px; border: 1px solid #ddd; background: #f8f8f8;" />
                                        </div>
                                    </div>

                                    <div class="uploads-container row">
                                        <div class="uploads col-md-4" id="image-upload-section" style="padding-top: 35px">
                                            <div class="uploads-box">
                                                <h3 class="-title">Upload thumb Image</h3>
                                                <p class="-paragraph px-5">
                                                <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                    <li>Only .jpeg, .jpg and .png files are allowed*</li>
                                                    <li class="text-left">The image size must not exceed 200 KB.</li>
                                                    <li class="text-left">Image dimension should be (800x450) px.</li>
                                                </ul>
                                                </p>
                                                <label for="file-input" class="drop-container "
                                                    style="margin-top: 40px;">
                                                    <span class="drop-title">Drop files here</span>
                                                    or
                                                    <input type="file" accept=".jpeg,.jpg,.png,.webp"
                                                        name="image_file" id="file-input"
                                                        oninput="clearError('image_file-error')">
                                                </label>
                                                @error('image_file')
                                                    <div class="input-group-append" id="image_file-error">
                                                        <div class="input-group-text text-danger">
                                                            {{ $message }}
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.querySelector('#image-upload-section input[type="file"]');

            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const preview = document.getElementById('image-preview');
                    if (file && file.type.startsWith('image/')) {
                        preview.src = URL.createObjectURL(file);
                        preview.style.display = 'block';
                    } else {
                        preview.style.display = 'none';
                    }
                });
            }

        });
    </script>
@endsection
