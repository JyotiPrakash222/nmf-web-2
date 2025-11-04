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
        <section class="content ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header " style="padding-block: 20px;">
                                <h3 class="card-title mb-0 fs-5">ADD User</h3>
                            </div>
                            <div class="uploads-container row px-1">
                                <form method="post" action="{{ asset('alluserslist/add') }}">
                                    @csrf
                                    <div class="form-group row ">
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="name"
                                                value="{{ old('name') }}" id="name"
                                                oninput="clearError('name-error')">
                                            <label for="name">Name <span class="text-danger">*</span></label>
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
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="text" name="url_name"
                                                value="{{ old('url_name') }}" id="url_name"
                                                oninput="clearError('url_name-error')">
                                            <label for="name">Url Name(Name In English) <span class="text-danger">*</span></label>
                                            @error('url_name')
                                                <div class="input-group-append" id="url_name-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('url_name') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class=" input-field col-md-6">
                                            <select class="js-example-basic-single form-select" data-width="100%"
                                                name="role" oninput="clearError('role-error')">
                                                <option value="0">Select User Type <span class="text-danger">*</span>
                                                </option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role') == $role->id ? 'selected' : '' }}>
                                                        {{ $role->role_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="input-group-append" id="role-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('role') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class=" input-field col-md-6">
                                            <input placeholder="" autocomplete="off" type="email" name="email"
                                                value="{{ old('email') }}" id="email"
                                                oninput="clearError('email-error')">
                                            <label for="name">Email <span class="text-danger">*</span></label>
                                            @error('email')
                                                <div class="input-group-append" id="email-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('email') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <div class=" input-field mb-4">
                                            <label for="name">Description</label>
                                            <textarea class="txtar" name="description" id="description" rows="10" class="form-control">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('description') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class=" input-field col-md-4">
                                            <input placeholder="" autocomplete="off" type="password" name="password"
                                                id="password" oninput="clearError('password-error')">
                                            <label for="name">Password <span class="text-danger">*</span></label>
                                            @error('password')
                                                <div class="input-group-append" id="password-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('password') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class=" input-field col-md-4">
                                            <input placeholder="" autocomplete="off" type="password"
                                                id="password_confirmation" name="password_confirmation"
                                                oninput="clearError('password_confirmation-error')">
                                            <label for="name">Confirm Password</label>
                                            @error('password_confirmation')
                                                <div class="input-group-append" id="password-error">
                                                    <div class="input-group-text">
                                                        <span class="me-1"><i class="fa-solid fa-circle-exclamation"></i>
                                                            {{ $errors->first('password_confirmation') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <button class="--btn btn-submit mt-4 " type="submit">Submit</button>
                                </form>
                            </div>
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
@endsection
