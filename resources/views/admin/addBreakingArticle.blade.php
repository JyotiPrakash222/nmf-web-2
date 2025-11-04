
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
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ asset('/home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ asset('/posts') }}">Article</a></li>
                            <li class="breadcrumb-item active">Add Article</li>
                        </ol>
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
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">Add Article</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-group" method="post" action="{{ asset('posts/breakingArticleAdd') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row ">
                                        <div class="checkbox-wrapper-46 mb-4">
                                            <input type="checkbox" id="cbx-45" name="send_Notification"
                                                class="inp-cbx" onchange="toggleNotificationTitle()" />
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
                                            <input type="text" class="at-title" name="notification_title" id="notification_title" placeholder="">
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
                                        <div class="input-field col-md-12">
                                            <input placeholder="" autocomplete="off" type="text" name="name" id="name" 
                                                oninput="clearError('name-error')" />
                                            <label for="name">Post Title</label>
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
                                   

                                 

                                  {{-- Action button --}}
                                  <div class="button-container row">
                                      <button class="--btn btn-publish" name="publish" value="pub">Add</button>
                                  </div>
                              </div>
                            </div>
                        </div>
                    <!-- /.card-body -->
                    </form>

                </div>
                <!-- /.card -->
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
    function toggleNotificationTitle() {
                const checkbox = document.getElementById('cbx-45');
                const titleWrapper = document.getElementById('notificationTitleWrapper');
                titleWrapper.style.display = checkbox.checked ? 'block' : 'none';
            }

</script>

@endsection