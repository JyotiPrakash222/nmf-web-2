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
background-color: #0381cf;
margin-left: 1.5px
}
.btn_edit:hover{
  background-color: #0577bd;
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
@media (max-width: 500px) {
  .btn_edit{
margin-left: 5px
}

.btn_delete{
margin-left: 5px
}
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">Home</a></li>
              <li class="breadcrumb-item active">Role List</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          <!-- /.row -->
          <div class="row">
              <div class="col-12">
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center px-5 py-">
                          <h3 class="card-title mb-0 fs-5">Role LIST</h3>

                          <div class="card-tool ">
                              <div class="input-group input-group-sm float-right  ">

                                  <a href="{{ asset('users') }}/add" class="--btn" style="background: #28364f">
                                      ADD USER
                                  </a>

                              </div>
                          </div>
                      </div>
                      <div class="row pt-4 px-5">
                          <div class="col-sm-12 col-md-6">
                              <div class="dataTables_length" id="dataTableExample_length">
                                  <label class="d-inline-flex gap-1 align-items-center">Show <select
                                          name="dataTableExample_length" aria-controls="dataTableExample"
                                          class="form-select select-down "
                                          onchange="window.location.href = '?perPage=' + this.value;">
                                        <option value="20" <?php if($perPage == "20") { echo "selected"; } ?>>20</option>
                                        <option value="30" <?php if($perPage == "30") { echo "selected"; } ?>>30</option>
                                      </select> entries</label>
                              </div>
                          </div>
                          {{-- <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                              <form class="form-wrapper btn-group d-flex gap-2 ">
                                  <div class="group">

                                      <input id="query" class="input" type="text" placeholder="Enter Title"
                                          name="searchbar" />
                                  </div>
                                 
                                  <button class="btn btn-outline-primary" type="submit"><i
                                          class="fa-solid fa-magnifying-glass"></i></button>
                              </form>
                     
                          </div> --}}

                          <!-- /.card-header -->
                          <div class="card-body table-responsive px-1 py-2 mt-1">
                              <table class="table user-table text-nowrap">

                                  <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>UserName</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  <?php $i=1; ?>
                                  @foreach ($users as $user)
                                  @if($user->role!= 5)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            <a class="action_btn btn_edit" href="{{ asset('users/edit') }}/{{$user->id}}">Edit</a>
                                            <a class="action_btn btn_delete" href="{{ asset('users/delete') }}/{{$user->id}}">Delete</a>
                                        </td>
                                  </tr>
                                  <?php $i++; ?>
                                  @endif
                                  @endforeach
                                </tbody>
                              </table>

                          </div>
                          <div class="row pagination-block">
                              <div class="col-sm-12 col-md-5">
                                  <div class="dataTables_info" id="dataTableExample_info" role="status" aria-live="polite">
                                        @if ($users->count() > 0)
                                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                                        @else
                                            Showing 0 entries
                                        @endif
                                  </div>
                              </div>
                              <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                                  <div class="dataTables_paginate paging_simple_numbers"
                                      id="dataTableExample_paginate">
                                      <ul class="pagination">
                                      {{ $users->links() }}
                                      </ul>
                                  </div>
                              </div>
                          </div>
                          <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                  </div>
              </div>

              <!-- /.row -->
          </div><!-- /.container-fluid -->
  </section>
  </div>
  @endsection