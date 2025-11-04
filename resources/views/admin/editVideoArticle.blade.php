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
                                <h3 class="card-title mb-0 fs-5">EDIT VIDEO</h3>
                            </div>
                            <form id="videoUploadEditForm" class="form-group use-progress" method="POST"
                                action="{{ url('video/edit/' . $video->id) }}" enctype="multipart/form-data" data-namespace="VideoFormUtils"
                                data-validate="validateVideoEditForm">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="input-field">
                                            <input class="at-title" id="title" placeholder="" autocomplete="off"
                                                type="text" name="title" value="{{ $video->title }}"
                                                oninput="VideoFormUtils.clearError('title-error')" />
                                            <label for="title">Title <span class="text-danger">*</span></label>
                                            @error('title')
                                                <div class="input-group-append" id="title-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('title') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="input-field col-md-6">
                                            <input autocomplete="off" type="text" name="title_url" placeholder=""
                                                value="{{ $video->eng_name }}" id="title_url"
                                                oninput="VideoFormUtils.clearError('title_url-error')" />
                                            <label for="title_url">Title URL <span class="text-danger">*</span></label>
                                            @error('title_url')
                                                <div class="input-group-append" id="title_url-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('title_url') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="input-field col-md-6">
                                            <input autocomplete="off" type="text" name="keywords" placeholder=""
                                                value="{{ $video->keywords }}" id="keywords"
                                                oninput="VideoFormUtils.clearError('keywords-error')" />
                                            <label for="keywords">Keywords</label>
                                            @error('keywords')
                                                <div class="input-group-append" id="keywords-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('keywords') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label class="customLable" for="category">Select category <span
                                                    class="text-danger">*</span></label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                id="category" name="category" oninput="VideoFormUtils.clearError('category-error')">
                                                <option value="0">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category', $video->category_id ?? '') == $category->id ? 'selected' : '' }}>
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

                                        <div class="col-md-4 mb-2">
                                            <label class="customLable" for="author">Author name <span
                                                    class="text-danger">*</span></label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                id ="author" name="author" oninput="VideoFormUtils.clearError('author-error')">
                                                <option value="0">Select Author Name</option>
                                                @foreach ($authors as $author)
                                                    <option value="{{ $author->id }}"
                                                        {{ old('author', $video->author_id ?? '') == $author->id ? 'selected' : '' }}>
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

                                        <div class="col-md-4 mb-2">
                                            <label class="customLable" for="state">State</label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                id ="state" name="state" oninput="VideoFormUtils.clearError('state-error')">
                                                <option value="0">Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ old('state', $video->state_id ?? '') == $state->id ? 'selected' : '' }}>
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
                                    </div>

                                    <div class="form-group row">
                                        <div class="input-field">
                                            <textarea autocomplete="off" name="description" placeholder="" id="description"
                                                oninput="VideoFormUtils.clearError('description-error')" class="input-textarea">{{ $video->description }}</textarea>
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
                                    </div>

                                    @php
                                        $videoPath = !empty($video->video_path) ? asset($video->video_path) : '';
                                        $thumbPath = !empty($video->thumbnail_path)
                                            ? asset($video->thumbnail_path)
                                            : '';
                                    @endphp

                                    <div class="row align-items-center justify-content-center mb-3" style="gap: 20px;">
                                        <label class="customLable mx-auto d-block text-center">Media Preview</label>
                                        <div class="col-auto p-0">
                                            <img id="image-preview" src="{{ $thumbPath }}" alt="Preview"
                                                style="{{ $thumbPath ? '' : 'display: none;' }} max-width: 400px; max-height: 100%; border-radius: 6px; border: 1px solid #ddd; background: #f8f8f8;" />
                                        </div>
                                        <div class="col-auto p-0">
                                            <video id="video-preview" controls {{ $videoPath ? '' : 'hidden' }}
                                                style="{{ $videoPath ? '' : 'display: none;' }} max-width: 400px; max-height: 100%; border-radius: 6px; border: 1px solid #ddd; background: #f8f8f8;">
                                                @if ($videoPath)
                                                    <source src="{{ $videoPath }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                @endif
                                            </video>
                                        </div>
                                    </div>



                                    {{-- --------------------------------------- Uploads container---------------------- --}}
                                    <div class="uploads-container row">
                                        <div class="uploads col-md-5" id="image-upload-section"
                                            style="padding-top: 35px">
                                            <div class="uploads-box">
                                                <h3 class="-title">Upload Thumbnail <span class="text-danger">*</span>
                                                </h3>
                                                <p class="-paragraph px-5">
                                                <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                    <li>Only .jpeg, .jpg, .png and .webp files are allowed*</li>
                                                    <li class="text-left">The image size must not exceed 200 KB.</li>
                                                </ul>
                                                </p>

                                                @if (isset($video->thumbnail_path))
                                                    @php
                                                        $thumbnailName = basename($video->thumbnail_path);
                                                    @endphp
                                                    <p>Saved Thumbnail: <strong>{{ $thumbnailName }}</strong></p>
                                                @endif
                                                <label for="image_file" class="drop-container "
                                                    style="margin-top: 40px;">
                                                    <span class="drop-title">Drop files here</span>
                                                    or
                                                    <input type="file" accept=".jpeg,.jpg,.png,.webp"
                                                        name="image_file" id="image_file"
                                                        oninput="VideoFormUtils.clearError('image_file-error')">
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
                                        <div class="uploads col-md-5" id="video-upload-section"
                                            style="padding-top: 35px">
                                            <div class="uploads-box">
                                                <h3 class="-title">Upload Video <span class="text-danger">*</span>
                                                </h3>
                                                <p class="-paragraph px-5">
                                                <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                    <li>Only .mp4 video files are allowed*</li>
                                                    <li class="text-left">File size must not exceed 300MB.</li>
                                                    <li>Recommended aspect ratio: <strong>16:9</strong> or
                                                        <strong>landscape</strong>
                                                </ul>
                                                </p>

                                                @if (isset($video->video_path))
                                                    @php
                                                        $videoName = basename($video->video_path);
                                                    @endphp
                                                    <p>Saved Video: <strong>{{ $videoName }}</strong></p>
                                                @endif
                                                <label for="video_file" class="drop-container">
                                                    <span class="drop-title">Drop files here</span>
                                                    or
                                                    <input type="file" accept=".mp4" id="video_file"
                                                        name="video_file" oninput="VideoFormUtils.clearError('video_file-error')">
                                                </label>
                                                <small id="video_file-size" class="text-muted"></small>

                                                @error('video_file')
                                                    <div class="input-group-append" id="video_file-error">
                                                        <div class="input-group-text text-danger">
                                                            {{ $message }}
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Action button --}}
                                    <div class="button-container row mx-3">
                                        <button type="submit" class="--btn btn-publish">SUBMIT</button>
                                    </div>
                                </div>
                            </form>
                            <x-progress-modal message="Updating your video..." />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.querySelector('#image-upload-section input[type="file"]');
            const videoInput = document.querySelector('#video-upload-section input[type="file"]');

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

            if (videoInput) {
                videoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const preview = document.getElementById('video-preview');
                    if (file && file.type === 'video/mp4') {
                        preview.src = URL.createObjectURL(file);
                        preview.style.display = 'block';
                    } else {
                        preview.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <script>
        window.existingThumb = {{ !empty($video->thumbnail_path) ? 'true' : 'false' }};
        window.existingVideo = {{ !empty($video->video_path) ? 'true' : 'false' }};
    </script>

    <script>
        document.getElementById('video_file').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const errorContainer = document.getElementById('video_file-error');
            const sizeDisplay = document.getElementById('video_file-size');
            const maxSizeMB = 300;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            const fieldRef = { first: null };

            // Clear previous error
            if (errorContainer) errorContainer.innerHTML = '';

            if (file) {
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                if (sizeDisplay) {
                    sizeDisplay.textContent = `Selected file size: ${sizeInMB} MB`;
                }

                const isMp4 = file.type === 'video/mp4' || file.name.toLowerCase().endsWith('.mp4');
                const isTooLarge = file.size > maxSizeBytes;

                if (!isMp4) {
                    VideoFormUtils.showError('video_file-error', 'Only .mp4 files are allowed.', fieldRef);
                    alert('Invalid file type. Only .mp4 files are allowed.');
                    event.target.value = '';
                    return;
                }

                if (isTooLarge) {
                    VideoFormUtils.showError('video_file-error', 'Video must not exceed 300 MB.', fieldRef);
                    alert('Video file size exceeds 300 MB. Please select a smaller file. Selected file size: ' + sizeInMB + ' MB ');
                    event.target.value = '';
                    return;
                }
            } else {
                if (sizeDisplay) sizeDisplay.textContent = '';
            }
        });
    </script>

@endsection
