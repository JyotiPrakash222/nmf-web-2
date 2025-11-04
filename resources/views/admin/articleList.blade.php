<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
@extends('layouts.adminNew')
@push('style')
    <link href="{{ asset('asset/new_admin/css/main_style.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <style>
        /* action buttons */
        .action_btn {
            padding-block: 3.5px;
            padding-inline: 8.5px;
            border-radius: 4px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;

        }

        .btn_view {
            background-color: #0381cf;

            &:hover {
                background-color: #0577bd;
                color: #fff;
            }

        }

        .btn_edit {
            background-color: #0381cf;
            margin-left: 1.5px
        }

        .btn_edit:hover {
            background-color: #0577bd;
            color: #fff;
        }

        .btn_delete {
            background-color: #ff0000;
            margin-left: 1.5px
        }

        .btn_delete:hover {
            background-color: #ca0808;
            color: #fff;
        }
        .status-unknown {
            background-color: #e2e3e5;
            color: #6c757d;
        }

        @media (max-width: 500px) {
            .btn_edit {
                margin-left: 5px
            }

            .btn_delete {
                margin-left: 5px
            }
        }
    </style>
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        {{-- <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">Article List</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section> --}}

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-2.5">
                                <h3 class="card-title mb-0 fs-5">PUBLISHED ARTICLES</h3>
                                <div class="card-tool ">
                                    <div class="input-group input-group-sm float-right  ">
                                        <a href="{{ asset('/posts') }}/add" class="--btn" style="background: #28364f">
                                            Add Article
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 px-3 px-md-5 gap-2 gap-md-0">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="dataTableExample_length">
                                        <label class="d-inline-flex gap-1 align-items-center">Show
                                            <select name="dataTableExample_length" id="entriesSelect"
                                                class="form-select select-down"
                                                onchange="window.location.href = '?perPage=' + this.value + '&page=1{{ isset($data['title']) && $data['title'] !== '' ? '&title=' . $data['title'] : '' }}';">
                                                <option value="30" <?php if ($data['perPage'] == '30') {
                                                    echo 'selected';
                                                } ?>>30</option>
                                                <option value="50" <?php if ($data['perPage'] == '50') {
                                                    echo 'selected';
                                                } ?>>50</option>
                                            </select> entries</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                    <form class="form-wrapper btn-group d-flex gap-2 flex-wrap flex-md-nowrap " ">
                                        <div class="group">
                                            <input id="query" class="input form-control" type="text"
                                                value="{{ $data['title'] }}" placeholder="Enter Title" name="title" />
                                        </div>
                                        <!-- Dropdown for Select Status -->
                                        <?php
                                        $statusOptions = [
                                            '' => 'Select Status',
                                            '0' => 'Breaking Status',
                                            '1' => 'Sequence',
                                            '2' => 'Podcast Status',
                                        ];
                                        $currentStatus = $statusOptions[$data['status']] ?? 'Select Status';
                                        ?>
                                        <div class="btn-group">
                                            <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" id="statusButton">
                                                {{ $currentStatus }} <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach ($statusOptions as $value => $label)
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            onclick="document.getElementById('statusInput').value='{{ $value }}'; document.getElementById('statusButton').textContent='{{ $label }}';">
                                                            {{ $label }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <input type="hidden" name="status" id="statusInput"
                                            value="{{ $data['status'] }}" />

                                        <!-- Dropdown for Select Author -->
                                        <?php
                                        $selectedAuthor = App\Models\User::find($_GET['author'] ?? '')->name ?? 'Select Author';
                                        $authors = App\Models\User::whereNot('id', 6)->get();
                                        ?>
                                        <div class="btn-group">
                                            <button class="btn btn-outline-primary dropdown-toggle"
                                                data-bs-toggle="dropdown" type="button" id="authorButton">
                                                {{ $selectedAuthor }} <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dp-menu">
                                                <li>
                                                    <button type="button"
                                                        onclick="document.getElementById('authorInput').value=''; document.getElementById('authorButton').textContent='Select Author';"
                                                        class="dropdown-item">
                                                        Select Author
                                                    </button>
                                                </li>
                                                @foreach ($authors as $author)
                                                    <li>
                                                        <button type="button"
                                                            onclick="document.getElementById('authorInput').value='{{ $author->id }}'; document.getElementById('authorButton').textContent='{{ $author->name }}';"
                                                            class="dropdown-item">
                                                            {{ $author->name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <input type="hidden" name="author" id="authorInput"
                                            value="{{ $_GET['author'] ?? '' }}" />

                                        <!-- Dropdown for Select Category -->
                                        <?php
                                        $selectedCategory = App\Models\Category::find($_GET['category'] ?? '')->name ?? 'Select Category';
                                        $categories = App\Models\Category::all();
                                        ?>
                                        <div class="btn-group">
                                            <button class="btn btn-outline-primary dropdown-toggle"
                                                data-bs-toggle="dropdown" type="button" id="categoryButton">
                                                {{ $selectedCategory }} <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dp-menu">
                                                <li>
                                                    <button type="button"
                                                        onclick="document.getElementById('categoryInput').value=''; document.getElementById('categoryButton').textContent='Select Category';"
                                                        class="dropdown-item">
                                                        Select Category
                                                    </button>
                                                </li>
                                                @foreach ($categories as $category)
                                                    <li>
                                                        <button type="button"
                                                            onclick="document.getElementById('categoryInput').value='{{ $category->id }}'; document.getElementById('categoryButton').textContent='{{ $category->name }}';"
                                                            class="dropdown-item">
                                                            {{ $category->name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <input type="hidden" name="category" id="categoryInput"
                                            value="{{ $_GET['category'] ?? '' }}" />

                                        <!-- Search Button -->
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </form>
                                    <!---<div id="dataTableExample_filter" class="dataTables_filter"><label><input type="search"
                                              class="form-control" placeholder="Search"
                                              aria-controls="dataTableExample"></label></div>--->
                                </div>
                                <!--<div class="group">
                                            <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                                                <g>
                                                    <path
                                                        d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                                                    </path>
                                                </g>
                                            </svg>

                                            <input id="query" class="input" type="search" placeholder="Search..."
                                                name="searchbar" />
                                        </div>-->
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body table-responsive py-2 px-0">
                                <table class="table article-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>ID</th>
                                            <th></th>
                                            <th>Post Name</th>
                                            <th>Author Name</th>
                                            <th>Status</th>
                                            <th>Publish Date</th>
                                            <th>Website Count</th>
                                            <th>Sequence</th>
                                            <th>IS LIVE</th>
                                            <th>Breaking Status</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if (isset($_GET['status'])) {
                                            $filteredstatus = $_GET['status'];
                                        } else {
                                            $filteredstatus = '';
                                        }
                                        // echo "?status=". $filteredstatus;
                                        ?>

                                        @if (count($data['blogs']) > 0)
                                            @foreach ($data['blogs'] as $blog)
                                                <?php
                                                $author = App\Models\User::where('id', $blog->author)->first();
                                                $cat = App\Models\Category::where('id', $blog->categories_ids)->first();
                                                ?>
                                                <tr>
                                                    <td class="text-nowrap">
                                                        @if (!empty($cat->site_url))
                                                            <a class="act_btn"
                                                                href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"
                                                                target="_blank"><i class="fa-solid fa-eye"></i>
                                                            </a>
                                                        @endif
                                                        <a class="act_btn"
                                                            href="{{ asset('posts/edit') }}/{{ $blog->id }}?from={{ request()->segment(2) }}&status={{ $filteredstatus }}&t={{ time() }}">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ $blog->id }}</td>
                                                    <td>
                                                        @if ($blog->link != '')
                                                            <i class="fa fa-video" aria-hidden="true"></i>
                                                        @endif
                                                    </td>
                                                    <td style="white-space: pre-wrap; word-wrap: break-word; width: 290px;">{{ $blog->name }}</td>
                                                    <td>{{ isset($author->name) ? $author->name : '' }}</td>
                                                    <td>
                                                        <span
                                                            class="status {{ $blog->status == App\Models\Blog::STATUS_PUBLISHED ? 'status-active' : 'status-unknown' }}">
                                                            {{ $blog->status == App\Models\Blog::STATUS_PUBLISHED ? 'Published' : 'Unknown' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $blog->created_at }}</td>
                                                    
                                                    <td>{{ $blog->WebHitCount }}</td>
                                                    <td>{{ $blog->sequence_id }}</td>
                                                    <td>{{ $blog->isLive }}</td>
                                                    <td>{{ $blog->breaking_status }}</td>
                                                    <td> 
                                                        <a class="act_btn"
                                                            href="{{ asset('posts/delete/') }}/{{ $blog->id }}?from={{ request()->segment(2) }}&status={{ $blog->status }}"
                                                            onclick="openDeleteModal(this); return false;">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </td>
                                                    {{-- <td class="text-nowrap">
                                                        @if (!empty($cat->site_url))
                                                            <a class="act_btn"
                                                                href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"
                                                                target="_blank"><i class="fa-solid fa-eye"></i>
                                                            </a>
                                                        @endif
                                                        <a class="act_btn"
                                                            href="{{ asset('posts/edit') }}/{{ $blog->id }}?from={{ request()->segment(2) }}&status={{ $filteredstatus }}&t={{ time() }}">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <a class="act_btn"
                                                            href="{{ asset('posts/delete/') }}/{{ $blog->id }}?from={{ request()->segment(2) }}&status={{ $blog->status }}"
                                                            onclick="openDeleteModal(this); return false;">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">No Data Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="row pagination-block px-5">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" id="dataTableExample_info" role="status"
                                        aria-live="polite">
                                        @if ($data['blogs']->count() > 0)
                                            Showing {{ $data['blogs']->firstItem() }} to {{ $data['blogs']->lastItem() }}
                                            of {{ $data['blogs']->total() }} entries
                                        @else
                                            Showing 0 entries
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dataTableExample_paginate">
                                        <ul class="pagination">
                                            {{ $data['blogs']->links() }}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
    @push('custom-scripts')
    @endpush
@endsection
