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
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">ADD LIVE ARTICLE</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-group" method="post" action="{{ asset('posts/addlive') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row ">
                                        <div class="input-field col-md-12">
                                            <input placeholder="" autocomplete="off" type="text" name="name"
                                                id="name" oninput="clearError('name-error')"
                                                value="{{ old('name') }}" />
                                            <label for="name">Title <span class="text-danger">*</span></label>
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
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="live_blog_name" class="form_label mb-1">Live Article</label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="live_blog_name" oninput="clearError('live_blog_name-error')">
                                                <option value="">Select Live Article <span
                                                        class="text-danger">*</span></option>
                                                @foreach ($data['liveBlogOptions'] as $liveBlog)
                                                    <option value="{{ $liveBlog->id }}"
                                                        {{ old('live_blog_name') == $liveBlog->id ? 'selected' : '' }}
                                                        data-category="{{ $liveBlog->category->name }}"
                                                        data-category-id="{{ $liveBlog->category->id }}">
                                                        {{ $liveBlog->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('live_blog_name')
                                                <div class="input-group-append" id="live_blog_name-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('live_blog_name') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="author" class="form_label mb-1">Author name</label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="author" oninput="clearError('author-error')">
                                                <option value="">Select Author Name <span class="text-danger">*</span>
                                                </option>
                                                <?php $authors = App\Models\User::whereNot('id', 6)->get()->all(); ?>
                                                @foreach ($authors as $author)
                                                    <option value="{{ $author->id }}"
                                                        {{ old('author') == $author->id ? 'selected' : '' }}>
                                                        {{ $author->name }}</option>
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
                                        <div class="col-md-4">
                                            <label class="form_label mb-1">Category name</label>
                                            <div class="input-field ">
                                                <input type="text" id="categoryInput" class="form-control"
                                                    value="{{ old('category_name', $categoryNameMap[old('category')] ?? '') }}"
                                                    readonly>
                                               <input type="hidden" name="category" id="categoryIdInput" value="{{ old('category') }}">
                                            </div>
                                        </div>


                                        {{-- <div class="input-field col-md-6">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="category" oninput="clearError('category-error')">
                                                <option value="">Select Category</option>
                                                @foreach ($data['categories'] as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                        </div> --}}
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-5 pt">
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
                                                            <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                                {{ $errors->first('breaking_status') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- --------------------------------------- editor container---------------------- --}}
                                        <div class="Editor-block row mb-4" style="padding: 10px">
                                            <label for="exampleFormControlSelect2"
                                                style="font-size: 17px; color: #757575; margin-bottom: 8px">Live
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
                                                            <span class="me-1"><i
                                                                    class="fa-solid fa-circle-exclamation"></i>
                                                                {{ $errors->first('link') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- --------------------------------------- Uploads container---------------------- --}}
                                        <div class="uploads-container row">
                                            <div class="uploads col-md-5">
                                                <div class="uploads-box">
                                                    <span class="-title">Select Thumb Images</span>
                                                    <label class="drop-container" style="height: 174px; margin-top:92px">
                                                        <input type="hidden" name="images" id="id_thumb_images">
                                                        <input type="text" class="form-control" id="name_thumb_images"
                                                            disabled>
                                                        <button type="button" class="btn btn-thumb-img"
                                                            data-toggle="modal" data-target="#modal-thumb">
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
                                                        <li class="text-left">File size does not exceed 5MB.</li>
                                                    </ul>
                                                    </p>

                                                    <label for="file-input" class="drop-container">
                                                        <input type="file" accept=".jpeg,.jpg,.png" name="file"
                                                            id="file-input">
                                                        <button class="--btn btn-submit mt-4" type="button"
                                                            id="save_image">Save Image</button>
                                                    </label>
                                                    </>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Action button --}}
                                        <div class="button-container row">
                                            <button class="--btn btn-publish" name="publish"
                                                value="pub">Publish</button>
                                            <button class="--btn btn-save" name="draft" value="du">Save as
                                                Draft</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="modal fade" id="modal-thumb">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content mw-100">
                                <div class="modal-header">
                                    <h4 class="modal-title">Select Thumb Image</h4>

                                    <button type="button" class="close_btn" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <input type="hidden" id="image_thumb_name">
                                <input type="hidden" id="image_thumb_id">
                                <div class="modal-body" style="height: 400px; overflow: scroll;">
                                    <div class="row image_row">
                                        @foreach ($data['file'] as $file)
                                            <?php
                                            $imagePath = $file->full_path;
                                            if (strpos($imagePath, 'file') !== false) {
                                                $findFilePos = strpos($imagePath, 'file');
                                                $imageFilePath = substr($imagePath, $findFilePos);
                                                $imageFilePath = $imageFilePath . '/' . $file->file_name;
                                            }
                                            ?>
                                            @if ($file->file_type == 'image/jpeg' || $file->file_type == 'image/png')
                                                <div class="col-md-3 popup">
                                                    <img style="width: 100%;" class="image_sec"
                                                        data-name="{{ $file->file_name }}" data-id="{{ $file->id }}"
                                                        src="{{ asset($imageFilePath) }}" />
                                                    {{ $file->file_name }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="card-footer clearfix">
                                        {{ $data['file']->links() }}
                                    </div>
                                    <!-- <p>One fine body&hellip;</p> -->
                                </div>
                                <div class="modal-footer justify-content-between">
                                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
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
                toolbar: 'customtextbox | undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons',
                setup: function (editor) {
                    editor.ui.registry.addButton('customtextbox', {
                        // text: '📦 Box Text',
                        text: 'bayan',
                        tooltip: 'Insert custom boxed text',
                        onAction: function () {
                            const userInput = prompt("Enter text to display as a box:");
                            if (userInput) {
                                const encodedText = editor.dom.encode(userInput);
                                const boxedHTML = `<span class="custom-box">${encodedText}</span>&nbsp;`;
                                editor.insertContent(boxedHTML);
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
                content_css: '{{ asset("asset/new_admin/css/main_style.css") }}',

                /*Image Upload Settings */
                automatic_uploads: false,
                images_upload_handler: null,
	       /*NL1030:Image not loading in app */
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
            $('.image_sec').click(function() {
                $('.popup').removeAttr('style');
                $(this).parent().attr('style', 'border: 5px solid blue;');
                $('#image_name').val($(this).data('name'));
                $('#image_id').val($(this).data('id'));
                $('#image_thumb_name').val($(this).data('name'));
                $('#image_thumb_id').val($(this).data('id'));
            })
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
                        console.log(res);
                        if (res.success) {
                            $('#id_thumb_images').val(res.file_id);
                            $('#name_thumb_images').val(res.file_name);
                            $('#file-input').val('');
                        } else {
                            alert("Upload failed: " + res.message);
                        }
                    },
                    error: function() {
                        alert("Something went wrong during upload.");
                    }
                });
            });

            function clearError(errorId) {
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        </script>
        {{-- -category name fetched from live article selected- --}}
        <script>
            document.querySelector('select[name="live_blog_name"]').addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var categoryName = selectedOption.getAttribute('data-category');
                var categoryId = selectedOption.getAttribute('data-category-id');
                document.getElementById('categoryInput').value = categoryName || '';
                document.getElementById('categoryIdInput').value = categoryId || '';
            });
        </script>
    @endpush
@endsection
