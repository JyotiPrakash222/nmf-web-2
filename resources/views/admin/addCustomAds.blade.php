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
                                <h3 class="card-title mb-0 fs-5">Add Advertisement</h3>
                            </div>
                            <form class="form-group" method="post" action="{{ asset('ads/add') }}"
                                enctype="multipart/form-data" onsubmit="return validateUploadForm()">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="input-field">
                                            <input class="at-title" id="location" placeholder="" autocomplete="off"
                                                type="text" name="location" value="{{ old('location') }}"
                                                oninput="clearError('location-error')" />
                                            <label for="location">Ads Location Name <span
                                                    class="text-danger">*</span></label>
                                            @error('location')
                                                <div class="input-group-append" id="location-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('location') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="input-field col-md-6">
                                            <input autocomplete="off" type="text" name="GoogleClient" placeholder=""
                                                value="{{ old('GoogleClient') }}" id="GoogleClient"
                                                oninput="clearError('GoogleClient-error')" />
                                            <label for="GoogleClient">Google Client Id</label>
                                            @error('GoogleClient')
                                                <div class="input-group-append" id="GoogleClient-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('GoogleClient') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="input-field col-md-6">
                                            <input autocomplete="off" type="text" name="GoogleSlot" placeholder=""
                                                value="{{ old('GoogleSlot') }}" id="GoogleSlot"
                                                oninput="clearError('GoogleSlot-error')" />
                                            <label for="GoogleSlot">Google Slot Id</label>
                                            @error('GoogleSlot')
                                                <div class="input-group-append" id="GoogleSlot-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('GoogleSlot') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="input-field">
                                            <input autocomplete="off" type="text" name="link" placeholder=""
                                                value="{{ old('link') }}" id="link"
                                                oninput="clearError('link-error')" />
                                            <label for="link">Custom Link</label>
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
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 col-sm-12">
                                            <label class="customLable" for="primicat">Ads Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="js-example-basic-single form-select" id="ads_type"
                                                data-width="100%" name="ads_type" oninput="clearError('ads_type-error')">

                                                <option value="1"
                                                    {{ old('ads_type', '1') === '1' ? 'selected' : '' }}>
                                                    Google
                                                </option>
                                                <option value="0"
                                                    {{ old('ads_type', '1') === '0' ? 'selected' : '' }}>
                                                    Custom
                                                </option>
                                            </select>
                                            @error('ads_type')
                                                <div class="input-group-append" id="ads_type-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('ads_type') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <label class="customLable" for="primicat">Page Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="js-example-basic-single form-select" id="page_type"
                                                data-width="100%" name="page_type"
                                                oninput="clearError('page_type-error')">

                                                @foreach (\App\Models\Ads::PAGE_TYPES as $type)
                                                    <option value="{{ $type }}" {{ old('page_type') === $type ? 'selected' : '' }}>
                                                        {{ ucfirst($type) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('page_type')
                                                <div class="input-group-append" id="page_type-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('page_type') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="row align-items-center justify-content-center mb-3" style="gap: 20px;">
                                        <label class="customLable mx-auto d-block text-center">Image Preview</label>
                                        <div class="col-auto p-0">
                                            <img id="image-preview" src="#" alt="Preview"
                                                style="display: none; max-width: 100%; max-height: 100%; border-radius: 6px; border: 1px solid #ddd; background: #f8f8f8;" />
                                        </div>
                                    </div>



                                    {{-- --------------------------------------- Uploads container---------------------- --}}
                                    <div class="uploads-container row">
                                        <div class="uploads col-md-5" id="image-upload-section"
                                            style="padding-top: 35px">
                                            <div class="uploads-box">
                                                <h3 class="-title">Upload Ads Image <span class="text-danger">*</span>
                                                </h3>
                                                <p class="-paragraph px-5">
                                                <ul class="-paragraph-content text-start" style="color: #ff3131d9;">
                                                    <li>Only .jpeg, .jpg and .png files are allowed*</li>
                                                    <li class="text-left">The image size must not exceed 200 KB.</li>
                                                    <li class="text-left">Image dimension should be based on Ads dimension.
                                                    </li>
                                                </ul>
                                                </p>
                                                <label for="image_file" class="drop-container "
                                                    style="margin-top: 40px;">
                                                    <span class="drop-title">Drop files here</span>
                                                    or
                                                    <input type="file" accept=".jpeg,.jpg,.png,.webp"
                                                        name="image_file" id="image_file"
                                                        oninput="clearError('image_file-error')">
                                                </label>
                                                @error('image_file')
                                                    <div class="input-group-append" id="image_file-error">
                                                        <div class="input-group-text text-danger">
                                                            {{ $errors->first('image_file') }}
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
            const preview = document.getElementById('image-preview');

            if (imageInput && preview) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];

                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            preview.src = event.target.result;
                            preview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.src = '#';
                        preview.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <script>
        function validateUploadForm() {
            let isValid = true;

            // Get field values
            const location = document.getElementById("location").value.trim();
            const adsType = document.getElementById("ads_type").value;
            const pageType = document.getElementById("page_type").value;
            const googleClient = document.getElementById("GoogleClient").value.trim();
            const googleSlot = document.getElementById("GoogleSlot").value.trim();
            const link = document.getElementById("link").value.trim();
            const imageFileInput = document.getElementById("image_file");
            const imageFile = imageFileInput.files[0];

            // Clear all previous error messages
            const errorFields = ['location', 'ads_type', 'page_type', 'GoogleClient', 'GoogleSlot', 'link', 'image_file'];
            errorFields.forEach(field => clearError(`${field}-error`));

            if (!location.trim()) {
                showError('location-error', 'Location is required.');
                isValid = false;
            }

            if(!pageType.trim()){
                showError('page_type-error', 'Page type is required.');
                isValid = false;
            }

            if (!adsType.trim()) {
                showError('ads_type-error', 'Ads type is required.');
                isValid = false;

            } else if (adsType === "1") {

                if (!googleClient.trim()) {
                    showError('GoogleClient-error', 'Google Client ID is required.');
                    isValid = false;
                }
                if (!googleSlot.trim()) {
                    showError('GoogleSlot-error', 'Google Slot ID is required.');
                    isValid = false;
                }

            } else if (adsType === "0") {

                if (!link.trim()) {
                    showError('link-error', 'Custom link is required.');
                    isValid = false;
                }

                if (!imageFile) {
                    showError('image_file-error', 'Custom Ads image is required.');
                    isValid = false;
                }
            }

            if (imageFile) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                const maxSize = 200 * 1024; // 200 KB

                if (!allowedTypes.includes(imageFile.type)) {
                    showError('image_file-error', 'Only .jpeg, .jpg, .png, .webp files are allowed.');
                    isValid = false;
                } else if (imageFile.size > maxSize) {
                    showError('image_file-error', 'Image must not exceed 200 KB.');
                    isValid = false;
                }
            }

            return isValid;
        }

        function showError(errorId, message) {
            let errorElement = document.getElementById(errorId);
            if (!errorElement) {
                // Dynamically create if not present
                const baseId = errorId.replace('-error', '');
                const fieldGroup = document.getElementById(baseId);
                errorElement = document.createElement('div');
                errorElement.id = errorId;
                errorElement.className = 'input-group-append';
                errorElement.innerHTML = `<div class="input-group-text text-danger">
                <i class="fa-solid fa-circle-exclamation me-1"></i>${message}</div>`;
                fieldGroup?.parentNode?.appendChild(errorElement);
            } else {
                errorElement.style.display = 'block';
                errorElement.innerHTML = `<div class="input-group-text text-danger">
                <i class="fa-solid fa-circle-exclamation me-1"></i>${message}</div>`;
            }
        }
    </script>
@endsection
