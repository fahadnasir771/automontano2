
@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
        <!-- vendor css files -->
        
@endsection
@section('page-style')
        <!-- Page css files -->
  @endsection

  @section('content')
    Customer | Invoice look will be added later (Under Development)
    @foreach ($worksheets as $ws)
        @php
          
            if($ws->user_customer->id != Auth::user()->id){
              continue;
            }
          
            
        @endphp
        <div>
          <h1>Worksheet#{{ $ws->id }}</h1>
          <a class="btn btn-primary" href="submit?wid={{ $ws->id }}">Submit</a>
          
        </div>
        <br>
        <br>
        
    @endforeach
  @endsection

@section('vendor-script')
        <!-- vendor files -->
@endsection
@section('page-script')
        <!-- Page js files -->
@endsection
