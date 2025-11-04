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
                                <h3 class="card-title mb-0 fs-5">Add Web Story File</h3>
                            </div>
                            <form class="form-group" method="post" action="{{ asset('webstory/webstory-files/add') }}"
                                enctype="multipart/form-data" onsubmit="return validateUploadForm()">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="input-field">
                                            <input class="at-title" id="description" autocomplete="off" type="text"
                                                name="description" value="{{ old('description') }}"
                                                oninput="clearError('description-error')" />
                                            <label for="description">Web Story Name <span
                                                    class="text-danger">*</span></label>
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
                                    {{-- <div class="input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="desc_eng" value="{{ old('desc_eng') }}"
                                                id="desc_eng" oninput="clearError('desc_eng-error')" />
                                            <label for="desc_eng">Web Story English Name</label>
                                            @error('desc_eng')
                                                <div class="input-group-append" id="desc_eng-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('desc_eng') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div> --}}
                                    <div class="form-group row">
                                        <div class="input-field col-md-6">
                                            @if (isset($data['selected_webstory_id']))
                                                @php
                                                    $selectedStory = $data['get_webstories']->firstWhere(
                                                        'id',
                                                        $data['selected_webstory_id'],
                                                    );
                                                @endphp

                                                {{-- <label class="customLable" for="authName">Selected Webstories Name</label> --}}

                                                <!-- Display the selected web story name in a readonly input -->
                                                <input type="text" class="form-control"
                                                    value="{{ $selectedStory ? $selectedStory->name : 'Unknown Web Story' }}"
                                                    readonly>

                                                <!-- Hidden input to submit the webstories_id -->
                                                <input type="hidden" name="webstories_id"
                                                    value="{{ $selectedStory ? $selectedStory->id : '' }}">
                                            @endif

                                            @error('webstories_id')
                                                <div class="input-group-append" id="webstories_id-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1">
                                                            <i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="input-field col-md-6">
                                            <input autocomplete="off" type="text" name="credit"
                                                value="{{ old('credit') }}" id="credit"
                                                oninput="clearError('credit-error')" />
                                            <label for="credit">Credit</label>
                                            @error('credit')
                                                <div class="input-group-append" id="credit-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('credit') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 col-sm-12">
                                            <label class="customLable" for="primicat">File Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="js-example-basic-single form-select" id="file_type"
                                                data-width="100%" name="file_type" onchange="handleFileTypeChange()"
                                                oninput="clearError('file_type-error')">
                                                {{-- <option value="">Select File Type <span class="text-danger">*</span>
                                                </option> --}}
                                                <option value="image" {{ old('file_type') == 'image' ? 'selected' : '' }}>
                                                    Image
                                                </option>
                                                <option value="video" {{ old('file_type') == 'video' ? 'selected' : '' }}>
                                                    Video
                                                </option>
                                            </select>
                                            @error('file_type')
                                                <div class="input-group-append" id="file_type-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('file_type') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-center mb-3" style="gap: 20px;">
                                        <div class="col-auto p-0">
                                            <img id="image-preview" src="#" alt="Preview"
                                                style="display: none; max-width: 260px; max-height: 100%; border-radius: 6px; border: 1px solid #ddd; background: #f8f8f8;" />
                                        </div>
                                        <div class="col-auto p-0">
                                            <video id="video-preview" controls
                                                style="display: none; max-width: 260px; max-height: 100%; border-radius: 6px; border: 1px solid #ddd; background: #f8f8f8;"></video>
                                        </div>
                                    </div>



                                    {{-- --------------------------------------- Uploads container---------------------- --}}
                                    <div class="uploads-container row">
                                        <div class="uploads col-md-4" id="image-upload-section" style="padding-top: 35px">
                                            <div class="uploads-box">
                                                <h3 class="-title">Upload Web Story Image <span class="text-danger">*</span>
                                                </h3>
                                                <p class="-paragraph px-5">
                                                <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                    <li>Only .jpeg, .jpg and .png files are allowed*</li>
                                                    <li class="text-left">The image size must not exceed 200 KB.</li>
                                                    <li class="text-left">Image dimension should be (720x1280) px.</li>
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
                                        <div class="uploads col-md-4" id="video-upload-section"
                                            style="padding-top: 35px">
                                            <div class="uploads-box">
                                                <h3 class="-title">Upload Video <span class="text-danger">*</span>
                                                </h3>
                                                <p class="-paragraph px-5">
                                                <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                    <li>Only .mp4 video files are allowed*</li>
                                                    <li class="text-left">File size must not exceed 20MB.</li>
                                                    <li class="text-left">File duration must not exceed 60 seconds.</li>
                                                </ul>
                                                </p>

                                                <label for="file-input-video" class="drop-container">
                                                    <span class="drop-title">Drop files here</span>
                                                    or
                                                    <input type="file" accept=".mp4" id="file-input-video"
                                                        name="video_file" oninput="clearError('video_file-error')">
                                                </label>

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
                                        <button class="--btn btn-publish">SUBMIT</button>
                                    </div>
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
        function handleFileTypeChange() {
            const fileType = document.getElementById('file_type').value;
            const imageSection = document.getElementById('image-upload-section');
            const videoSection = document.getElementById('video-upload-section');
            const titleElement = imageSection.querySelector('h3.-title');

            if (fileType === 'image') {
                titleElement.innerHTML = 'Upload Web Story Image <span class="text-danger">*</span>';
                imageSection.style.display = 'block';
                videoSection.style.display = 'none';
            } else if (fileType === 'video') {
                titleElement.innerHTML = 'Upload Thumbnail <span class="text-danger">*</span>';
                imageSection.style.display = 'block';
                videoSection.style.display = 'block';
            } else {
                imageSection.style.display = 'none';
                videoSection.style.display = 'none';
            }

            // Disable inputs if their section is hidden
            if (imageSection.querySelector('input')) {
                imageSection.querySelector('input').disabled = fileType !== 'image' && fileType !==
                    'video'; // Only enable when section is shown
            }
            if (videoSection.querySelector('input')) {
                videoSection.querySelector('input').disabled = fileType !== 'video';
            }
        }

        // Trigger on page load to reflect old input (Laravel's old())
        window.addEventListener('DOMContentLoaded', handleFileTypeChange);
    </script>
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
        function validateUploadForm() {
            const fileType = document.getElementById('file_type').value;
            const imageInput = document.querySelector('#image-upload-section input[type="file"]');
            const videoInput = document.querySelector('#video-upload-section input[type="file"]');
            const storyName = document.getElementById('description').value;

            if (!storyName.trim()) {
                alert('Please enter web story file title.');
                return false;
            }

            if (!fileType) {
                alert('Please select a file type.');
                return false;
            }

            if (fileType === 'image') {
                if (!imageInput || !imageInput.files.length) {
                    alert('Please upload an image file.');
                    return false;
                }

                const file = imageInput.files[0];
                const allowedExtensions = ['jpeg', 'jpg', 'png','.webp'];
                const extension = file.name.split('.').pop().toLowerCase();
                const sizeKB = file.size / 1024;

                if (!allowedExtensions.includes(extension)) {
                    alert('Only .jpeg, .jpg, .webp' and .png files are allowed.');
                    return false;
                }

                if (sizeKB > 200) {
                    alert('Image size must not exceed 200 KB.');
                    return false;
                }
            }

            if (fileType === 'video') {
                if (!videoInput || !videoInput.files.length) {
                    alert('Please upload a video file.');
                    return false;
                }

                if (!imageInput || !imageInput.files.length) {
                    alert('Please upload a thumbnail image.');
                    return false;
                }

                // ✅ Validate Image
                const imageFile = imageInput.files[0];
                const imageExtension = imageFile.name.split('.').pop().toLowerCase();
                const imageSizeKB = imageFile.size / 1024;
                const allowedImageExtensions = ['jpeg', 'jpg', 'png'];

                if (!allowedImageExtensions.includes(imageExtension)) {
                    alert('Only .jpeg, .jpg, and .png image files are allowed.');
                    return false;
                }

                if (imageSizeKB > 200) {
                    alert('Thumbnail image size must not exceed 200 KB.');
                    return false;
                }

                // ✅ Validate Video
                const videoFile = videoInput.files[0];
                const videoExtension = videoFile.name.split('.').pop().toLowerCase();
                const videoSizeMB = videoFile.size / (1024 * 1024);

                if (videoExtension !== 'mp4') {
                    alert('Only .mp4 video files are allowed.');
                    return false;
                }

                if (videoSizeMB > 20) {
                    alert('Video size must not exceed 20 MB.');
                    return false;
                }

                // Optional: Video duration check (skip for now)
                // const tempVideo = document.createElement('video');
                // tempVideo.preload = 'metadata';
                // tempVideo.onloadedmetadata = function () {
                //     window.URL.revokeObjectURL(tempVideo.src);
                //     const duration = tempVideo.duration;
                //     if (duration > 60) {
                //         alert('Video duration must not exceed 60 seconds.');
                //         return false;
                //     } else {
                //         document.querySelector('form').submit();
                //     }
                // };
                // tempVideo.src = URL.createObjectURL(videoFile);
                // return false;
            }


            return true; // allow normal submission for image
        }
    </script>
@endsection
