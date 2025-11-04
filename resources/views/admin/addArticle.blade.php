@extends('layouts.adminNew')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">

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
                        <div class="card card-primary border-0">
                            <div class="card-header d-flex justify-content-between align-items-center"
                                style="padding-block: 10px;">
                                <h3 class="card-title mb-0 fs-5">ADD ARTICLE</h3>


                                <div class="d-flex gap-2">
                                    <button type="button" class="--btn btn-publish"
                                        onclick="submitArticleForm('pub')">Publish</button>
                                    <button type="button" class="--btn btn-save" onclick="submitArticleForm('du')">Save as
                                        Draft</button>
                                </div>
                            </div>

                            <!-- /.card-header -->
                            <!-- form start -->
                            <?php
                            $livestatus = request('callfrom');
                            ?>

                            {{-- <form class="form-group" method="post" action="{{ asset('posts/add?callfrom=') }}{{$livestatus}}" --}}
                            <form id="articleForm" class="form-group" method="post" action="{{ asset('posts/add') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row ">
                                        <div class="checkbox-wrapper-46 mb-4">
                                            <input type="checkbox" id="cbx-45" name="send_Notification" class="inp-cbx"
                                                onchange="toggleNotificationTitle()"
                                                {{ old('send_Notification') ? 'checked' : '' }} />
                                            <label for="cbx-45" class="cbx"><span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg></span><span>Send Notification</span>
                                            </label>
                                        </div>
                                        @error('send_Notification')
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                        {{ $errors->first('send_Notification') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @enderror
                                        {{-- Hidden input field shown conditionally --}}
                                        <div class="input-field" id="notificationTitleWrapper" style="display: none;">
                                            <input type="text" class="at-title" name="notification_title"
                                                id="notification_title" placeholder=""
                                                value="{{ old('notification_title') }}">
                                            <label for="notification_title">Notification Header Title</label>
                                            @error('notification_title')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1">
                                                            <i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('notification_title') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <div class="input-field">
                                            <input class="at-title" placeholder="" autocomplete="off" type="text"
                                                name="name" id="name" oninput="clearError('name-error')"
                                                value="{{ old('name') }}" />
                                            <label for="name">Post Title <span class="text-danger">*</span></label>
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
                                    </div>

                                    <div class="form-group row ">
                                        <div class="input-field">
                                            <input class="at-title" placeholder="" autocomplete="off" type="text"
                                                name="short_title" id="short_title"
                                                oninput="clearError('short_title-error')"
                                                value="{{ old('short_title') }}" />
                                            <label for="short_title">Short Title</label>
                                            @error('short_title')
                                                <div class="input-group-append" id="short_title-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('short_title') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="eng_name"
                                                id="eng_name" oninput="clearError('eng_name-error', 'site_url-error')"
                                                value="{{ old('eng_name') }}" />
                                            <label for="eng_name">Title URL <span class="text-danger">*</span></label>
                                            @error('eng_name')
                                                <div class="input-group-append" id="eng_name-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('eng_name') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                            @error('site_url')
                                                {{-- <div class="text-danger">{{ $message }}</div> --}}
                                                <div class="input-group-append" id="site_url-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="tags"
                                                id="tags" oninput="clearError('tags-error')"
                                                value="{{ old('tags') }}" />
                                            <label for="tags">Tags</label>
                                            @error('tags')
                                                <div class="input-group-append" id="tags-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('tags') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>


                                    </div>
                                    <div class="form-group row">
                                        <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="keyword"
                                                id="keyword" oninput="clearError('keyword-error')"
                                                value="{{ old('keyword', 'NMF News') }}" />
                                            <label for="keyword">Keywords</label>
                                            @error('keyword')
                                                <div class="input-group-append" id="keyword-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('keyword') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>


                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 mb-2">
                                            <label class="customLable" for="authName">Author name <span
                                                    class="text-danger">*</span></label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="author" oninput="clearError('author-error')">
                                                <option value="">Select Author Name <span
                                                        class="text-danger">*</span></option>
                                                @foreach ($data['authors'] as $author)
                                                    <option value="{{ $author->id }}"
                                                        {{ old('author', Auth::id()) == $author->id ? 'selected' : '' }}>
                                                        {{ $author->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('author')
                                                <div class="input-group-append" id="author-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('author') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="customLable" for="primicat">Prime category <span
                                                    class="text-danger">*</span></label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="category" oninput="clearError('category-error')">
                                                <option value="">Select Prime Category <span
                                                        class="text-danger">*</span></option>
                                                @foreach ($data['categories'] as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
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
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="customLable" for="statecat">State</label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="state" oninput="clearError('state-error')">
                                                <option value="0">Select State </option>
                                                @foreach ($data['states'] as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ old('state') == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('state')
                                                <div class="input-group-append" id="state-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('state') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="customLable" for="distcat">District</label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="district" oninput="clearError('district-error')">
                                                <option value="0">Select District</option>
                                                @foreach ($data['district'] as $district)
                                                    <option value="{{ $district->id }}"
                                                        {{ old('district') == $district->id ? 'selected' : '' }}>
                                                        {{ $district->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('district')
                                                <div class="input-group-append" id="district-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('district') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 mb-2">
                                            <label class="customLable" for="isLive">Live Status</label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                id="isLive" name="isLive" oninput="clearError('isLive-error')">
                                                <option value="0" {{ old('isLive') == '0' ? 'selected' : '' }}>Normal
                                                </option>
                                                <option value="1" {{ old('isLive') == '1' ? 'selected' : '' }}>Live
                                                </option>
                                            </select>
                                            @error('isLive')
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="isLive-error">
                                                        <span class="me-1">
                                                            <i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-7">
                                            <label for="exampleFormControlSelect2"
                                                style="font-size: 17px; color: #757575; margin-bottom: 8px">Select More
                                                Category</label>
                                            <select multiple="" class="form-select" id="exampleFormControlSelect2"
                                                name="mult_cat[]" oninput="clearError('mult_cat-error')">
                                                <!-- <option value="">Select More Category</option> -->
                                                @foreach ($data['categories'] as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('mult_cat')
                                                <div class="input-group-append" id="mult_cat-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('mult_cat') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div
                                            class="col-md-5 pt-3 d-flex flex-wrap align-items-start justify-content-start gap-3">

                                            <!-- Breaking News -->
                                            <div class="form-group mt-3 ">
                                                <div class="checkbox-wrapper-46">
                                                    <input type="checkbox" id="cbx-46" name="breaking_status"
                                                        value="1" {{ old('breaking_status') ? 'checked' : '' }}
                                                        class="inp-cbx" />
                                                    <label for="cbx-46" class="cbx"><span>
                                                            <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                            </svg></span><span>Breaking News</span>
                                                    </label>
                                                </div>
                                                @error('breaking_status')
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="me-1"><i
                                                                    class="fa-solid fa-circle-exclamation"></i>
                                                                {{ $errors->first('breaking_status') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>

                                            <!-- Top News Sequence -->
                                            <div class="form-group mt-3">
                                                <div class="checkbox-wrapper-46">
                                                    <input type="checkbox" id="cbx-47" name="sequence" value="1"
                                                        {{ old('sequence') ? 'checked' : '' }} class="inp-cbx"
                                                        onchange="toggleSequenceDropdown()" />
                                                    <label for="cbx-47" class="cbx"><span>
                                                            <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                            </svg></span><span>Top Sequence</span>
                                                    </label>
                                                </div>
                                                @error('sequence')
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="me-1"><i
                                                                    class="fa-solid fa-circle-exclamation"></i>
                                                                {{ $errors->first('sequence') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @enderror


                                            </div>

                                            <!-- Homepage Podcast -->
                                            <div class="form-group mt-3">
                                                <div class="checkbox-wrapper-46">
                                                    <input type="checkbox" id="cbx-48" name="ispodcast_homepage"
                                                        value="1" {{ old('ispodcast_homepage') ? 'checked' : '' }}
                                                        class="inp-cbx" />
                                                    <label for="cbx-48" class="cbx"><span>
                                                            <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                            </svg></span><span>Podcast</span>
                                                    </label>
                                                </div>
                                                @error('ispodcast_homepage')
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="me-1"><i
                                                                    class="fa-solid fa-circle-exclamation"></i>
                                                                {{ $errors->first('ispodcast_homepage') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>

                                            <!-- Conditional Dropdown for Sequence Order -->
                                            <div class="col-md-12" id="sequenceDropdown" style="display: none;">
                                                <select class="js-example-basic-single form-select" data-width="100%"
                                                    name="sequence_order" id="sequence_order"
                                                    oninput="clearError('sequence_order-error')">
                                                    <option value="">Select Sequence Order</option>
                                                    @for ($i = 1; $i <= 15; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ old('sequence_order') == $i ? 'selected' : '' }}>
                                                            {{ $i }}</option>
                                                    @endfor
                                                </select>
                                                @error('sequence_order')
                                                    <div class="input-group-append">
                                                        <div class="input-group-text" id="sequence_order-error">
                                                            <span class="me-1"><i
                                                                    class="fa-solid fa-circle-exclamation"></i>
                                                                {{ $message }}</span>
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="input-field">
                                            <input autocomplete="off" type="text" name="sort_desc" placeholder=""
                                                id="sort_desc" oninput="clearError('sort_desc-error')"
                                                value="{{ old('sort_desc') }}" />
                                            <label for="sort_desc">Brief <span class="text-danger">*</span></label>
                                            @error('sort_desc')
                                                <div class="input-group-append" id="sort_desc-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('sort_desc') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- --------------------------------------- editor container---------------------- --}}
                                    <div class="Editor-block row mb-4" style="padding: 10px">
                                        <label for="exampleFormControlSelect2"
                                            style="font-size: 17px; color: #757575; margin-bottom: 8px">Post
                                            Description</label>
                                        <textarea id="default" name="description">{{ old('description') }}</textarea>

                                    </div>
                                    {{-- ------------------ video link------------- --}}
                                    <div class="form-group row">
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="link"
                                                id="sort_desc" value="{{ old('link') }}" />
                                            <label for="username">Video Link</label>
                                            @error('link')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('link') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>

                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="credits"
                                                id="credits" value="{{ old('credits') }}" />
                                            <label for="username">Image Credits</label>
                                            @error('credits')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('credits') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group mt-4">
                                            <label class="form-label fw-semibold text-capitalize"
                                                for="scheduletime">Schedule Publish Time</label>
                                            <div class="input-field col-md-4">

                                                <div class="input-group">
                                                    <!-- Input -->
                                                    <input id="scheduletime" name="scheduletime" class="form-control"
                                                        placeholder="Select Date and Time" type="text"
                                                        value="{{ old('scheduletime') }}" />

                                                    <!-- Include Flatpickr CSS -->
                                                    <link rel="stylesheet"
                                                        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


                                                    <!-- Include Flatpickr JS -->
                                                    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

                                                    <!-- Initialize Flatpickr -->
                                                    <script>
                                                        flatpickr("#scheduletime", {
                                                            enableTime: true,
                                                            dateFormat: "Y-m-d H:i",
                                                            minuteIncrement: 30,
                                                        });
                                                    </script>
                                                    @error('scheduletime')
                                                        <div class="invalid-feedback d-block">
                                                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                                                            {{ $errors->first('scheduletime') }}
                                                        </div>
                                                    @enderror

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    {{-- --------------------------------------- Uploads container---------------------- --}}
                                    <div class="uploads-container row">
                                        <div class="uploads col-md-5">
                                            <div class="uploads-box">
                                                <span class="-title">Select Thumb Images</span>
                                                <label class="drop-container" style="height: 174px; margin-top:92px">
                                                    <input type="hidden" name="thumb_images" id="id_thumb_images">
                                                    <input type="text" class="form-control" id="name_thumb_images"
                                                        disabled>
                                                    <button type="button" class="btn btn-thumb-img" data-toggle="modal"
                                                        data-target="#modal-thumb">
                                                        Select thumb images
                                                    </button>
                                                </label>
                                                </>
                                            </div>
                                        </div>
                                        <div class="uploads col-md-5">
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
                                                    <input type="file" accept=".jpeg,.jpg,.png" name="file"
                                                        id="file-input">
                                                    <button class="--btn btn-submit mt-4" type="button"
                                                        id="save_image">Save Image</button>
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
                                        </div>
                                    </div>
                                    {{-- Action button --}}
                                    <input type="hidden" name="from" value="{{ request()->get('from') }}">
                                    <div class="button-container row">
                                        <button class="--btn btn-publish" name="publish" value="pub">Publish</button>
                                        <button class="--btn btn-save" name="draft" value="du">Save as
                                            Draft</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="modal fade" id="modal-thumb">
                        <div class="modal-dialog" style="width: 100%; max-width: 95vw;">
                            <div class="modal-content" style="width: 100%; max-width: 95vw; height: 95vh;">
                                <div class="modal-header">
                                    <div class="col-md-6 mb-2">
                                        <input type="text" id="imageSearchInput" class="form-control"
                                            placeholder="Search image by name">
                                    </div>

                                    <button type="button" class="close_btn" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>


                                <div class="modal-body" style="height: 400px; overflow: scroll;">



                                    {{-- AJAX-loaded content --}}
                                    <div id="imageContentWrapper">
                                        @include('admin.partials.image_modal_body', ['data' => $data])
                                    </div>

                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-primary ms-auto" data-dismiss="modal"
                                        id="save_thumb_image">Save changes</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                </div>
            </div>
            <!-- /.card -->
        </section>
    </div>
    <!-- Include jQuery, Popper.js, and Bootstrap 5 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script> --}}

    <!-------  modal open script ----->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function loadImageModalContent(url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {

                    $('#imageContentWrapper').html(response);
                },
                error: function() {
                    alert('Search or pagination failed.');
                }
            });
        }

        // Search on keyup
        $('#imageSearchInput').on('keyup', function() {
            let keyword = $(this).val();
            let url = "?modal=1&page=1&keyword=" + encodeURIComponent(keyword);
            loadImageModalContent(url);
        });

        // Delegate pagination click (because pagination is dynamically loaded)
        $(document).on('click', '.card-footer .pagination a', function(e) {

            e.preventDefault();
            let url = $(this).attr('href') + "&modal=1";
            loadImageModalContent(url);
        });
    </script>
    @push('custom-scripts')
        <script src="{{ asset('asset/new_admin/tinymce/tinymce.min.js') }}"></script>
        <script>
            tinymce.init({
                selector: 'textarea#default',
                license_key: 'gpl',
                width: 1000,
                height: 300,
                plugins: [
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                    'table', 'emoticons', 'template', 'codesample'
                ],
                // toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
                // 'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                // 'forecolor backcolor emoticons',
                toolbar: 'customtextbox insertfacebookvideo | undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
            'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
            'forecolor backcolor emoticons',
                setup: function(editor) {
                    editor.ui.registry.addButton('customtextbox', {
                        // text: ' Box Text',
                        text: 'bayan',
                        tooltip: 'Insert custom boxed text',
                        onAction: function() {
                            const userInput = prompt("Enter text to display as a box:");
                            if (userInput) {
                                const encodedText = editor.dom.encode(userInput);
                                const boxedHTML =
                                    `<span class="custom-box">${encodedText}</span>&nbsp;`;
                                editor.insertContent(boxedHTML);
                            }
                        }
                    });
                     // 🔹 New button: Insert Facebook Video
            editor.ui.registry.addButton('insertfacebookvideo', {
                text: 'FB Video',
                tooltip: 'Insert Facebook Video',
                onAction: function() {
                    const fbUrl = prompt("Paste Facebook Video URL:");
                    if (fbUrl) {
                        const embedHTML = `
                            <div class="fb-video" 
                                 data-href="${fbUrl}" 
                                 data-width="500" 
                                 data-show-text="false">
                            </div>
                        `;
                        editor.insertContent(embedHTML);
                    }
                }
            });
                },

                extended_valid_elements: 'span[class|style]',
                menu: {
                    favs: {
                        title: 'menu',
                        items: 'code visualaid | searchreplace | emoticons'
                    }
                },
                menubar: 'favs file edit view insert format tools table',
                content_style: 'body{font-family:Helvetica,Arial,sans-serif; font-size:16px}',
                content_css: '{{ asset('asset/new_admin/css/main_style.css') }}',

                /*Image Upload Settings */
                automatic_uploads: false,
                images_upload_handler: null,

                relative_urls: false,
                remove_script_host: false,
                convert_urls: false,

                // Used in General tab
                file_picker_types: 'image',
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype === 'image') {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');

                        input.onchange = function() {
                            var file = this.files[0];

                            // Client-side size validation (200 KB = 204800 bytes)
                            if (file.size > 204800) {
                                alert("The image size must not exceed 200 KB.");
                                return;
                            }

                            var formData = new FormData();
                            formData.append('file', file);
                            formData.append('_token', '{{ csrf_token() }}');

                            fetch('{{ url('/files/upload') }}', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        callback(data.location);
                                    } else {
                                        alert('Upload failed: ' + data.message);
                                    }
                                })
                                .catch(() => {
                                    alert('An error occurred while uploading the image.');
                                });
                        };
                        input.click();
                    }
                }
            });
        </script>

        <script>
            /*  $('.image_sec').click(function() {
                $('.popup').removeAttr('style');
                $(this).parent().attr('style', 'border: 5px solid blue;');
                $('#image_name').val($(this).data('name'));
                $('#image_id').val($(this).data('id'));
                $('#image_thumb_name').val($(this).data('name'));
                $('#image_thumb_id').val($(this).data('id'));
            }) */
            $('#imageContentWrapper').on('click', '.image_sec', function() {

                $('.popup').removeAttr('style');
                $(this).parent().attr('style', 'border: 5px solid blue;');
                $('#image_name').val($(this).data('name'));
                $('#image_id').val($(this).data('id'));
                $('#image_thumb_name').val($(this).data('name'));
                $('#image_thumb_id').val($(this).data('id'));
            });

            /*   $('#save_thumb_image').click(function() {
                  $('#id_thumb_images').val($('#image_thumb_id').val());
                  $('#name_thumb_images').val($('#image_thumb_name').val());
              }); */
            $('#save_thumb_image').click(function() {

                $('#id_thumb_images').val($('#image_thumb_id').val());
                $('#name_thumb_images').val($('#image_thumb_name').val());
            });
            $('#save_image').click(function() {
                const fileInput = $('#file-input')[0];

                if (fileInput.files.length === 0) {
                    alert("Please select a file.");
                    return;
                }

                const file = fileInput.files[0];

                // Only allow images: check MIME type first
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert("Only JPEG and PNG image formats are allowed.");
                    return;
                }

                // Check file size
                if (file.size > 200 * 1024) {
                    alert("Image size must not exceed 200 KB.");
                    return;
                }

                // Passed all validations — continue AJAX
                const formData = new FormData();
                formData.append('file', file, file.name);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ asset('/files/upload') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if (res.success) {
                            $('#id_thumb_images').val(res.file_id);
                            $('#name_thumb_images').val(res.file_name);
                            $('#file-input').val('');
                            // alert("Image saved successfully.");
                        } else {
                            alert("Upload failed: " + res.message);
                        }
                    },
                    error: function() {
                        alert("Something went wrong during upload.");
                    }
                });
                // };

                img.onerror = function() {
                    alert("Could not read image.");
                };

                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });

            function clearError(...ids) {
                ids.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.textContent = ''; // Clear the error message
                    }
                });
            }
        </script>
        {{-- <script>
            function validateRequired(fieldId) {
                const input = document.getElementById(fieldId);
                const error = document.getElementById(`${fieldId}-error`);
                
                if (input && input.value.trim() === '') {
                    error.classList.remove('d-none'); // show error
                } else {
                    error.classList.add('d-none'); // hide error
                }
            }
            
            function clearError(errorId) {
                const error = document.getElementById(errorId);
                if (error) {
                    error.classList.add('d-none');
                }
            }
        </script> --}}

        <script>
            function submitArticleForm(type) {
                const form = document.getElementById('articleForm');

                // Remove previous hidden inputs if any
                ['publish', 'draft'].forEach(name => {
                    const el = form.querySelector(`input[name="${name}"]`);
                    if (el) el.remove();
                });

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';

                if (type === 'du') {
                    hiddenInput.name = 'draft';
                    hiddenInput.value = 'du';
                } else {
                    hiddenInput.name = 'publish';
                    hiddenInput.value = 'pub';
                }

                form.appendChild(hiddenInput);
                form.submit();
            }
        </script>
        <script>
            function toggleNotificationTitle() {
                const checkbox = document.getElementById('cbx-45');
                const titleWrapper = document.getElementById('notificationTitleWrapper');
                titleWrapper.style.display = checkbox.checked ? 'block' : 'none';
            }
        </script>
        <script>
            function toggleSequenceDropdown() {
                const checkbox = document.getElementById('cbx-47');
                const dropdown = document.getElementById('sequenceDropdown');
                dropdown.style.display = checkbox.checked ? 'block' : 'none';
            }

            // Run once on page load in case checkbox is pre-checked
            document.addEventListener('DOMContentLoaded', toggleSequenceDropdown);
            toggleNotificationTitle(); // ?? this ensures the title shows/hides on page load
        </script>

     <script>
