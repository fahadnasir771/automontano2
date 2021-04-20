@extends('layouts/contentLayoutMaster')

@section('title', '')

@section('vendor-style')
  <!-- vendor css files -->
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset('css/pages/timelineBroadcast.css') }}">
@endsection

@section('content')
    {{-- <center ><h4 style="color: red">Per la fase di test, la pagina della timeline non può ricordare i suoi stati della barra se aggiorni la pagina, quindi per ora la timeline può essere gestita solo in tempo reale</h4></center> --}}
    
    <div style="position: relative;height: 50px">
      <form action="" method="GET">
        <div style="float: left">
          <div class="input-group float-left">
            <input type="date" name="selection" class="form-control" value="" style="padding: 10px 50px; border-radius: 120px; ">
          </div>
          
        </div>
        <div style="float: left">
          
            <input type="submit" class="bg-gradient-info" style="padding: 12px 30px; margin-left: 10px;outline: none; border: none; border-radius: 30px" value="GO!">
          
        </div>
      </form>
      <div style="position: absolute; left: 50%; transform: translateX(-50%)">
        <div class="navigate nav-now bg-gradient-danger"><i class="feather icon-aperture"></i></div>
      </div>
    </div>
    <br>
    
    <div class="">
      <div class="card" >
        <div class="card-content" style="">

          
          
          <div class="" style="">
            <ul class="list-group float-left list-group-flush" style="width:250px;" id="operator-info-column">

              <li class="list-group-item info-row" style="text-align: center;" >
                <div style="">
                  <h3 class="float-left mr-2 current-time"></h3>
                  <h6 class="float-left" style="margin-top: 4px"> | {{ isset($_GET['selection']) ? date("j M, y", strtotime($_GET['selection'])) : date("j M, y") }} </h6>
                </div>
              </li>

              {{-- Operators List --}}
              @foreach ($operators as $operator)
                <li class="list-group-item operator-row">
                  <div class="operator-row-info">
                    <img src="{{ Gravatar::src(  $operator->email ) }}" class="rounded-circle operator-image" height="34" width="34" alt="">
                    <h5 class="operator-name">{{ $operator->name }}</h5>
                  </div>
                </li>
              @endforeach
              
              

            </ul>
            
            <ul class="list-group  list-group-flush" style="width:" id="timeline-column">
              <div class="timeline"></div>
              

              <li class="list-group-item timeline-row" >
                
                  <ul id="time">
                    
                    @for ($i = 0; $i < 24; $i++)
                      @for ($j = 0; $j < 60; $j++)
                        <li class="time-step">{{ ($i < 10) ? '0' . $i : $i }}:{{ ($j < 10) ? '0' . $j : $j }}</li>
                      @endfor
                    @endfor
                    
                  </ul>
                
              </li>

              {{-- Timeline operator row --}}
              @php
                $i=0
              @endphp
              @foreach ($operators as $operator)
                {{-- @php
                    $i += 1;
                @endphp --}}
                <li class="list-group-item timeline-operator-row" style="padding: 0;margin: 0">
                  <ul class="list-group list-group-flush progress-set" >
                    <li class="list-group-item progressbar1 progressbar">
                      {{-- {{ dd($operator->late_bars->left) }} --}}
                      @if (isset($operator->late_bars))
                        @php

                          for($i=0; $i < count($operator->late_bars); $i++){
                            echo '<div class="bar late-bar" data-left="' . $operator->late_bars[$i]->left . '" data-color="' .$operator->late_bars[$i]->color . '" data-width="' . $operator->late_bars[$i]->width . '">' . $operator->late_bars[$i]->text . '</div> ';
                          }
                        @endphp
                        
                      @endif
                      
                    </li>

                    <li class="list-group-item progressbar2 progressbar">
                      
                      @if (isset($operator->main_bars))
                        @php

                          for($i=0; $i < count($operator->main_bars); $i++){
                            echo '<div class="bar main-bar" data-left="' . $operator->main_bars[$i]->left . '"  data-color="' . $operator->main_bars[$i]->color . '" data-width="' . $operator->main_bars[$i]->width . '" data-mode="' . $operator->main_bars[$i]->mode . '"  data-jobs="' . $operator->main_bars[$i]->jobs . '" data-jobs-done="' . $operator->main_bars[$i]->jobs_done . '" data-objects-id="' . $operator->main_bars[$i]->id . '" data-id="' . $operator->main_bars[$i]->id . '">' . $operator->main_bars[$i]->text . '</div>';
                          }
                        @endphp
                        
                      @endif

                      {{-- @if ($i==1)
                        <div class="bar main-bar" data-left="28.6041666"  data-color="royalblue" data-width="257.4374994" data-mode="work-not-started"  data-jobs="4" data-jobs-done="0" data-objects-id="0">LED2465</div>
                      @endif --}}
                      
                    </li>

                    <li class="list-group-item progressbar3 progressbar {{ ($i==1) ? 'simplace' : '' }} " id="{{ $operator->id }}">

                      @if (isset($operator->secondary_bars))
                        @php

                          for($i=0; $i < count($operator->secondary_bars); $i++){
                            echo '<div class="bar secondary-bar" data-left="' . $operator->secondary_bars[$i]->left . '" data-color="' . $operator->secondary_bars[$i]->color . '" data-width="' . $operator->secondary_bars[$i]->width . '" data-mode="' . $operator->secondary_bars[$i]->mode . '" data-worksheet-id="' . $operator->secondary_bars[$i]->worksheet_id . '" data-object-index="' . $operator->secondary_bars[$i]->objects_index . '" data-status="' . $operator->secondary_bars[$i]->status . '" data-position="' . $operator->secondary_bars[$i]->position . '" data-mode2="' . $operator->secondary_bars[$i]->mode2 . '" style="display: none" data-id="' . $operator->secondary_bars[$i]->id . '">' . $operator->secondary_bars[$i]->text . '</div>';
                          }
                        @endphp
                        
                      @endif

                      {{-- @if ($i==1)
                      <div class="bar secondary-bar" data-left="0" data-color="#434343" data-width="100.1145830999" data-mode="pre-object" data-worksheet-id="0" data-object-index="1" data-status="0" data-position="first" data-mode2="" style="display: none">Engine</div>

                      <div class="bar secondary-bar" data-left="0" data-color="#434343" data-width="42.9062499" data-mode="pre-object" data-worksheet-id="0" data-object-index="2" data-status="0" data-position="middle" data-mode2="" style="display: none">Oil</div>

                      <div class="bar secondary-bar" data-left="0" data-color="#434343" data-width="71.51041649999" data-mode="pre-object" data-worksheet-id="0" data-object-index="3" data-status="0" data-position="middle" data-mode2="" style="display: none">Align</div>

                      <div class="bar secondary-bar" data-left="0" data-color="#434343" data-width="42.9062499" data-mode="pre-object" data-worksheet-id="0" data-object-index="4" data-status="0" data-position="last" data-mode2="" style="display: none">Tyre</div>
                      @endif --}}
                      

                    </li>
                  </ul>
                </li>
              @endforeach
              
              

            </ul>

          </div>

          
        </div>
      </div>
    </div>
  

@endsection

@section('vendor-script')
  <!-- vendor files -->
@endsection
@section('page-script')
    <script>
      let AJAX_URL = '{{ route("admin.update") }}'
    </script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('js/scripts/timeline-broadcast.js') }}" ></script>
@endsection
