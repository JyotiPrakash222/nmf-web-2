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
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">Edit Web Story File</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-group" method="post"
                                action="{{ asset('webstory/webstory-files/edit') }}/{{ $data['file']->id }}"
                                enctype="multipart/form-data" onsubmit="return validateUploadForm()">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="input-field">
                                            <input class="at-title" autocomplete="off" type="text" name="description"
                                                id="description" value="{{ $data['file']->description }}"
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
                                            <input placeholder="" autocomplete="off" type="text" name="desc_eng"
                                                id="desc_eng" value="{{ $data['file']->desc_eng }}"
                                                oninput="clearError('desc_eng-error')" />
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
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="webstory" oninput="clearError('webstory-error')">
                                                <option value="0">Select Web Stories</option>
                                                <?php $cat = explode(',', $data['file']->webstories_id); ?>
                                                @foreach ($data['categories'] as $category)
                                                    <option value="{{ $category->id }}" <?php if (in_array($category->id, $cat)) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('webstory')
                                                <div class="input-group-append" id="webstory-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('webstory') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="input-field col-md-6">
                                            <input autocomplete="off" type="text" name="credit" id="credit"
                                                value="{{ $data['file']->credit }}" oninput="clearError('credit-error')" />
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
                                                data-width="100%" name="file_type" disabled>
                                                <option selected>{{ $data['file']->file_type }}</option>
                                            </select>
                                            <input type="hidden" name="file_type" value="{{ $data['file']->file_type }}">
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
                                    {{-- image container --}}
                                    <?php
                                    $imagePath = $data['file']->filepath;
                                    if (strpos($imagePath, 'file') !== false) {
                                        $findFilePos = strpos($imagePath, 'file');
                                        $imageFilePath = substr($imagePath, $findFilePos) . '/' . $data['file']->filename;
                                    }
                                    
                                    $thumbnailPath = $data['file']->thumb_path;
                                    if (strpos($thumbnailPath, 'file') !== false) {
                                        $findFilePos = strpos($thumbnailPath, 'file');
                                        $thumbnailFilePath = substr($thumbnailPath, $findFilePos);
                                    }
                                    ?>

                                    {{-- Static preview from DB --}}
                                    <div class="img-prev">
                                        <label class="customLable mx-auto d-block text-center mb-2">Media Preview</label>

                                        <div class="row align-items-center justify-content-center mb-3" style="gap: 20px;">
                                            {{-- Image / Thumbnail Preview --}}
                                            <div class="col-auto p-0">
                                                <img id="media-image-preview"
                                                    src="{{ $data['file']->file_type === 'image' ? asset($imageFilePath) : (!empty($thumbnailFilePath) ? asset($thumbnailFilePath) : '') }}"
                                                    alt="Image preview"
                                                    style="max-width: 260px; max-height: 100%; border-radius: 6px; border: 1px solid #ddd; background: #f8f8f8;
                                                        {{ $data['file']->file_type === 'image' || !empty($thumbnailFilePath) ? '' : 'display: none;' }}">
                                            </div>

                                            {{-- Video Preview --}}
                                            <div class="col-auto p-0">
                                                <video id="media-video-preview" controls
                                                    style="max-width: 260px; max-height: 100%; border-radius: 6px; border: 1px solid #ddd; background: #f8f8f8;
                                                        {{ $data['file']->file_type === 'video' ? '' : 'display: none;' }}">
                                                    @if ($data['file']->file_type === 'video')
                                                        <source src="{{ asset($imageFilePath) }}" type="video/mp4">
                                                    @endif
                                                </video>
                                            </div>
                                        </div>
                                    </div>
 
                                    {{-- --------------------------------------- Uploads container---------------------- --}}
                                    <div class="uploads-container row">
                                        <div class="uploads col-md-5" id="image-upload-section" style="padding-top: 35px">
                                            <div class="uploads-box">
                                                @if (isset($data['file']->file_type) && $data['file']->file_type === 'video')
                                                    <h3 class="-title">Upload Thumbnail <span
                                                            class="text-danger">*</span></h3>
                                                @else
                                                    <h3 class="-title">Upload Web Story Image <span
                                                            class="text-danger">*</span></h3>
                                                @endif

                                                <p class="-paragraph px-5">
                                                <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                    <li>Only .jpeg, .jpg and .png files are allowed*</li>
                                                    <li class="text-left">The image size must not exceed 200 KB.</li>
                                                    <li class="text-left">Image dimension should be (720x1280) px.</li>
                                                </ul>
                                                </p>

                                                @if (isset($data['file']->file_type) && $data['file']->file_type === 'video')
                                                    @if (isset($data['file']->thumb_path))
                                                        @php
                                                            $thumbnailFileName = basename($data['file']->thumb_path);
                                                        @endphp
                                                        <p>Saved Thumbnail: <strong>{{ $thumbnailFileName }}</strong></p>
                                                    @endif
                                                @else
                                                    @if (isset($data['file']->filename))
                                                        <p>Saved Image: <strong>{{ $data['file']->filename }}</strong></p>
                                                    @endif
                                                @endif

                                                <label for="file-input" class="drop-container" style="margin-top: 40px;">
                                                    <span class="drop-title">Drop files here</span> or
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
                                            </div>
                                        </div>

                                        <div class="uploads col-md-5" id="video-upload-section"
                                            style="padding-top: 35px">
                                            <div class="uploads-box">
                                                <h3 class="-title">Upload Video <span class="text-danger">*</span></h3>

                                                <p class="-paragraph px-5">
                                                <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                    <li>Only .mp4 video files are allowed*</li>
                                                    <li class="text-left">File size must not exceed 20MB.</li>
                                                    <li class="text-left">File duration must not exceed 60 seconds.</li>
                                                </ul>
                                                </p>

                                                @if (isset($data['file']->filename))
                                                    <p>Saved Video: <strong>{{ $data['file']->filename }}</strong></p>
                                                @endif

                                                <label for="file-input-video" class="drop-container">
                                                    <span class="drop-title">Drop files here</span> or
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileType = document.getElementById('file_type').value;
            const imageSection = document.getElementById('image-upload-section');
            const videoSection = document.getElementById('video-upload-section');

            if (fileType === 'image') {
                imageSection.style.display = 'block';
                videoSection.style.display = 'none';
            } else if (fileType === 'video') {
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

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.querySelector('#image-upload-section input[type="file"]');
            const videoInput = document.querySelector('#video-upload-section input[type="file"]');
            const fileType = document.getElementById('file_type')?.value || '';

            const imagePreview = document.getElementById('media-image-preview');
            const videoPreview = document.getElementById('media-video-preview');

            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        imagePreview.src = URL.createObjectURL(file);
                        imagePreview.style.display = 'block';

                        // If file_type is 'image', hide the video
                        if (fileType === 'image') {
                            videoPreview.src = '';
                            videoPreview.style.display = 'none';
                        }
                    }
                });
            }

            if (videoInput) {
                videoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('video/')) {
                        if (fileType === 'video') {
                            videoPreview.src = URL.createObjectURL(file);
                            videoPreview.style.display = 'block';
                        }
                    }
                });
            }
        });
    </script>




    <script>
        // Pass server-side data to JS variables
        const existingImage = @json(isset($data['file']->filename) && $data['file']->file_type === 'image' ? true : false);
        const existingVideo = @json(isset($data['file']->filename) && $data['file']->file_type === 'video' ? true : false);
        const existingThumbnail = @json(isset($data['file']->thumb_path) ? true : false);
    </script>

    <script>
        function validateUploadForm() {
            const fileType = document.getElementById('file_type').value;
            const imageInput = document.querySelector('#image-upload-section input[type="file"]');
            const videoInput = document.querySelector('#video-upload-section input[type="file"]');
            const storyName = document.getElementById('description').value;

            const hasNewImage = imageInput && imageInput.files.length > 0;
            const hasNewVideo = videoInput && videoInput.files.length > 0;

            if (!storyName.trim()) {
                alert('Please enter web story file title.');
                return false;
            }

            if (!fileType) {
                alert('Please select a file type.');
                return false;
            }


            if (fileType === 'image') {
                if (!existingImage && !hasNewImage) {
                    alert('Please upload an image file.');
                    return false;
                }

                if (hasNewImage) {
                    const file = imageInput.files[0];
                    const extension = file.name.split('.').pop().toLowerCase();
                    const sizeKB = file.size / 1024;
                    const allowedExtensions = ['jpeg', 'jpg', 'png'];

                    if (!allowedExtensions.includes(extension)) {
                        alert('Only .jpeg, .jpg, and .png files are allowed.');
                        return false;
                    }

                    if (sizeKB > 200) {
                        alert('Image size must not exceed 200 KB.');
                        return false;
                    }
                }
            }

            if (fileType === 'video') {

                // CASE 4: Neither exist in DB
                if (!existingVideo && !existingThumbnail) {
                    if (!hasNewVideo || !hasNewImage) {
                        alert('Please upload both video and thumbnail as none exists.');
                        return false;
                    }
                }

                // CASE 5: Only one exists
                if ((existingVideo && !existingThumbnail) || (!existingVideo && existingThumbnail)) {
                    if (!hasNewVideo || !hasNewImage) {
                        alert('One of the required files is missing. Please upload both video and thumbnail.');
                        return false;
                    }
                }

                // CASE 1/2/3: Both exist, uploading one or both is allowed

                if (hasNewImage) {
                    const img = imageInput.files[0];
                    const ext = img.name.split('.').pop().toLowerCase();
                    const size = img.size / 1024;
                    const allowed = ['jpg', 'jpeg', 'png'];

                    if (!allowed.includes(ext)) {
                        alert('Only .jpeg, .jpg, and .png files are allowed for thumbnail.');
                        return false;
                    }

                    if (size > 200) {
                        alert('Thumbnail image size must not exceed 200 KB.');
                        return false;
                    }
                }

                if (hasNewVideo) {
                    const vid = videoInput.files[0];
                    const ext = vid.name.split('.').pop().toLowerCase();
                    const size = vid.size / (1024 * 1024);

                    if (ext !== 'mp4') {
                        alert('Only .mp4 video files are allowed.');
                        return false;
                    }

                    if (size > 20) {
                        alert('Video size must not exceed 20 MB.');
                        return false;
                    }
                }
            }

            return true; // allow normal submission for image
        }
    </script>
@endsection
