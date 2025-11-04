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
.action_btn{
  padding-block: 3.5px;
    padding-inline: 8.5px;
    border-radius: 4px;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    
}
.btn_view{
background-color: #0381cf;
&:hover{
  background-color: #0577bd;
  color: #fff;
}

}
.btn_edit{
background-color: #00c1d9;
margin-left: 1.5px
}
.btn_edit:hover{
  background-color: #04abc1;
  color: #fff;
}
.btn_delete{
background-color: #ff0000;
margin-left: 1.5px
}
.btn_delete:hover{
  background-color: #ca0808;
  color: #fff;
}
#loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4); /* <-- nice semi-transparent black */
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

/* Spinner */
.loader {
    border: 6px solid #f3f3f3;
    border-top: 6px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

/* Spinner animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
@media (max-width: 500px) {
  .btn_edit{
margin-left: 5px
}

.btn_delete{
margin-left: 5px
}
}
.status-gray {
    background-color: #e0e0e0;  Light gray
    color: #333;
    border-color: #aaa;
}

</style>
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center px-5 py-2.5">
                                <h3 class="mb-0 fs-5">Breaking Articles</h3>

                                <div class="card-tool ">
                                    <div class="input-group input-group-sm float-right  ">

                                        <a href="{{ asset('/posts') }}/addBreakingArticle" class="--btn" style="background: #28364f">
                                            Add Breaking Article
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 px-5">
                                <div class="col-sm-12 col-md-6">
                                    
                                </div>
                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                    <form class="form-wrapper btn-group d-flex gap-2 ">
                                        
                                            <div class="group">
                                                <input id="query" class="input form-control" type="text" 
                                                       placeholder="Enter Title" name="title" />
                                            </div>
                                            <!-- Date Picker Input -->
                                            <div class="group">
                                                <input
                                                    id="date"
                                                    class="input form-control"
                                                    type="date"
                                                    name="date"
                                                    value="{{ $_GET['date'] ?? '' }}"
                                                    placeholder="Select Date"
                                                />
                                            </div>

                                        
                                           

                                          <!-- Dropdown for Select Author -->

                                           <?php
                                            $selectedAuthor = App\Models\User::find($_GET['author'] ?? '')->name ?? 'Select Author';
                                            $authors = App\Models\User::whereNot('id', 6)->get();
                                            ?>
                                            <div class="btn-group">
                                                <button
                                                    class="btn btn-outline-primary dropdown-toggle"
                                                    data-bs-toggle="dropdown"
                                                    type="button"
                                                    id="authorButton"
                                                >
                                                    {{ $selectedAuthor }} <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dp-menu">
                                                    <li>
                                                        <button
                                                            type="button"
                                                            onclick="document.getElementById('authorInput').value=''; document.getElementById('authorButton').textContent='Select Author';"
                                                            class="dropdown-item"
                                                        >
                                                            Select Author
                                                        </button>
                                                    </li>
                                                    @foreach($authors as $author)
                                                        <li>
                                                            <button
                                                                type="button"
                                                                onclick="document.getElementById('authorInput').value='{{ $author->id }}'; document.getElementById('authorButton').textContent='{{ $author->name }}';"
                                                                class="dropdown-item"
                                                            >
                                                                {{ $author->name }}
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <input type="hidden" name="author" id="authorInput" value="{{ $_GET['author'] ?? '' }}" />

                                          <!-- Dropdown for Select Category -->
                                           
                                            <?php
                                            $selectedCategory = App\Models\Category::find($_GET['category'] ?? '')->name ?? 'Select Category';
                                            $categories = App\Models\Category::all();
                                            ?>
                                            <div class="btn-group">
                                                <button
                                                    class="btn btn-outline-primary dropdown-toggle"
                                                    data-bs-toggle="dropdown"
                                                    type="button"
                                                    id="categoryButton"
                                                >
                                                    {{ $selectedCategory }} <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dp-menu">
                                                    <li>
                                                        <button 
                                                            type="button"
                                                            onclick="document.getElementById('categoryInput').value=''; document.getElementById('categoryButton').textContent='Select Category';"
                                                            class="dropdown-item"
                                                        >
                                                            Select Category
                                                        </button>
                                                    </li>
                                                    @foreach($categories as $category)
                                                    <li>
                                                        <button
                                                            type="button"
                                                            onclick="document.getElementById('categoryInput').value='{{ $category->id }}'; document.getElementById('categoryButton').textContent='{{ $category->name }}';"
                                                            class="dropdown-item"
                                                        >
                                                            {{ $category->name }}
                                                        </button>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <input type="hidden" name="category" id="categoryInput" value="{{ $_GET['category'] ?? '' }}" />
                                       
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
                                <div class="card-body table-responsive py-2">
                                    <div id="loader-overlay">
                                        <div class="loader"></div>
                                    </div>
                                    <table class="table article-table text-nowrap">

                                        <thead>
                                            <tr>
                                                <th class="text-center">Action</th>
                                                <th>ID</th>
                                                <th></th>
                                                <th>Post Name</th>
                                                <th>Author Name</th>
                                                <th>Status</th>
                                                <th>Publish Date</th>
                                                <th>Breaking Status</th>
                                                {{-- <th>Sequence</th>
                                                <th>App Count</th>
                                                <th>Website Count</th> --}}
                                                <th>Enable</th>
                                                <th class="text-center">Delete</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                                if (isset($_GET['status'])) {
                                                    $filteredstatus=$_GET['status'];
                                                } else {
                                                $filteredstatus='';
                                                }
                                                // echo "?status=". $filteredstatus; 
                                            ?>

                                            @if(count($blogs) > 0)
                                                @php $isFirst = true; @endphp
                                                @foreach($blogs as $blog)
                                                <?php 
                                                $author = App\Models\User::where('id',$blog->author)->first();
                                                $cat = App\Models\Category::where('id',$blog->categories_ids)->first(); 
                                            ?>
                                            <tr>
                                                <td class="mx-auto" >
                                                  @if(isset($blog->site_url) && isset($blog->categories_ids) && $blog->categories_ids > 0)
                                                    <a class="act_btn" href="{{ asset('/') }}{{  isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>" target="_blank"><i class="fa-solid fa-eye"></i></a>
                                                    @endif
                                                    <a class="act_btn"
                                                            href="{{ asset('posts/edit') }}/{{ $blog->id }}?from={{ request()->segment(2) }}&status={{ $filteredstatus }}">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                   
                                                </td>
                                                <td>{{ $blog->id }}</td>
                                                <td>
                                                @if($blog->link != '')
                                                <i class="fa fa-video" aria-hidden="true"></i>
                                                @endif
                                                </td>
                                                <td style="white-space: pre-wrap; word-wrap: break-word; width: 290px;">{{ $blog->name }}</td>
                                                <td >{{ isset($author->name) ? $author->name : '' }}</td>
                                                <td>
                                                    @php
                                                        $statusText = $blog->status == 0 ? 'Draft' : 'Published';
                                                        $statusClass = $isFirst ? 'status-active' : 'status-gray';
                                                    @endphp
                                                    <span class="status {{ $statusClass }}">{{ $statusText }}</span>
                                                </td>
                                                <td >{{ $blog->created_at }}</td>  
                                                <td >{{ $blog->breaking_status }}</td>                     
                                                {{-- <td >{{ $blog->sequence_id }}</td>
                                                <td >{{ $blog->AppHitCount }}</td>
                                                <td >{{ $blog->WebHitCount }}</td>      --}}
                                                
                                                <td class="mx-auto">
                                                    <input class="bg-primary form-check-input ms-4" type="checkbox"
                                                        name="breaking_status_{{ $blog->id }}"
                                                        {{ $blog->breaking_status == 1 ? 'checked' : '' }}
                                                        onchange="updateBreakingStatus({{ $blog->id }}, this.checked)">
                                                </td>
                                               <td> <a class="act_btn"
                                                        href="{{ asset('posts/delete/') }}/{{ $blog->id }}?from={{ request()->segment(2) }}&status={{ $blog->status }}"
                                                        onclick="openDeleteModal(this); return false;">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                                
                                            </tr>
                                            @php $isFirst = false; @endphp
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="4">No Data Found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                </div>

                               
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>

                    <!-- /.row -->
                </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    @push('custom-scripts')

    @endpush
@endsection
<script>
    function updateBreakingStatus(blogId, isChecked) {
        const status = isChecked ? 1 : 0;
    
        // Show loader overlay
        document.getElementById('loader-overlay').style.display = 'flex';
    
        fetch('/posts/update-breaking-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                blog_id: blogId,
                breaking_status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (status === 0) {
                    setTimeout(() => {
                        location.reload();
                    }, 300);
                } else {
                    // Hide loader if not reloading
                    document.getElementById('loader-overlay').style.display = 'none';
                }
            } else {
                document.getElementById('loader-overlay').style.display = 'none';
            }
        })
        .catch(error => {
            document.getElementById('loader-overlay').style.display = 'none';
        });
    }
    </script>
    
    
    
    