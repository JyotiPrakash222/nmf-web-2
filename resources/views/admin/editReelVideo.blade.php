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
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">Edit Reels</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-group" method="post"
                                action="{{ asset('reel-videos/edit') }}/{{ $data['clips']->id }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">

                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="name"
                                                value="{{ $data['clips']->title }}" id="name" />
                                            <label for="name">Clip Title <span class="text-danger">*</span></label>
                                            @error('name')
                                                <div class="input-group-append">
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
                                                value="{{ $data['clips']->eng_name }}" id="eng_name" />
                                            <label for="eng_name">Clip Title (English) <span
                                                    class="text-danger">*</span></label>
                                            @error('eng_name')
                                                <div class="input-group-append">
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
                                                value="{{ $data['clips']->description }}" id="description" />
                                            <label for="description">Description <span class="text-danger">*</span></label>
                                            @error('description')
                                                <div class="input-group-append">
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
                                                value="{{ $data['clips']->subtitle }}" id="subtitle" />
                                            <label for="subtitle">Sub Title</label>
                                            @error('subtitle')
                                                <div class="input-group-append">
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
                                                name="category">
                                                <option value="0">Select Category <span class="text-danger">*</span>
                                                </option>
                                                <?php $cat = explode(',', $data['clips']->categories_id); ?>
                                                @foreach ($data['categories'] as $category)
                                                    <option value="{{ $category->id }}" <?php if (in_array($category->id, $cat)) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="input-group-append">
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
                                                value="{{ $data['clips']->likes }}" id="likes" />
                                            <label for="likes">Likes</label>
                                            @error('likes')
                                                <div class="input-group-append">
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
                                                value="{{ $data['clips']->shares }}" id="shares" />
                                            <label for="shares">Shares</label>
                                            @error('shares')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
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
                                            <div class="d-flex gap-4 align-items-center">
                                                
                                                {{-- Display Clip --}}
                                                <div class="checkbox-wrapper-46">
                                                    <input type="checkbox" id="cbx-46" name="status"
                                                        {{ $data['clips']->status == 1 ? 'checked' : '' }}
                                                        class="inp-cbx" />
                                                    <label for="cbx-46" class="cbx">
                                                        <span>
                                                            <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                            </svg>
                                                        </span>
                                                        <span>Display Clip</span>
                                                    </label>
                                                </div>

                                                {{-- Add To Sequence --}}
                                                <div class="checkbox-wrapper-46">
                                                    <input type="checkbox" id="add_to_Sequence" name="add_to_Sequence" value="1"
                                                        {{ $data['clips']->SortOrder ? 'checked' : '' }}
                                                        class="inp-cbx" oninput="clearError('add-to-sequence-error')" />
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


                                    {{-- image container --}}
                                    <?php
                                    $clipPath = $data['clips']->image_path;
                                    if (strpos($clipPath, 'file') !== false) {
                                        $findfilepos = strpos($clipPath, 'file');
                                        $clipFilePath = substr($clipPath, $findfilepos);
                                        $clipFilePath = $clipFilePath . '/' . $data['clips']->thumb_image;
                                    }
                                    ?>
                                    <div class="img-prev">
                                        <label class="customLable mx-auto d-block text-center mb-2">Image Preview</label>
                                        @if (!empty($clipFilePath))
                                            <div class="--imgPrev d-flex justify-content-center">
                                                <img class="col-md-5 rounded" src="{{ asset($clipFilePath) }}"
                                                    alt="{{ $data['clips']->name }}">
                                            </div>
                                        @endif
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
                                                    value="{{ isset($data['clips']->clip_file_name) ? $data['clips']->clip_file_name : '' }}">
                                            </label>
                                            </>
                                            @error('file')
                                                <div class="input-group-append">
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
                                            <h3 class="-title">Upload Thubmnail <span class="text-danger">*</span>
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
                                                    value="{{ isset($data['clips']->thumb_image) ? $data['clips']->thumb_image : '' }}"
                                                    id="file-input">
                                            </label>
                                            @error('thumbnail')
                                                <div class="input-group-append">
                                                    <div class="input-group-text text-danger">
                                                        {{ $message }}
                                                        <!-- Error message will display here (e.g., "The thumb image size must not exceed 5MB.") -->
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
@endsection
