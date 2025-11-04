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
    <style>
        .btn-delete {
            background: linear-gradient(to bottom, #f25c5c, #cc3a3a);
            max-width: 177px;
            min-height: 49px;
            font-size: 15px;
            line-height: 0;
            font-weight: 500;
            padding-bottom: 6px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!-- <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a href="{{ asset('/home') }}">Home</a></li>
                                                <li class="breadcrumb-item"><a href="{{ asset('/posts') }}">Posts</a></li>
                                                <li class="breadcrumb-item active">Edit Post</li>
                                            </ol> -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <?php
                            $cat = App\Models\Category::where('id', $data['blogs']->categories_ids)->first();
                            ?>
                            <div class="card-header d-flex justify-content-between align-items-center"
                                style="padding-block: 10px;">
                                <h3 class="card-title mb-0 fs-5">EDIT {{ request()->get('from') }} ARTICLE</h3>
                                @if (!empty($cat->site_url))
                                    <a class="viewBtn"
                                        href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($data['blogs']->site_url) ? $data['blogs']->site_url : ''; ?>"><span><i
                                                class="fa-solid fa-table-list"></i></span> View Article</a>
                                @endif

                                <div class="d-flex gap-2">
                                    @if ($data['blogs']->status == App\Models\Blog::STATUS_ARCHIVED)
                                        <button class="--btn btn-publish" name="unpublish"
                                            onclick="submitArticleForm('restore')">Restore</button>
                                        <a class="--btn btn-delete" name="delete" value="delete"
                                            href="{{ asset('posts/del/') }}/{{ $data['blogs']->id }}">Delete</a>
                                    @elseif ($data['blogs']->status == App\Models\Blog::STATUS_DRAFT)
                                        <button class="--btn btn-publish" name="publish"
                                            onclick="submitArticleForm('pub')">Publish</button>
                                        <button class="--btn btn-save" name="draft" onclick="submitArticleForm('du')">Save
                                            as Draft</button>
                                    @elseif ($data['blogs']->status == App\Models\Blog::STATUS_UNPUBLISHED)
                                        <button class="--btn btn-publish" name="publish"
                                            onclick="submitArticleForm('pub')">Publish</button>
                                    @elseif ($data['blogs']->status == App\Models\Blog::STATUS_PUBLISHED)
                                        <button class="--btn btn-publish" name="publish"
                                            onclick="submitArticleForm('pub')">Publish</button>
                                        <button class="--btn btn-save" name="unpublish"
                                            onclick="submitArticleForm('unpub')">Unpublish</button>
                                    @elseif ($data['blogs']->status == App\Models\Blog::STATUS_SCHEDULED)
                                        <button class="--btn btn-publish" name="publish"
                                            onclick="submitArticleForm('pub')">Publish</button>
                                    @endif
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <?php
                            if (isset($_GET['status'])) {
                                $filteredstatus = $_GET['status'];
                            } else {
                                $filteredstatus = '';
                            }
                            //NLW035:04Oct2024:Added:Start
                            if (isset($data['blogs']->user_id)) {
                                $blogsavedbyuser = App\Models\User::where('id', $data['blogs']->user_id)->get()->first();
                            }
                            $loggedinuserid = Auth::id();
                            
                            //NLW035:04Oct2024:Added:End
                            
                            ?>
                            <form id="articleEditForm" class="form-group" method="post"
                                action="{{ asset('posts/edit') }}/{{ $data['blogs']->id }}?status={{ $filteredstatus }}">
                                @csrf
                                <!-- <div class="button-container row">
                                                                <button class="--btn btn-publish" name="publish" value="pub">Publish</button>
                                                                <button class="--btn btn-save" name="draft" value="du">Save as Draft</button>
                                                                <button class="--btn btn-save" name="isunpublish" value="unpub" onclick="document.getElementById('isunpublish').value='true';">Unpublish</button>
                                                    </div> -->
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
                                                id="notification_title" placeholder="">
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
                                        <div class=" input-field">
                                            <input class="at-title" type="text" name="name"
                                                value="{{ $data['blogs']->name }}" id="name"
                                                oninput="clearError('name-error')" />
                                            <label for="name">Edit Title <span class="text-danger">*</span></label>
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
                                                value="{{ $data['blogs']->short_title }}" />
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
                                        <div class=" input-field col-md-6">
                                            <input type="text" name="eng_name" value="{{ $data['blogs']->eng_name }}"
                                                id="eng_name" oninput="clearError('eng_name-error', 'site_url-error')" />
                                            <label for="name">Title URL <span class="text-danger">*</span></label>
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
                                                <div class="input-group-append" id="site_url-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $message }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class=" input-field col-md-6">
                                            <input type="text" name="tags" value="{{ $data['blogs']->tags }}"
                                                id="tags" oninput="clearError('tags-error')" />
                                            <label for="username">Tags</label>
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
                                        {{-- <div class=" input-field col-md-4">
                                            <input type="text" name="name2" value="{{ $data['blogs']->name2 }}"
                                                id="name2" oninput="clearError('name2-error')" />
                                            <label for="name">Edit Title on Detail</label>
                                            @error('name2')
                                                <div class="input-group-append" id="name2-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('name2') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div> --}}
                                    </div>
                                    <div class="form-group row ">
                                        <div class=" input-field col-md-6">
                                            <input type="text" name="keyword"
                                                value="{{ $data['blogs']->keyword ?: 'NMF News' }}" id="keyword"
                                                oninput="clearError('keyword-error')" />
                                            <label for="username">Keywords</label>
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
                                                id="authName" name="author" oninput="clearError('author-error')">
                                                <option value="">Select Author Name</option>
                                                @foreach ($data['authors'] as $author)
                                                    <option value="{{ $author->id }}" <?php if ($data['blogs']->author == $author->id) {
                                                        echo 'selected';
                                                    } ?>>
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
                                        <div class="col-md-6">
                                            <label class="customLable" for="primicat">Prime category <span
                                                    class="text-danger">*</span></label>
                                            <?php $cat = explode(',', $data['blogs']->categories_ids); ?>
                                            <select id="primicat" class="js-example-basic-single form-select"
                                                data-width="100%" name="category" oninput="clearError('category-error')">
                                                <option value="">Select Prime Category</option>
                                                @foreach ($data['categories'] as $category)
                                                    <option value="{{ $category->id }}" <?php if (in_array($category->id, $cat)) {
                                                        echo 'selected';
                                                    } ?>>
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
                                    </div>
                                    <div class="form-group row">

                                        {{-- <div class="col-md-3">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="sequence" oninput="clearError('sequence-error')">
                                                <option value="0">Select Sequence</option>
                                                <?php $options = config('global.sequence_global_array'); ?>
                                                @foreach ($options as $value => $label)
                                                    <option value="{{ $value }}" <?php if ($value == $data['blogs']->sequence_id) {
                                                        echo 'selected=true';
                                                    } ?>>
                                                        {{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('sequence')
                                                <div class="input-group-append" id="sequence-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('sequence') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div> --}}
                                        <div class="col-md-6">
                                            <?php $sta = explode(',', $data['blogs']->state_ids); ?>
                                            <label class="customLable" for="statecat">State</label>
                                            <select class="js-example-basic-single form-select" id="statecat"
                                                data-width="100%" name="state" oninput="clearError('state-error')">
                                                <option value="0">Select State</option>
                                                @foreach ($data['states'] as $state)
                                                    <option value="{{ $state->id }}" <?php if (in_array($state->id, $sta)) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $state->name }}</option>
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
                                            <?php $dis = explode(',', $data['blogs']->district_ids); ?>
                                            <select class="js-example-basic-single form-select" id="distcat"
                                                data-width="100%" name="district" oninput="clearError('district-error')">
                                                <option value="0">Select District</option>
                                                @foreach ($data['district'] as $district)
                                                    <option value="{{ $district->id }}" <?php if (in_array($district->id, $dis)) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $district->name }}</option>
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
                                        {{-- <div class="col-md-4">
                                            <label class="customLable" for="subcat">Sub Category</label>
                                            <?php $sub_pole_cat = explode(',', $data['blogs']->sub_category_id); ?>
                                            <select class="js-example-basic-single form-select" id="subcat"
                                                data-width="100%" name="sub_cat" oninput="clearError('sub_cat-error')">
                                                <option value="">Select Sub Category</option>
                                                <?php $poleblogs = App\Models\SubCategory::get()->all(); ?>
                                                @foreach ($poleblogs as $poleblog)
                                                    <option value="{{ $poleblog->id }}" <?php if (in_array($poleblog->id, $sub_pole_cat)) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $poleblog->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('sub_cat')
                                                <div class="input-group-append" id="sub_cat-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('sub_cat') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div> --}}
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 mb-2">
                                            <label class="customLable" for="isLive">Live Status</label>
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                id="isLive" name="isLive" oninput="clearError('isLive-error')">
                                                <option value="0"
                                                    {{ $data['blogs']->isLive == 0 ? 'selected' : '' }}>Normal
                                                </option>
                                                <option value="1"
                                                    {{ $data['blogs']->isLive == 1 ? 'selected' : '' }}>Live
                                                </option>
                                                <option value="2"
                                                    {{ $data['blogs']->isLive == 2 ? 'selected' : '' }}>Remove Red
                                                    Live Tag</option>
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
                                            <label class="customLable" for="morecat">More Categories</label>
                                            <?php $multi_cat = explode(',', $data['blogs']->mult_cat); ?>
                                            <select multiple="" class="form-select" id="exampleFormControlSelect2"
                                                name="mult_cat[]" oninput="clearError('mult_cat-error')" id="morecat">
                                                <option value="">Select More Category</option>
                                                @foreach ($data['categories'] as $category)
                                                    <option value="{{ $category->id }}" <?php if (in_array($category->id, $multi_cat)) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $category->name }}</option>
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
                                            <div class="form-group mt-3">
                                                <div class="checkbox-wrapper-46">
                                                    <input type="checkbox" id="cbx-46" name="breaking_status"
                                                        <?php if ($data['blogs']->breaking_status == 1) {
                                                            echo 'checked=true';
                                                        } ?> value="1" class="inp-cbx" />
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
                                                    <input type="checkbox" id="cbx-47" name="sequence"
                                                        <?php if (!empty($data['blogs']->sequence_id)) {
                                                            echo 'checked=true';
                                                        } ?> value="1" class="inp-cbx"
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
                                                        <?php if ($data['blogs']->ispodcast_homepage == 1) {
                                                            echo 'checked=true';
                                                        } ?> value="1" class="inp-cbx" />
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
                                                            {{ old('sequence_order', $data['blogs']->sequence_id ?? '') == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
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
                                        <div class=" input-field">
                                            <input type="text" name="sort_desc"
                                                value="{{ $data['blogs']->sort_description }}" id="sort_desc"
                                                oninput="clearError('sort_desc-error')" />
                                            <label for="username">Brief <span class="text-danger">*</span></label>
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
                                        <textarea id="default" name="description">
                                          {{ $data['blogs']->description }}
                                        </textarea>
                                    </div>
                                    {{-- ------------------ video link------------- --}}
                                    <div class="form-group row">
                                        <div class=" input-field col-md-6">
                                            <input type="text" name="link" value="{{ $data['blogs']->link }}"
                                                id="sort_desc" />
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
                                            <input type="text" name="credits" value="{{ $data['blogs']->credits }}"
                                                id="credits" />
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
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fw-semibold text-capitalize" for="scheduletime">Schedule
                                            Publish Time</label>
                                        <div class="input-group">
                                            <!-- Input -->
                                            <input id="scheduletime" name="scheduletime" class="form-control"
                                                placeholder="Select Date and Time" type="text"
                                                value="{{ $data['blogs']->published_at ? \Carbon\Carbon::parse($data['blogs']->published_at)->format('Y-m-d\TH:i') : '' }}" />

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

                                        </div>
                                    </div>

                                    <!--  <div class="input-field col-md-4">
                                        <div class="input-group">
                                            <input id="scheduletime"
                                                class="form-control @error('scheduletime') is-invalid @enderror"
                                                type="datetime-local" name="scheduletime"
                                                value="{{ $data['blogs']->published_at ? \Carbon\Carbon::parse($data['blogs']->published_at)->format('Y-m-d\TH:i') : '' }}"
                                                placeholder="Select Date and Time" />

                                            @error('scheduletime')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                                                    {{ $errors->first('scheduletime') }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div> -->
                                </div>

                        </div>
                        {{-- image container --}}
                        <?php
                        $imageUrl = config('global.blog_images_everywhere')($data['blogs']);
                        ?>
                        <div class="img-prev">
                            <label class="customLable mx-auto d-block text-center mb-2">Image Preview</label>
                            @if (!empty($imageUrl))
                                <div class="d-flex justify-content-center">
                                    <img class="col-md-5 rounded" src="{{ asset($imageUrl) }}"
                                        alt="{{ $data['blogs']->name }}">
                                </div>
                            @endif
                        </div>
                        {{-- --------------------------------------- Uploads container---------------------- --}}
                        <div class="uploads-container row">
                            <div class="uploads col-md-5">
                                <div class="uploads-box">
                                    <span class="-title">Select Thumb Images</span>
                                    <label class="drop-container" style="height: 174px; margin-top:92px">
                                        <input type="hidden" name="thumb_images" id="id_thumb_images"
                                            value="{{ isset($data['blogs']->thumb_images) ? $data['blogs']->thumb_images : 0 }}"
                                            id="id_thumb_images">
                                        <input type="text" class="form-control" id="name_thumb_images"
                                            value="{{ isset($data['blogs']->thumbnail->file_name) ? $data['blogs']->thumbnail->file_name : '' }}"
                                            id="name_thumb_images" disabled>
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
                                        <input type="file" accept=".jpeg,.jpg,.png" name="file" id="file-input">
                                        <button class="--btn btn-submit mt-4" type="button" id="save_image">Save
                                            Image</button>
                                    </label>
                                    </>
                                </div>
                            </div>
                        </div>
                        {{-- Action button --}}
                        <input type="hidden" name="from" value="{{ request()->get('from') }}">
                        <div class="button-container row">
                            @if ($data['blogs']->status == App\Models\Blog::STATUS_ARCHIVED)
                                <button class="--btn btn-publish" name="unpublish" value="restore">Restore</button>
                                <a class="--btn btn-delete" name="delete" value="delete"
                                    href="{{ asset('posts/del/') }}/{{ $data['blogs']->id }}">Delete</a>
                            @elseif ($data['blogs']->status == App\Models\Blog::STATUS_DRAFT)
                                <button class="--btn btn-publish" name="publish" value="pub">Publish</button>
                                <button class="--btn btn-save" name="draft" value="du">Save as
                                    Draft</button>
                            @elseif ($data['blogs']->status == App\Models\Blog::STATUS_UNPUBLISHED)
                                <button class="--btn btn-publish" name="publish" value="pub">Publish</button>
                            @elseif ($data['blogs']->status == App\Models\Blog::STATUS_PUBLISHED)
                                <button class="--btn btn-publish" name="publish" value="pub">Publish</button>
                                <button class="--btn btn-save" name="unpublish" value="du">Unpublish</button>
                            @elseif ($data['blogs']->status == App\Models\Blog::STATUS_SCHEDULED)
                                <button class="--btn btn-publish" name="publish" value="pub">Publish</button>
                            @endif
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
                        <div class="modal-body" style="height: 400px; overflow: scroll;">

                            <div class="col-md-12 mb-2">
                                <input type="text" id="imageSearchInput" class="form-control"
                                    placeholder="Search image by name">
                            </div>

                            {{-- AJAX-loaded content --}}
                            <div id="imageContentWrapper">
                                @include('admin.partials.image_modal_body', ['data' => $data])
                            </div>

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
                        // text: '📦 Box Text',
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
            // $('.image_sec').click(function() {
            $('#imageContentWrapper').on('click', '.image_sec', function() {
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
        <script>
            function submitArticleForm(actionType) {
                const form = document.getElementById('articleEditForm');
                if (!form) {
                    alert('Form not found!');
                    return;
                }

                // Clean up any previously added action inputs
                ['publish', 'draft', 'unpublish', 'restore'].forEach(name => {
                    const input = form.querySelector(`input[name="${name}"]`);
                    if (input) input.remove();
                });

                // Create the correct input name based on actionType
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';

                // Set name and value dynamically
                switch (actionType) {
                    case 'pub':
                        hiddenInput.name = 'publish';
                        hiddenInput.value = '1';
                        break;
                    case 'du':
                        hiddenInput.name = 'draft';
                        hiddenInput.value = '1';
                        break;
                    case 'unpub':
                        hiddenInput.name = 'unpublish';
                        hiddenInput.value = '1';
                        break;
                    case 'restore':
                        hiddenInput.name = 'unpublish';
                        hiddenInput.value = '1';
                        break;
                    default:
                        return; // don't submit if actionType is invalid
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
            //document.addEventListener('DOMContentLoaded', toggleSequenceDropdown);
            document.addEventListener('DOMContentLoaded', function() {
                toggleSequenceDropdown();
                toggleNotificationTitle(); // this ensures the title shows/hides on page load
            });
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
