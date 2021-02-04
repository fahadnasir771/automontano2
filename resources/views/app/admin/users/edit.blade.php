@extends('layouts/contentLayoutMaster')

@section('title', 'Update User')

@section('vendor-style')
        {{-- Page Css files --}}
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection

@section('page-style')
        {{-- Page Css files --}}
        <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/validation/form-validation.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/pages/app-user.css')) }}">

@endsection

@section('content')
<!-- users edit start -->
<section class="users-edit">
  <div class="card">
    <div class="card-content">
      <div class="card-body">
        <ul class="nav nav-tabs mb-3" role="tablist">
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account"
              aria-controls="account" role="tab" aria-selected="true">
              <i class="feather icon-user mr-25"></i><span class="d-none d-sm-block">Account</span>
            </a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
            <!-- users edit account form start -->
            <form novalidate method="POST" action="{{ route('admin.users.update', $data->id) }}">
              @csrf
              <input type="hidden" name="_method" value="PUT">
              <div class="row">

                  <div class="form-group col-6">
                    <div class="controls">
                      <label>Name</label>
                      <input type="text" class="form-control" placeholder="Name" name="name"  value="{{ $data->name }}" required
                        data-validation-required-message="This name field is required">
                    </div>
                  </div>
                  <div class="form-group col-6">
                    <div class="controls">
                      <label>E-mail</label>
                      <input type="text" class="form-control" name="email" placeholder="E-mail" value="{{ $data->email }}" required
                        data-validation-required-message="This e-mail field is required">
                    </div>
                  </div>
                  <div class="form-group col-6">
                    <label>Role</label>
                    <select class="form-control" name="role">
                      <option value=""  hidden selected>Select Role</option>
                      <option value="2" @if($data->role == 2) selected @endif>Acceptor</option>
                      <option value="3" @if($data->role == 3) selected @endif>Operator</option>
                      <option value="4" @if($data->role == 4) selected @endif>Customer</option>
                    </select>
                  </div>


                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                  <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Update User</button>
                </div>
              </div>
            </form>
            <!-- users edit account form ends -->
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
<!-- users edit ends -->
@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jqBootstrapValidation.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
@endsection

@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(mix('js/scripts/pages/app-user.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/navs/navs.js')) }}"></script>
@endsection