document.addEventListener('DOMContentLoaded', function() {
    const stateSelect = document.querySelector('select[name="state"]');
    const categorySelect = $('select[name="mult_cat[]"]');
    const rajyaCategoryId = "3"; // ID of राज्य

    // ✅ Ensure Rajya is always included
    function enforceRajya() {
        let values = categorySelect.val() || [];
        if (!values.includes(rajyaCategoryId)) {
            values.push(rajyaCategoryId);
            categorySelect.val(values).trigger('change.select2');
        }
    }

    // 🔹 When state changes
    stateSelect.addEventListener('change', function() {
        if (this.value !== "0") {
            enforceRajya(); // only add Rajya when state is chosen
        } else {
            // Reset if state = 0
            categorySelect.val([]).trigger('change.select2');
        }
    });

    // 🔹 Prevent manually unselecting Rajya
    categorySelect.on('select2:unselecting', function(e) {
        if (e.params.args.data.id === rajyaCategoryId) {
            e.preventDefault(); // stops Rajya from being removed
        }
    });

    // 🔹 On every change -> re-enforce Rajya (safety)
    categorySelect.on('change', function() {
        if (stateSelect.value !== "0") {
            enforceRajya();
        }
    });

    // 🔹 On form submit -> final safety net
    $('form').on('submit', function() {
        if (stateSelect.value !== "0") {
            enforceRajya();
        }
    });
});
</script>



    @endpush
@endsection
