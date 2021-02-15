
@extends('layouts/contentLayoutMaster')
@php
    $title = '';
    if(isset($vehicle_search)){
      $title = 'Vehicle Search';
    }elseif(isset($license_plate)){
      $title = 'Data of Vehicle with License Plate#' . $license_plate;
    }else{
      $title = 'All Worksheets';
    }
@endphp
@section('title', $title )

@section('vendor-style')
       
@endsection
@section('page-style')
        {{-- Page css files --}}
        <link rel="stylesheet" href="{{ asset(mix('css/pages/dashboard-ecommerce.css')) }}">
@endsection

@section('content')

@if (!isset($vehicle_search))

  @isset($license_plate)
  <div class="search-bar">
    <form>
        <fieldset class="form-group position-relative has-icon-left">
            <input type="text" class="form-control round" autofocus id="searchbar" name="plate" value="" placeholder="Enter License Plate and then press enter">
            <div class="form-control-position">
                <i class="feather icon-search px-1"></i>
            </div>
        </fieldset>
    </form>
  </div>
  @endisset
    
<section>
  @if (count($worksheets) == 0)
  <div style="position: absolute; left: 50%;top:60%;transform: translate(-50%,-50%)">
    <img style=""  src="{{ asset('images/custom/no-results.png') }}" alt="">
  </div>
    
  @endif
  <div class="row match-height">
    @foreach ($worksheets as $worksheet)
      <div class="col-xl-4 col-md-6 col-sm-12">

        <style>
          .worksheet:hover>.overlay{
            
            opacity: 1;
          }
          .overlay {
            background:rgba(255,255,255,0.8);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            
            opacity: 0;
            border-radius: 0.5rem;
            transition: opacity 0.2s linear;
            padding: 10px;
            text-align: center;
          }
          .overlay-btn {
            margin-bottom: 5px;
            display: block
          }
          
        </style>

        <div class="card worksheet" style="cursor: pointer">
          <div class="card-content">
            <div class="card-body">
                @php
                  $completed = 0;
                  $percentage = 0;
                  for ($i=0; $i < count($worksheet->jobs); $i++) { 
                    if($worksheet->jobs[$i]->completed == 1){
                      $completed += 1;
                    }
                  }
                  $percentage = (count($worksheet->jobs) == 0) ? 0 : (($completed/count($worksheet->jobs)) * 100);
                @endphp
                <div>
                  <h4 class="mt-1 float-left">Worksheet#{{$worksheet->id}}</h4>
                  <div class="pill badge-pill badge-@php

                      if($percentage == 100){
                        echo 'success';
                      }else{
                        if($worksheet->work_actually_started == 1) {
                          echo 'primary';
                        }elseif($worksheet->work_actually_started == 0){ 
                          echo 'warning'; 
                        }
                      }
                     
                  @endphp   text-white float-right" style="top: 13px;position: relative"> 
                    @php

                    if($percentage == 100){
                      echo 'Completed';
                    }else{
                      if($worksheet->work_actually_started == 1) {
                        echo 'Working';
                      }else if($worksheet->work_actually_started == 0){ 
                        echo 'Work Not Started'; 
                      }
                    }
                    
                @endphp
                  </div>
                </div>
                <div style="clear: both">
                  <p class="mt-1 float-left">Customer Accept Status</p>
                  <div class="pill badge-pill badge-@php
                      if($worksheet->customer_accepted == 0){
                        echo 'info';
                      }elseif($worksheet->customer_accepted == 1){
                        echo 'success';
                      }else{
                        echo 'danger';
                      }
                  @endphp text-white float-right" style="top: 13px;position: relative">
                  @php
                    if($worksheet->customer_accepted == 0){
                      echo 'Waiting';
                    }elseif($worksheet->customer_accepted == 1){
                      echo 'Accepted';
                    }else{
                      echo 'Cancelled';
                    }
                @endphp  
                </div>
                  <p style="float: left">Parts Need To Be Delivered (<b>
                    @php
                      $parts = 0;
                      for ($i=0; $i < count($worksheet->spare_parts); $i++) { 
                        if($worksheet->spare_parts[$i]->available == 0){
                          $parts += 1;
                        }
                      }
                      echo $parts;
                    @endphp  
                  
                  </b>)</p>
                  <div style="clear:both">
                    <p style="float: left">Jobs Started (<b>
                      @php
                        $started = 0;
                        for ($i=0; $i < count($worksheet->jobs); $i++) { 
                          if($worksheet->jobs[$i]->started == 1){
                            $started += 1;
                          }
                        }  
                        echo $started . ' of ' . count($worksheet->jobs);
                      @endphp
                      
                      
                    
                    </b>) </p>
                  </div>
                  
                </div>  
               

                

                <div style="clear: both">
                  <div class="d-flex justify-content-between mt-2" >
                    <h6>Work Progress ({{ $percentage }}%)</h6>
                  </div>
                  <div class="progress progress-bar-@php
                      if($percentage < 25){
                        echo 'danger';
                      }elseif ($percentage > 25 && $percentage < 80) {
                        echo 'warning';
                      }elseif ($percentage > 80) {
                        echo 'success';
                      }
                  @endphp box-shadow-6" >
                    <div class="progress-bar" role="progressbar"
                      style="width:{{ $percentage }}%" aria-describedby="example-caption-2"></div>
                  </div>
                </div>

                
              
              <hr class="my-1">
              <div class="d-flex justify-content-between mt-2">
                <div class="float-left">
                  <p class="font-medium-2 mb-0">{{ date("jS M, y", strtotime($worksheet->work_started)) }}</p>
                  <p class="">Work Start Date</p>
                </div>
                <div class="float-right">
                  <p class="font-medium-2 mb-0">
                    {{ 
                      ($worksheet->work_actually_started == 0) ? '-' : date("jS M, y", strtotime($worksheet->work_started . ' + ' . $worksheet->days_required . ' days'))
                    }}
                  </p>
                  <p class="">Delivery Date</p>
                </div>
                
              </div>
              <div style="clear: both">
                <small>Created By: @php
                   
                     echo $user->find($worksheet['user_id'])->name .  ' | ' . $user->find($worksheet->user_id)->title;
                     
                @endphp </small>
              </div>
            </div>
          </div>
          <div class="overlay">
            
              <a href="#" class="btn btn-dark overlay-btn dev">Manage Failures</a>
              <a href="#" class="btn btn-dark overlay-btn dev">Manage Spare Parts</a>
              <a href="#" class="btn btn-dark overlay-btn dev">Manage Jobs</a>
              <a href="#" class="btn btn-dark overlay-btn dev">Manage Vehicle / Customer</a>
              <a href="timeline-by-worksheet?wid={{ $worksheet->id }}" class="btn btn-danger overlay-btn">View Timeline</a>
           
            
          </div>
        </div>
      </div>
    @endforeach

    
  </div>
</section>
  
@else
<div class="search-bar">
  <form>
      <fieldset class="form-group position-relative has-icon-left">
          <input type="text" class="form-control round" autofocus id="searchbar" name="plate" value="" placeholder="Enter License Plate and then press enter">
          <div class="form-control-position">
              <i class="feather icon-search px-1"></i>
          </div>
      </fieldset>
  </form>
</div>
@endif

@endsection
@section('vendor-script')
{{-- vendor js files --}}
        
        
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/app-chat.js')) }}"></script>
    
@endsection
