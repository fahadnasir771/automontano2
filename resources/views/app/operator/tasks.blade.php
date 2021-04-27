@extends('layouts/contentLayoutMaster')
@section('title', 'Tasks')

@section('vendor-style')
  <!-- vendor css files -->
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
	<!-- Page css files -->
	{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"> --}}
	<link rel="stylesheet" href="{{ asset(mix('css/plugins/extensions/toastr.css')) }}">
	<style>

	.base-timer {
		position: relative;
		width: 130px;
		height: 130px;
	}

	.base-timer__svg {
		transform: scaleX(-1);
	}

	.base-timer__circle {
		fill: none;
		stroke: none;
	}

	.base-timer__path-elapsed {
		stroke-width: 7px;
		stroke: grey;
	}

	.base-timer__path-remaining {
		stroke-width: 7px;
		/* stroke-linecap: round; */
		transform: rotate(90deg);
		transform-origin: center;
		transition: 1s linear all;
		fill-rule: nonzero;
		stroke: currentColor;
	}

	.base-timer__path-remaining.green {
		color: rgb(65, 184, 131);
	}
	.base-timer__path-remaining.orange {
		color: orange;
	}
	.base-timer__path-remaining.red {
		color: red;
	}

	.timer-overlay.orange{
		background-color:orange  !important;
		transition: background-color 1s,transform 0.5s !important;
		/* transition: transform 0.5s */
	}
	.timer-overlay.red{
		background-color: red  !important;
		transition: background-color 1s,transform 0.5s !important;
		/* transition: transform 0.5s */
	}

	.base-timer__label {
		position: absolute;
		width: 130px;
		height: 130px;
		top: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 36px;
	}
	.timer-overlay {
		position: absolute;
		width: 130px;
		height: 130px;
		top: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		background:  rgb(65, 184, 131);
		border-radius:  50%;
		font-size: 50px;
		color: #fff;
		
		cursor: pointer;
		transition: transform 0.4s;
	}
	.timer-overlay:hover{
		transform: scale(1.1);
		transition: transform 0.4s;
	}
	.timer-overlay.completed {
		background: #1f3969
	}

	</style>
@endsection

@section('content')
<section>
	

	


	{{-- Tasks --}}
	<div class="row">
  @foreach ($tasks as $task)

    
      
      <h3 class="col-12"><b>Task#{{ $task->id }}</b> | {{ date('jS M y', strtotime($task->work_start)) }}
        
      </h3>
      <br><br>

    {{-- jobs --}}
    <div class="col-lg-3 col-md-12">
      <div class="card" style="position: relative">

        <div class="dropdown chart-dropdown">
          <button class="btn btn-sm border-0 px-50 float-right" style="top: 4px; font-size: 20px; position: absolute; right: 0;z-index:1" type="button" id="dropdownItem1"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="feather icon-more-vertical"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownItem1">
            <a class="dropdown-item dev" href="#">Spare Part</a>
            <a class="dropdown-item dev" href="#">Pause Work</a>
            <a class="dropdown-item dev" href="#">Stop Work</a>
          </div>
      </div>

        <div class="card-header pb-0" style="padding-top: 30px" >
          <h4 style="position: absolute;left:50%;transform: translateX(-50%);width : 100%; text-align: center">{{ $task->title }}</h4>
        </div>
        <div class="card-body" style="position: relative" >
          <div id="{{ $task->timer->html_id }}" class="app-timer" style="position: relative">
            
            <div class="base-timer" style="margin: 0 auto">
              <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <g class="base-timer__circle">
                  <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
                  <path
                    {{-- class="base-timer-path-remaining" --}}
                    stroke-dasharray="283"
                    class="base-timer__path-remaining green"
                    
                    d="
                      M 50, 50
                      m -45, 0
                      a 45,45 0 1,0 90,0
                      a 45,45 0 1,0 -90,0
                    "
                  ></path>
                </g>
              </svg>
              {{-- style="background:#193465" --}}
              
              <div 
                class="timer-overlay {{ ($task->completed == 1) ? 'completed feather icon-check' : 'start feather icon-play' }} " 
                
              ></div>	
              

              
              
              <div class="min-time-data" style="display: none">{{ $task->min_time * 60 }}</div> <!-- It should be placed here -->
              <div class="max-time-data" style="display: none">{{ $task->max_time * 60 }}</div>
            </div>

            

            @if ($task->completed != 1)
            <span class="base-timer-label" style="font-size: 15px"><br></span>
            @endif
            
            @if ($task->timer->in_progress != 1 && $task->completed != 1)
            <span class="not-started" style="font-size: 15px"><b>Not Started</b></span>
            @endif
            <br>
            @php
              if ($task->completed == 1) {
                if($task->timer->finished_at >= $task->timer->max_at) {
                  $seconds = $task->timer->finished_at - $task->timer->max_at;
                  $hours = floor($seconds / 3600);
                  $mins = floor($seconds / 60 % 60);
                  $secs = floor($seconds % 60);
                  echo '<span style="color: red;font-size: 15px">Overdue By: ' . sprintf('%02d:%02d:%02d', $hours, $mins, $secs) .' </span>';
                }else{
                  $seconds = $task->timer->max_at - $task->timer->finished_at;
                  $hours = floor($seconds / 3600);
                  $mins = floor($seconds / 60 % 60);
                  $secs = floor($seconds % 60);
                  echo '<span style="color: green;font-size: 15px">Submitted ' . sprintf('%02d:%02d:%02d', $hours, $mins, $secs) .' Early</span>';
                }
              }
            @endphp

            {{-- {{ ($task->completed == 1) ? 'completed feather icon-check' : 'start feather icon-play' }} --}}
          </div>
          <div class="">
            <small>Min. Time: {{ $task->min_time }} mins | Max. Time: {{ $task->max_time }} mins</small>
          </div>
          
          
          
        </div>
      </div>
      
    </div>
  @endforeach

	</div>


	<style>
		.apexcharts-datalabels-group{
			display: none !important
		}
	</style>
	<form action="" method="POST">
		@csrf
		@method('PUT')
	</form>
</section>
{{-- if the is ha value it means that the operator is alsready doing some job --}}
@if (isset($timer_continue) && $timer_continue != false)
	<data
	 style="display: none"> 
		<div id="id-data">#{{ $timer_continue->html_id }}</div>
		<div id="min-time-data">{{  $timer_continue->min_at - $timer_continue->started_at }}</div>
		<div id="max-time-data">{{ $timer_continue->max_at - $timer_continue->started_at }}</div>
		<div id="seek-data">{{ strtotime('now') - $timer_continue->started_at   }}</div>
	</data>
@endif
@endsection

@section('vendor-script')
  <!-- vendor files -->
	<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
	<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>	
@endsection
@section('page-script')
	<!-- Page js files -->
	<script src="{{ asset(mix('js/scripts/forms/select/form-select2.js')) }}"></script>
	<script>
		var VIEW_ONLY = '0';
		// if(parseInt(VIEW_ONLY) == 1){
		// 	$('.timer-overlay').removeClass('start');
		// }
		
	</script>
	<script type="text/javascript" src="{{ asset('js/scripts/countdown.js') }}"></script>
	<script type="text/javascript">
		

		$(ID).on('click', '.start' ,  (el) => {
			start_nutshell(el.target);

			let min = $(el.target).siblings('.min-time-data').text();
			let max = $(el.target).siblings('.max-time-data').text();

			MIN_TIME = init(min,max )['min'];
			TIME_LIMIT = init(min,max )['max'];
			console.log(MIN_TIME + '-' + TIME_LIMIT);
			$.ajax({
				'url': '{{ route("operator.timer") }}',
				'type': 'POST',
				'data': {
					'_token': $("input[name='_token']").val(),
					'started': 1,
					'html_id': html_id,
					'max_at': TIME_LIMIT,
					'min_at': MIN_TIME,
          'is_task': 1
				},
				success: function(data) {
					console.log(data);
					toastr.success('','Best of Luck');
					
				}
			});

			

		});

		$(ID).on('click', '.stop' ,(el) => {
			if(VIEW_ONLY == 1){
				toastr.info('You can\'t control the timeline of the operator!', 'Not Permitted!');
				return false;
			}
			if(timePassed < MIN_TIME){
				toastr.error('You can\'t finish the job in less than the minimum time provided!', 'Minimum Time Restriction!');
				return false;
			}else{
				toastr.info('Pleas wait while we submit your work!', 'Submiting work');
			}
			if('{{ isset($timer_continue->html_id) ?? false }}' == false){
		
			}else{
				html_id = '{{ isset($timer_continue->html_id) ? $timer_continue->html_id : '' }}'
			}
			$.ajax({
				'url': '{{ route("operator.timer") }}',
				'type': 'POST',
				'data': {
					'_token': $("input[name='_token']").val(),
					'finished': 1,
					'html_id': html_id,
          'is_task': 1
					
				},
				success: function(data) {
					console.log(data);
					toastr.success('','Your work is submitted');
					// window.location.reload(false); 
				}
			});

			stopTimer();
			$(el.target).removeClass('stop');
			$(el.target).removeClass('icon-square');
			$(el.target).addClass('icon-check');
			$(el.target).css({
				'transform': 'scale(1)',
				
			});
		});
	</script>
@endsection
