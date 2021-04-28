@extends('layouts/contentLayoutMaster')

@section('title', 'Add Task')

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
                                <i class="feather icon-user mr-25"></i><span class="d-none d-sm-block">Create Task</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                            <!-- users edit account form start -->
                            @if (count($operators) > 0)
                                <form novalidate method="POST" id="form" action="{{ route('admin.task.store') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <div class="controls">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Title" name="title"
                                                    value="{{ old('title') }}" required
                                                    data-validation-required-message="This name field is required">
                                            </div>
                                        </div>

                                        <fieldset class="controls form-group col-6  position-relative">
                                            <label>Min. Completion Time</label>
                                            <input type="number" class="form-control"  placeholder="Min. Completion Time"
                                                name="min_time" value="{{ old('min_time') }}" required
                                                data-validation-required-message="This Minimun completion Time field is required">
                                            <div class="form-control-position" style="top: 20px; right: 20px">
                                                <b>min.</b>
                                            </div>
                                        </fieldset>

                                        <fieldset class="controls form-group col-6  position-relative">
                                          <label>Max. Completion Time</label>
                                          <input type="number" class="form-control"  placeholder="Max. Completion Time"
                                              name="max_time" value="{{ old('max_time') }}" required
                                              data-validation-required-message="This maximun completion time field is required">
                                          <div class="form-control-position" style="top: 20px; right: 20px">
                                              <b>min.</b>
                                          </div>
                                      </fieldset>

                                        {{-- <div class="form-group col-6">
                                            <div class="controls">
                                                <label>Max. Completion Time</label>
                                                <input type="number" class="form-control" placeholder="Max. Completion Time"
                                                    name="max_time" value="{{ old('max_time') }}" required
                                                    data-validation-required-message="This maximum completion time field is required">
                                            </div>
                                        </div> --}}
                                        <div class="form-group col-6">
                                            <label>Operators</label>
                                            <select class="select2 form-control" name="operator" >
                                                @foreach ($operators as $operator)
                                                    <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-6">
                                            <div class="controls">
                                              <label for="">Work Start Date</label>
                                              <input type="datetime-local" id="work-start-date" name="work_start" class="form-control" required>
                                            </div>
                                          </div>


                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                            <button type="button" id="form-submit-btn"
                                                class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Create Task</button>
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

    <script>
        
        $('#form-submit-btn').on('click', function(){
            let string = $('#work-start-date').val();
          
          // Time
          let time2 = string.substr(string.length - 5)
          let hr2 = parseInt(time2.split(':')[0])
          let min2 = parseInt(time2.split(':')[1])

          // Date
          let date2 = string.substring(0,10)
          let year2 = parseInt(date2.split('-')[0])
          let month2 = parseInt(date2.split('-')[1])
          let datenum2 = parseInt(date2.split('-')[2])
          
          let d = new Date

          // Current time
          let hr3 = d.getHours()
          let min3 = d.getMinutes()

          // Current date
          let year3 = d.getFullYear()
          let month3 = d.getMonth() + 1
          let date3 = d.getDate()
          $error = true;
          if(year2 > year3){
              $('#form').submit()
              $error = false;
              return false;
          }
          if(year2 >= year3 && month2 > month3){
              $('#form').submit()
              $error = false;
              return false;
          }


          if(
            year2 >= year3 &&
            month2 >= month3 &&
            datenum2 >= date3
          ){
            if(datenum2 > date3){
                $('#form').submit()
                $error = false;
                return false;
            }
            if(
              hr2 > hr3 ||
              (
                hr2 == hr3 && min2 > min3
              )
            ){
                $('#form').submit()

              $error = false;
            }
          }
          if($error){
            alert('Fail')
            }
        });
        
    </script>
@endsection
