@extends('layouts/contentLayoutMaster')

@section('title', 'Update Task')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
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
                            <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab"
                                href="#account" aria-controls="account" role="tab" aria-selected="true">
                                <i class="feather icon-user mr-25"></i><span class="d-none d-sm-block">Task</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                            <!-- users edit account form start -->
                            @if (count($operators) > 0)
                                <form novalidate method="POST" action="{{ route('admin.task.update', $data->id) }}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <div class="controls">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Title" name="title"
                                                    value="{{ $data->title }}" required
                                                    data-validation-required-message="This name field is required">
                                            </div>
                                        </div>

                                        <fieldset class="controls form-group col-6  position-relative">
                                            <label>Min. Completion Time</label>
                                            <input type="number" class="form-control"  placeholder="Min. Completion Time"
                                                name="min_time" value="{{ $data->min_time }}" required
                                                data-validation-required-message="This Minimun completion Time field is required">
                                            <div class="form-control-position" style="top: 20px; right: 20px">
                                                <b>min.</b>
                                            </div>
                                        </fieldset>

                                        <fieldset class="controls form-group col-6  position-relative">
                                          <label>Max. Completion Time</label>
                                          <input type="number" class="form-control"  placeholder="Max. Completion Time"
                                              name="max_time" value="{{ $data->max_time }}" required
                                              data-validation-required-message="This maximun completion time field is required">
                                          <div class="form-control-position" style="top: 20px; right: 20px">
                                              <b>max.</b>
                                          </div>
                                      </fieldset>
                                      {{-- <select class="select2 form-control" name="operators[]" multiple>
                                        @for($i=0; $i<count($operators); $i++)
                                          @isset($data->operators[$i])
                                            <option value="{{ $operators[$i]->id }}" @if($data->operators[$i]->id == $operators[$i]->id) selected  @endif>{{ $operators[$i]->name }}</option>
                                          @else
                                          <option value="{{ $operators[$i]->id }}" >{{ $operators[$i]->name }}</option>
                                          @endisset()
                                        @endfor
                                      </select> --}}
                                        <div class="form-group col-6">
                                            <label>Operators</label>
                                            <select class="select2 form-control" name="operators[]" multiple>
                                              @for($i=0; $i<count($operators); $i++)
                                                @isset($data->operators[$i])
                                                  <option value="{{ $operators[$i]->id }}" @if($data->operators[$i]->id == $operators[$i]->id) selected  @endif>{{ $operators[$i]->name }}</option>
                                                @else
                                                <option value="{{ $operators[$i]->id }}" >{{ $operators[$i]->name }}</option>
                                                @endisset
                                              @endfor
                                            </select>
                                        </div>


                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                            <button type="submit"
                                                class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Update Task</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <h4 class="alert-heading">Attention</h4>
                                    <p class="mb-0">Please add operator first.</p>
                                </div>
                            @endif
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
    <script src="{{ asset(mix('js/scripts/forms/select/form-select2.js')) }}"></script>
@endsection
