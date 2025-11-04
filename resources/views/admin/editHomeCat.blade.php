@extends('layouts.adminNew')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEJ1Q4S2O3vF74q8+S3uLqK2Sh6Qw5aPbZ27O38V5xx1rR9BhpzB8N5w0yGdO" crossorigin="anonymous">

    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card card-primary">
                            <div class="card-header d-flex justify-content-between align-items-center"
                                style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">EDIT HOME CATEGORY</h3>
                            </div>

                            <form class="form-group" method="post"
                                action="{{ asset('home-category/edit') }}/{{ $homeCategory->id }}"
                                enctype="multipart/form-data" onsubmit="return validateEditForm()">
                                @csrf
                                <div class="card-body">
                                    {{-- Title --}}
                                    <div class="form-group row">
                                        <div class="input-field col-md-6">
                                            <input type="text" name="title" id="title" placeholder=""
                                                autocomplete="off" value="{{ old('title', $homeCategory->title) }}"
                                                oninput="clearError('title-error')" />
                                            <label for="title">Section Title <span class="text-danger">*</span></label>
                                            @error('title')
                                                <div class="input-group-append" id="title-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1">
                                                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Category ID --}}
                                        <div class="input-field col-md-6">
                                            <select id="catid" class="form-select" name="catid"
                                                oninput="clearError('catid-error')">
                                                <option value="0">Select Category <span class="text-danger">*</span>
                                                </option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ old('catid', $homeCategory->catid) == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('catid')
                                                <div class="input-group-append" id="catid-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}</span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Section Type and Status --}}
                                    <div class="form-group row">
                                        <div class="col-md-6 mb-2">
                                            <label class="customLable" for="type">Section Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="type" name="type"
                                                oninput="clearError('type-error')">
                                                <option value="0">Select type</option>
                                                @foreach (\App\Models\HomeSection::TYPES as $type)
                                                    <option value="{{ $type }}"
                                                        {{ old('type', $homeCategory->type) === $type ? 'selected' : '' }}>
                                                        {{ ucfirst($type) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="input-group-append" id="type-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}</span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label class="customLable" for="status">Status <span
                                                    class="text-danger">*</span></label>
                                            <select id="status" name="status" class="form-select">
                                                <option value="1"
                                                    {{ old('status', $homeCategory->status) == 1 ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="0"
                                                    {{ old('status', $homeCategory->status) == 0 ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="input-group-append" id="status-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}</span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Banner Section --}}
                                    <div class="form-group row" id="banner_section">
                                        <div class="input-field col-md-6">
                                            <input type="text" id="image_url" name="image_url" placeholder=""
                                                value="{{ old('image_url', $homeCategory->image_url) }}"
                                                oninput="clearError('image_url-error')" />
                                            <label for="image_url">Image URL <span class="text-danger">*</span></label>
                                            @error('image_url')
                                                <div class="input-group-append" id="image_url-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}</span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="input-field col-md-6">
                                            <input type="text" id="banner_link" name="banner_link" placeholder=""
                                                value="{{ old('banner_link', $homeCategory->banner_link) }}"
                                                oninput="clearError('banner_link-error')" />
                                            <label for="banner_link">Banner Link <span
                                                    class="text-danger">*</span></label>
                                            @error('banner_link')
                                                <div class="input-group-append" id="banner_link-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}</span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Section & Sidebar Orders --}}
                                    <div class="form-group row" id="sequence_order">
                                        <div class="input-field col-md-6" id="section_order">
                                            <input type="number" name="section_order" placeholder=""
                                                value="{{ old('section_order', $homeCategory->type === 'section' ? $homeCategory->section_order : ($maxSectionOrder ?? 0) + 1) }}"
                                                oninput="clearError('section_order-error')" readonly />
                                            <label for="section_order">Section Order <span
                                                    class="text-danger">*</span></label>
                                            @error('section_order')
                                                <div class="input-group-append" id="section_order-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}</span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="input-field col-md-6" id="sidebar_sec_order">
                                            <input type="number" name="sidebar_sec_order" placeholder=""
                                                value="{{ old('sidebar_sec_order', $homeCategory->type === 'sidebar' ? $homeCategory->sidebar_sec_order : ($maxSidebarOrder ?? 0) + 1) }}"
                                                oninput="clearError('sidebar_sec_order-error')" readonly />
                                            <label for="sidebar_sec_order">Sidebar Section Order <span
                                                    class="text-danger">*</span></label>
                                            @error('sidebar_sec_order')
                                                <div class="input-group-append" id="sidebar_sec_order-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}</span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="button-container row">
                                        <button class="--btn btn-publish" type="submit" name="submit">Update</button>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.card -->
        </section>
    </div>

    @push('custom-scripts')
        <script>
            function clearError(errorId) {
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        </script>
    @endpush
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const catidSelect = document.getElementById('catid');
        const bannerSection = document.getElementById('banner_section');
        const sectionOrder = document.getElementById('section_order');
        const sidebarSecOrder = document.getElementById('sidebar_sec_order');

        // Get the original selected category from the backend (Blade will set it)
        let lastSelectedCategory = catidSelect.value;

        function toggleFields(clear = false) {
            const selected = typeSelect.value;

            if (clear) {
                clearError('section_order-error');
                clearError('sidebar_sec_order-error');
                clearError('image_url-error');
                clearError('banner_link-error');
            }

            bannerSection.style.display = 'none';
            sectionOrder.style.display = 'none';
            sidebarSecOrder.style.display = 'none';

            // Show based on type
            if (selected === 'section') {
                sectionOrder.style.display = 'block';
            } else if (selected === 'sidebar') {
                sidebarSecOrder.style.display = 'block';
            } else if (selected === 'banner') {
                bannerSection.style.display = bannerSection.classList.contains('row') ? 'flex' : 'block';
            }

            // Disable or restore category dropdown based on type
            if (['banner', 'other'].includes(selected)) {
                // Store current value before disabling
                if (!catidSelect.disabled) {
                    lastSelectedCategory = catidSelect.value;
                }
                catidSelect.value = '0';
                catidSelect.disabled = true;
            } else {
                catidSelect.disabled = false;
                if (lastSelectedCategory && lastSelectedCategory !== '0') {
                    catidSelect.value = lastSelectedCategory;
                }
            }
        }

        // Trigger on load in case old value is present
        toggleFields();

        // Add event listener
        //typeSelect.addEventListener('change', toggleFields);
        typeSelect.addEventListener('change', () => toggleFields(true)); // only clear on user change
    });
</script>

<script>
    function validateEditForm() {
        const typeSelect = document.getElementById('type');
        const titleInput = document.getElementById('title');
        const catidSelect = document.getElementById('catid');

        const errorFields = ['title', 'catid', 'type', 'status', 'image_url', 'banner_link', 'section_order', 'sidebar_sec_order'];
        errorFields.forEach(field => clearError(`${field}-error`));

        let hasError = false;
        const selectedType = typeSelect.value;

        // Always required
        if (!titleInput.value.trim()) {
            showError('title-error', 'Title is required');
            hasError = true;
        }

        if (!['banner', 'other'].includes(selectedType)) {
            if (!catidSelect.value || catidSelect.value === "0") {
                showError('catid-error', 'Category is required');
                hasError = true;
            }
        }

        if (!selectedType || selectedType === "0") {
            showError('type-error', 'Section Type is required');
            hasError = true;
        }

        // Type-specific validation
        if (selectedType === 'section') {
            const sectionOrderInput = document.querySelector('input[name="section_order"]');
            if (!sectionOrderInput.value.trim() || sectionOrderInput.value.trim() === "0") {
                showError('section_order-error', 'Section order is required');
                hasError = true;
            }
        }

        if (selectedType === 'sidebar') {
            const sidebarOrderInput = document.querySelector('input[name="sidebar_sec_order"]');
            if (!sidebarOrderInput.value.trim() || sidebarOrderInput.value.trim() === "0") {
                showError('sidebar_sec_order-error', 'Sidebar order is required');
                hasError = true;
            }
        }

        if (selectedType === 'banner') {
            const imageUrlInput = document.querySelector('input[name="image_url"]');
            const bannerLinkInput = document.querySelector('input[name="banner_link"]');

            if (!imageUrlInput.value.trim()) {
                showError('image_url-error', 'Image URL is required');
                hasError = true;
            }

            if (!bannerLinkInput.value.trim()) {
                showError('banner_link-error', 'Banner Link is required');
                hasError = true;
            }
        }

        return !hasError;
    }

    function showError(errorId, message) {
        let errorElement = document.getElementById(errorId);
        if (!errorElement) {
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