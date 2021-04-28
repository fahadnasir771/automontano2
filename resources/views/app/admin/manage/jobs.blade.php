
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
         <div class="card-title">Manage Jobs</div>
       </div>
        <div class="card-body">
          
          <form action="{{ route('admin.manage.jobs_update', request()->id) }}" method="POST">
            @csrf
            @method('PUT')
          
            <div class="existing-job-container">
              @php
                $i =0;
              @endphp
              @foreach ($jobs_created as $j)

                <div class="existing-job-group {{ ($j->completed == 1 || $j->started == 1) ? 'non' : '' }}" style=" 
                background: rgba(136, 136, 136, 0.1); padding: 20px;">
                  @if ($j->completed == 1)
                    <p style="color: #1e7fce">You can't modify this job as it is already <b>Finished</b></p>

                  @else

                  @if ($j->started == 1)
                    <p style="color: #1e7fce">You can't modify this job as it is <b>In Progress</b></p>
                  @endif

                  @endif
                  
                  
                  <select name="existing_job[{{ $i }}][object]" class="form-control existing_job" id="" style="pointer-events: {{ ($j->completed == 1 || $j->started == 1) ? 'none' : '' }}" >
                    <option value="">Select Job</option>
                    @foreach ($jobs as $job)
                        
                        <option value="{{ $job->id }}" {{ ($job->id == $j->object_id) ? 'selected' : '' }}>{{ $job->title }}</option>
                    @endforeach

                  </select>
                  <br>
                  <select name="existing_job[{{ $i }}][operator]" class="form-control existing_job_operators" id="" style="pointer-events: {{ ($j->completed == 1 || $j->started == 1) ? 'none' : '' }}">

                    @if ($j->completed == 1 || $j->started == 1)

                      <option value="{{ $j->operator_id }}" selected>{{ $operators->find($j->operator_id)->name }}</option>

                    @else
                      <option value="">Select Operator</option>
                      <option value="{{ $j->operator_id }}" selected>{{ $operators->find($j->operator_id)->name }}</option>
                    @endif

                    
                  </select>
                </div>
                <br>
                @php
                  $i += 1;
                @endphp
              @endforeach

            </div>
            <div class="col-lg-6 col-md-6" style="padding-left: 0">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-outline-success square mr-1 mb-1 add-existing-job " style="width: 120px">Add</button>
                <button type="button" class="btn btn-outline-danger square mr-1 mb-1 remove-existing-job " style="width: 120px">Remove</button>
              
              </div>
            </div>
    
            
            

            <button type="submit" class="btn btn-primary float-right">Update Worksheet</button>

          </form>

        </div>
      </div>

    </div>
  </div>
  <div class="json" style="display: none">{{$jobs}}</div>

@endsection
@section('vendor-script')
{{-- vendor js files --}}
        
        
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/app-chat.js')) }}"></script>

    <script>
      


var json = JSON.parse($('.json').text());
    // console.log(json);
    // console.log(json[0]['operators']);

    $(document).on('change', '.existing_job' , (el) => {    
      
      // Verify alreadt added
      // for(let i=0; i < $('.existing_job').length; i++){
      //   if($(el.target).val() == $('.existing_job').eq(i).val()){
      //     alert('You have already added the job!');
      //     return false;
      //   }
      // }

      var existing_jobs_group = '';
      for(let i=0; i < json.length; i++ ){
        if($(el.target).val() == json[i]['id']){
          let operators = json[i]['operators'];
          for(let j=0; j < operators.length; j++){
            existing_jobs_group += `<option value="` + operators[j]['id'] + `">` + operators[j]['name'] + `</option>`;
          }
          break;
        }
      }
      $(el.target).siblings('.existing_job_operators').html('<option value="" >Select Operator</option>');
      $(el.target).siblings('.existing_job_operators').append(existing_jobs_group);
      
    });
          //Adding and removing existing job
    $('.add-existing-job').on('click', () => {
      $('.existing_job').attr('required', true);
      $('.existing_job_operators').attr('required', true);
      var existing_job = `
        <div class="existing-job-group" style=" background: rgba(136, 136, 136, 0.1); padding: 40px;border-radius: 10px">
          <select name="existing_job[` + $('.existing-job-group').length +`][object]" class="form-control existing_job" id="" required>
            <option value="" >Select Job ` + ($('.existing-job-group').length + 1) + `</option>
            @foreach ($jobs as $job)
              <option value="{{ $job->id }}">{{ $job->title }}</option>
            @endforeach
          </select>
          <br>
          <select name="existing_job[` + $('.existing-job-group').length +`][operator]" class="form-control existing_job_operators" id="" required>
            <option value="" >Select Operator </option>
          </select>
        </div>
        <br>
      `;
      $('.existing-job-container').append(existing_job);
    });

    $('.remove-existing-job').on('click', () => {

      if($('.existing-job-group').length > 1){
        if($('.existing-job-group').eq($('.existing-job-group').length - 1).hasClass('non')){

        }else{
          $('.existing-job-group').eq($('.existing-job-group').length - 1).remove();
        }
        

        if($('.existing-job-group').length == 1){
          $('.existing_job').attr('required', false);
          $('.existing_job_operators').attr('required', false);
        }
      }else{
        $('.existing_job').attr('required', false);
        $('.existing_job_operators').attr('required', false);
      }
    });

    $('.existing_job').on('change', function(){
      for (let index = 0; index < $('.existing_job').length; index++) {
        if($('.existing_job').eq(index).val() != ''){
          $('.existing_job_operators').eq(index).attr('required', true);
        }else{
          $('.existing_job_operators').eq(index).attr('required', false);
        }
      }
    })


    </script>
    
@endsection
