
@extends('layouts/contentLayoutMaster')
@section('title', 'Worksheet#' . request()->id . ' Management' )

@section('vendor-style')
       
@endsection
@section('page-style')
        {{-- Page css files --}}
        <link rel="stylesheet" href="{{ asset(mix('css/pages/dashboard-ecommerce.css')) }}">
@endsection

@section('content')
  <br>
  <div class="row">
    <div class="col-3">
      
      @include('app.partials.action_bar1')

    </div>
    <div class="col-9">

     <div class="card">
       <div class="card-header">
         <div class="card-title">Manage Failures</div>
       </div>
        <div class="card-body">
          
          <form action="{{ route('admin.manage.failure_update', request()->id) }}" method="POST">
            @csrf
            @method('PUT')
          
            <div class="failures-container row" style="width: 100%; margin: 0">
              @php
                $i=0;
              @endphp
              @foreach ($failures as $f)
              
                <div class="failure row" style="width: 100%; margin: 0">
                  <div class="form-group col-12">
                    <div class="controls">
                      <label>Failure Reported By the Client</label>
                      <input type="text" name="failure[{{ $i }}][failure_title]"  placeholder="Defected Steering Pump" class="form-control failure-input" value="{{ $f->failure_title }}" required>
                    </div>
                  </div>
                  
                </div>
                @php
                  $i += 1;
                @endphp
              @endforeach
              
              
            </div>
    
            <br>
            <div class="col-lg-12 col-md-12 mb-1" style="right: 0; position: relative">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-outline-success square mr-1 mb-1 add-failure" style="width: 120px">Add</button>
                <button type="button" class="btn btn-outline-danger square mr-1 mb-1 remove-failure" style="width: 120px">Remove</button>
              
              </div>
            </div>

            <button type="submit" class="btn btn-primary float-right">Update Worksheet</button>

          </form>

        </div>
      </div>

    </div>
  </div>

@endsection
@section('vendor-script')
{{-- vendor js files --}}
        
        
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/app-chat.js')) }}"></script>

    <script>

        $('.add-failure').on('click', () => {
        var failure = `
              <div class="failure row" style="width: 100%; margin: 0">
                <div class="form-group col-12">
                  <div class="controls ">
                    <label>Failure ` + ($('.failure').length + 1) + `</label>
                    <input type="text" name="failure[` + $('.failure').length + `][failure_title]"  placeholder="Failure ` + ($('.failure').length + 1) + `" class="form-control failure-input" required>
                  </div>
                </div>
              </div>`;
        $('.failures-container').append(failure);
        });
        $('.remove-failure').on('click', () => {
              
          if($('.failure').length > 1){
            $('.failure').eq($('.failure').length - 1).remove();
          }
        });


    </script>
    
@endsection
