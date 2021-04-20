@extends('layouts/contentLayoutMaster')
@php
	if((Auth::user()->role == 1  || Auth::user()->role == 2)){
		if(isset($operator_id_2)){
			$title = 'Worksheet Data of ' . $operator_id_2->name;
		}else{
			$title = 'All Timeline';
		}
		
	}else{
		$title = 'Worksheet Jobs';
	}
	
@endphp
@section('title', $title)

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

	@if (isset($search_by_operator))
		<form action="">
			<div class="row">
				<div class="col-9">
					
						<div class="form-group">
							
								<select name="oid" class="select2 form-control">
									@foreach ($operators as $op)
										<option value="{{ $op->id }}">{{ $op->name }}</option>
									@endforeach	
								</select>
								
						</div>
						
				</div>
				<div class="col-3">
					<button class="btn btn-primary">Search Worksheet Data</button>
				</div>
			</div>
		</form>
	@endif
	

	{{-- FOR ALL WORKS --}}
	@foreach ($worksheets as $ws)

	@php
		if((Auth::user()->role == 1  || Auth::user()->role == 2)){

			if(isset($operator_id_2)){
				$operator_jobs = false;
				for ($i=0; $i < count($ws->jobs); $i++) { 
					if($ws->jobs[$i]->operator_id == $operator_id_2->id){
						$operator_jobs = true;
						break;	
					}
				}
				// dd($operator_jobs);
				if(!$operator_jobs){
					continue;
				}
			}

		}else{
			echo '<p style="color: red">Per la fase di test, avviare i lavori in sequenza, prima il lavoro e poi il successivo</p>';
			$operator_jobs = false;
			for ($i=0; $i < count($ws->jobs); $i++) { 
				if($ws->jobs[$i]->operator_id == Auth::user()->id){
					$operator_jobs = true;
					break;	
				}
			}
			// dd($operator_jobs);
			if(!$operator_jobs){
				continue;
			}
		}
			
	@endphp

	{{-- Worksheet --}}
	<br>
	<div class="row">
		
		<h3 class="col-12"><b>Worksheet#{{ $ws->id }}</b> | 
			@php
				$start_date_convert = strtotime($ws->work_started);
				echo date('jS M y', $start_date_convert);

				if($ws->delivery_date != null){
					$delivery_date_convert = strtotime($ws->delivery_date);
					echo ' - ' . date('jS M y', $delivery_date_convert);
				}
			@endphp
		</h3>
		<br><br>

		@foreach ($ws['jobs'] as $job)
		@php
			if((Auth::user()->role == 1  || Auth::user()->role == 2)){
				if(isset($operator_id_2)){
					if($job->operator_id != $operator_id_2->id){
						continue;
					}
				}
			}else{
				if($job->operator_id != Auth::user()->id){
					continue;
				}
			}
				
		@endphp
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
					<h4 style="position: absolute;left:50%;transform: translateX(-50%);width : 100%; text-align: center">{{ $job->object->title }}</h4>
				</div>
				<div class="card-body" style="position: relative" >
					<div id="{{ $job->timer->html_id }}" class="app-timer" style="position: relative">
						
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
							@if (!(Auth::user()->role == 1  || Auth::user()->role == 2))
								<div 
									class="timer-overlay {{ ($job->completed == 1) ? 'completed feather icon-check' : 'start feather icon-play' }} " 
									
								></div>	
							@endif

							@if ((Auth::user()->role == 1  || Auth::user()->role == 2))
								<div 
									class="{{ ($job->completed == 1) ? 'timer-overlay completed feather icon-check' : '' }} view-only"
									data-job-progress="{{ ($job->timer->in_progress == 1) ? 1 : 0 }}"
									data-time-limit="{{ $job->timer->max_at - $job->timer->started_at }}"
									data-seek="{{ strtotime('now') - $job->timer->started_at   }}"
								></div>	
							@endif
							
							<div class="min-time-data" style="display: none">{{ $job->object->min_time * 60 }}</div> <!-- It should be placed here -->
							<div class="max-time-data" style="display: none">{{ $job->object->max_time * 60 }}</div>
						</div>

						

						@if ($job->completed != 1)
						<span class="base-timer-label" style="font-size: 15px"><br></span>
						@endif
						
						@if ($job->timer->in_progress != 1 && $job->completed != 1)
						<span class="not-started" style="font-size: 15px"><b>Not Started</b></span>
						@endif
						<br>
						@php
							if ($job->completed == 1) {
								if($job->timer->finished_at >= $job->timer->max_at) {
									$seconds = $job->timer->finished_at - $job->timer->max_at;
									$hours = floor($seconds / 3600);
									$mins = floor($seconds / 60 % 60);
									$secs = floor($seconds % 60);
									echo '<span style="color: red;font-size: 15px">Overdue By: ' . sprintf('%02d:%02d:%02d', $hours, $mins, $secs) .' </span>';
								}else{
									$seconds = $job->timer->max_at - $job->timer->finished_at;
									$hours = floor($seconds / 3600);
									$mins = floor($seconds / 60 % 60);
									$secs = floor($seconds % 60);
									echo '<span style="color: green;font-size: 15px">Submitted ' . sprintf('%02d:%02d:%02d', $hours, $mins, $secs) .' Early</span>';
								}
							}
						@endphp

						{{-- {{ ($job->completed == 1) ? 'completed feather icon-check' : 'start feather icon-play' }} --}}
					</div>
					<div class="">
						<small>Min. Time: {{ $job->object->min_time }} mins | Max. Time: {{ $job->object->max_time }} mins</small>
					</div>
					@if ((Auth::user()->role == 1  || Auth::user()->role == 2))
								<small><b>@php
										$op_id = $job->operator_id;
										$op = $user->find($op_id);
										echo $op->name;
								@endphp</b></small>	
					@endif
					
					
				</div>
			</div>
			
		</div>
		@endforeach

	</div>
	@endforeach


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
@if (isset($timer_continue) && $timer_continue != false && !(Auth::user()->role == 1  || Auth::user()->role == 2))
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
		var VIEW_ONLY = '{{ (Auth::user()->role == 1 || Auth::user()->role == 2) ? "1" : "0" }}';
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
					'min_at': MIN_TIME 
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
					'html_id': html_id
					
				},
				success: function(data) {
					toastr.success('','Your work is submitted');
					window.location.reload(false); 
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
