@extends('layouts/contentLayoutMaster')

@section('title', 'Create Worksheet')

@section('vendor-style')
        {{-- Page Css files --}}
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">

       
@endsection

@section('page-style')
        {{-- Page Css files --}}
        <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/validation/form-validation.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/pages/app-user.css')) }}">

        <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">

@endsection

@section('content')
<style>
  label {
    font-size: 1em
  }
</style>

<!-- Form wizard with step validation section start -->
<section id="validation">
  <div class="row">
      <div class="col-12">
          <div class="card">
              {{-- <div class="card-header">
                  <h3 class="card-title">Worksheet</h3>
              </div> --}}
              <div class="card-content">
                  <div class="card-body">
                      <form class="steps-validation wizard-circle" id="form" 
                      action="{{ (str_contains($route, 'admin/')) ? route('admin.worksheets.store') : route('acceptor.worksheets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                          <!-- Step 1 -->
                          <h6> Vehicle Details</h6>
                          <fieldset>
                            <br>
                              <h3>Vehicle Details</h3>
                              <br>
                              <div class="row">
                                <div class="form-group col-4">
                                  <div class="controls">
                                    <label>Date of Acceptance</label>
                                    <input type="date" name="vehicle[date_of_acceptance]" class="form-control" readonly value="{{ date('Y-m-d') }}" >
                                  </div>
                                </div>
                                <div class="form-group col-4">
                                  <div class="controls">
                                    <label>License Plate</label>
                                    <input type="text" name="vehicle[license_plate]" class="form-control" id="license" placeholder="AAAA####" required>
                                  </div>
                                </div>
                                <div class="form-group col-4">
                                  <div class="controls">
                                    <label>Engine</label>
                                      <select name="vehicle[engine_variant]" class="form-control" id="" required>
                                        <option value="">Select Engine Variant</option>
                                        <option value="diesel" >Diesel</option>
                                        <option value="petrol" >Petrol</option>
                                        <option value="gas" >Gas</option>
                                      </select>
                                  </div>
                                </div>
                                <div class="form-group col-4">
                                  <div class="controls">
                                    <label>Car Brand</label>
                                    <input type="text" name="vehicle[car_brand]" class="form-control" placeholder="Toyota" required>
                                  </div>
                                </div>
                                <div class="form-group col-4">
                                  <div class="controls">
                                    <label>Car Model</label>
                                    <input type="text" name="vehicle[car_model]" class="form-control" placeholder="Corolla" required>
                                  </div>
                                </div>
                                <div class="form-group col-4">
                                  <div class="controls">
                                    <label>Engine Displaceement</label>
                                    <input type="number" name="vehicle[engine_displacement]" class="form-control" placeholder="1799cc" required>
                                  </div>
                                </div>
                                <div class="form-group col-4">
                                  <div class="controls">
                                    <label>Revision Due Date</label>
                                    <input type="date" name="vehicle[revision_due_date]" class="form-control" required>
                                  </div>
                                </div>
                                <div class="form-group col-4">
                                  <div class="controls">
                                    <label>Fuel Level</label>
                                    <input type="number" name="vehicle[fuel_level]" placeholder="78%" class="form-control" required>
                                  </div>
                                </div>
                                <div class="form-group col-4">
                                  <div class="controls">
                                    <label>Mileage</label>
                                    <input type="number" name="vehicle[mileage]" placeholder="40000" class="form-control" required>
                                  </div>
                                </div>
            
                                <div class="col-lg-12 col-md-12">
                                  <fieldset class="form-group">
                                      <label for="basicInputFile">Add Multiple Images</label>
                                      <div class="custom-file" >
                                          <input type="file" name="vehicle[images][]" class="custom-file-input"  id="images" multiple required>
                                          <label class="custom-file-label images"  for="inputGroupFile01">Choose Images</label>
                                      </div>
                                  </fieldset>
                              </div>
            
                                <div class="failures-container row" style="width: 100%; margin: 0">
            
                                  <div class="failure row" style="width: 100%; margin: 0">
                                    <div class="form-group col-8">
                                      <div class="controls">
                                        <label>Failure Reported By the Client</label>
                                        <input type="text" name="failure[0][failure_title]"  placeholder="Defected Steering Pump" class="form-control failure-input" required>
                                      </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <div class="controls">
                                          <label>Price Quotation</label>
                                          <input type="number" name="failure[0][failure_quotation]" placeholder="300" class="form-control failure-price" required>
                                        </div>
                                    </div>
                                  </div>
                                  
                                </div>
            
                                <br>
                                <div class="col-lg-12 col-md-12 mb-1" style="right: 0; position: relative">
                                  <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-success square mr-1 mb-1 add-failure" style="width: 120px">Add</button>
                                    <button type="button" class="btn btn-outline-danger square mr-1 mb-1 remove-failure" style="width: 120px">Remove</button>
                                  
                                  </div>
                                </div>
                            </div>
                              
                          </fieldset>

                          <!-- Step 2 -->
                          <h6>Spare Parts</h6>
                          <fieldset>
                            <div class="row">
                              <div class="col-12">
                                <h3>Spare Parts</h3>
                                <br>
                                <table class="table table-default table-bordered table-dark">
                                  <thead>
                                    <th style="width: 55%">Spare Part Title</th>
                                    <th style="width: 1px">Available</th>
                                    <th style="width: 1px">Order</th>
                                    <th style="width: 1px">Delivery Date</th>
                                    <th style="width: 15%">Price</th>
                                    <th style="width: 15%">Labor Fee</th>
                                  </thead>
                                  <tbody class="spare-container">
                                    <tr class="spare">
                                      <td><input type="text" name="spare[0][title]" placeholder="Spare Part Title" class="form-control spare-title" required></td>
                                      <td>
                                        <fieldset>
                                          <div class="vs-radio-con">
                                            <input type="radio" class="spare-radio0" name="spare[0][available]" checked value="1">
                                            <span class="vs-radio">
                                              <span class="vs-radio--border"></span>
                                              <span class="vs-radio--circle"></span>
                                            </span>
                                          </div>
                                        </fieldset>
                                      </td>
                                      <td>
                                        <fieldset>
                                          <div class="vs-radio-con">
                                            <input type="radio" class="spare-radio" name="spare[0][available]" value="0">
                                            <span class="vs-radio">
                                              <span class="vs-radio--border"></span>
                                              <span class="vs-radio--circle"></span>
                                            </span>
                                          </div>
                                        </fieldset>
                                      </td>
                                      <td>
                                        <input type="date" name="spare[0][delivery_date]"  class="form-control spare-date" required>   
                                      </td>
                                      <td>
                                        <input type="number" name="spare[0][price]" class="form-control spare-price" placeholder="0.00" required>
                                      </td>
                                      <td><input type="number" name="spare[0][labor_fee]" class="form-control spare-fee" placeholder="0.00" required></td>
                                    </tr>
                                  </tbody>
                                </table>
                                
                              </div>
                              <div class="col-lg-12 col-md-12 mb-1" style="right: 0; position: relative">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                  <button type="button" class="btn btn-outline-success btn-dark square mr-1 mb-1 add-spare" style="width: 120px">Add</button>
                                  <button type="button" class="btn btn-outline-danger btn-dark square mr-1 mb-1 remove-spare" style="width: 120px">Remove</button>
                                
                                </div>
                              </div>
                              
                            </div>
                          </fieldset>

                          <!-- Step 3 -->
                          <h6>Job Details</h6>
                          <fieldset>                      
                            <div class="row" >
                              <br>
                              <div class="col-6 ">
                                <br>
                                
                                <h4 style="text-align: center">Existing Jobs</h4>
                                <br>
            
                                <div class="existing-job-container">
            
                                  <div class="existing-job-group" style=" background: rgba(136, 136, 136, 0.1); padding: 40px;">
                                    <select name="existing_job[0][object]" class="form-control existing_job" id="">
                                      <option value="">Select Job</option>
                                      @foreach ($jobs as $job)
                                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                                      @endforeach
                                    </select>
                                    <br>
                                    <select name="existing_job[0][operator]" class="form-control existing_job_operators" id="">
                                      <option value="">Select Operator</option>
                                    </select>
                                  </div>
                                  <br>
            
                                </div>
                                <div class="col-lg-6 col-md-6" style="padding-left: 0">
                                  <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-success square mr-1 mb-1 add-existing-job " style="width: 120px">Add</button>
                                    <button type="button" class="btn btn-outline-danger square mr-1 mb-1 remove-existing-job " style="width: 120px">Remove</button>
                                  
                                  </div>
                                </div>
            
                              </div>
                              
                              <div class="col-6 ">
                                <br>
                                <h4 style="text-align: center">Create Job</h4>
                                <br>
            
                                <div class="create-job-container">
                                  
                                  <div class="create-job-group" style=" background: rgba(136, 136, 136, 0.1); padding: 40px;">
                                    
                                    <input type="text" name="create_job[0][object]" class="form-control  create_job_title" readonly placeholder="Job Title">
                                    <br>
                                    <select name="create_job[0][operator]" class="form-control create_job_operators" id="" readonly>
                                      <option value="">Select Operator</option>
                                      @foreach ($operators as $operator)
                                        {{-- <option value="{{ $operator->id }}">{{ $operator->name }}</option> --}}
                                      @endforeach
                                    </select>
                                    <br>
                                    <div class="row">
                                      <div class="col-6">
                                        <input type="number" name="create_job[0][min_time]" class="form-control  create_job_min_time" placeholder="Min. Completeion Time" readonly>
                                      </div>
                                      <div class="col-6">
                                        <input type="number" name="create_job[0][max_time]" class="form-control  create_job_max_time" placeholder="Max. Completeion Time" readonly>
                                      </div>
                                    </div>
                                    
                                  </div>
                                  
            
                                </div>
                                <br>
                                <div class="col-lg-6 col-md-6" style="padding-left: 0">
                                  <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-success square mr-1 mb-1 add-create-job " style="width: 120px">Add</button>
                                    <button type="button" class="btn btn-outline-danger square mr-1 mb-1 remove-create-job " style="width: 120px">Remove</button>
                                  
                                  </div>
                                </div>

                                <p style="color: red">Per la fase di test, non è possibile creare un lavoro durante la creazione del foglio di lavoro</p>
            
                              </div>
                             
                            </div>
                          </fieldset>

                          <!-- Step 4 -->
                          <h6>Customer Details</h6>
                          <fieldset>                      
                            <h3>Customer Details</h3>
                            <br>

                            <div class="row">

                              <div class="form-group col-4">
                                <div class="controls">
                                  <label>Full Name</label>
                                  <input type="text" name="customer[full_name]" class="form-control" placeholder="John Doe" required>
                                </div>
                              </div>
                              <div class="form-group col-4">
                                <div class="controls">
                                  <label>Surame</label>
                                  <input type="text" name="customer[surname]" class="form-control surname" placeholder="John" required>
                                </div>
                              </div>
                              <div class="form-group col-4 only-invoice-group">
                                <div class="controls">
                                  <label>City Of Residence</label>
                                  <input type="text" name="customer[city]" class="form-control only-invoice" placeholder="New York" >
                                </div>
                              </div>
                              <div class="form-group col-4 only-invoice-group">
                                <div class="controls">
                                  <label>Street</label>
                                  <input type="text" name="customer[street]" class="form-control only-invoice" placeholder="Street#2" >
                                </div>
                              </div>
                              <div class="form-group col-4 only-invoice-group">
                                <div class="controls">
                                  <label>Fiscal Code</label>
                                  <input type="text" name="customer[fiscal_code]" class="form-control only-invoice" placeholder="####" >
                                </div>
                              </div>
                              <div class="form-group col-4 only-invoice-group">
                                <div class="controls">
                                  <label>VAT Number</label>
                                  <input type="text" name="customer[vat_number]" class="form-control only-invoice" placeholder="####" >
                                </div>
                              </div>
                              {{-- <div class="form-group col-4">
                                <div class="controls" style="position:relative;">
                                  <label>Cell Phone</label>
                                  <input type="number" name="customer[cell_phone]" class="form-control" id="cellphone" placeholder="####" required style="padding-left: 45px" maxlength="10" minlength="9">
                                  <div style="position: absolute; top: 28px;left: 10px">
                                    <b>+32</b>
                                  </div>
                                </div>
                              </div> --}}
                              <div class="form-group col-4">
                                <div class="controls">
                                  <label>Phone</label>
                                  <input type="number" name="customer[phone]" class="form-control" placeholder="#### (Optional)">
                                </div>
                              </div>
                              <div class="form-group col-4">
                                <div class="controls">
                                  <label>E-mail Address</label>
                                  <input type="email" name="customer[email]" class="form-control" placeholder="john@email.com (Optional)">
                                </div>
                              </div>
                            </div>
                            <h3>Metadata</h3>
                            <br>
                            <div class="row">

                              <div class="form-group col-4">
                                <div class="controls">
                                  <label for="">Work Start Date</label>
                                  <input type="datetime-local" id="work-start-date" name="meta[work_started]" class="form-control" required>
                                </div>
                              </div>
                              <div class="form-group col-4">
                                <div class="controls">
                                  <label>Days Required For Completion of work</label>
                                  <input type="number" name="meta[days_required]" class="form-control" placeholder="12" required>
                                </div>
                              </div>
                              
                              <div class="form-group col-4">
                                <label></label>
                              
                                <ul class="list-unstyled mb-0 ">
                                  <li class="d-inline-block mr-2">
                                    <fieldset>
                                      <div class="vs-radio-con">
                                        <input type="radio" class="voucher1 voucher" name="meta[voucher_radio]" checked value="receipt">
                                        <span class="vs-radio">
                                          <span class="vs-radio--border"></span>
                                          <span class="vs-radio--circle"></span>
                                        </span>
                                        Receipt
                                      </div>
                                    </fieldset>
                                  </li>
                                  <li class="d-inline-block mr-2">
                                    <fieldset>
                                      <div class="vs-radio-con">
                                        <input type="radio" class="voucher2 voucher" name="meta[voucher_radio]" value="invoice">
                                        <span class="vs-radio">
                                          <span class="vs-radio--border"></span>
                                          <span class="vs-radio--circle"></span>
                                        </span>
                                        Invoice
                                      </div>
                                    </fieldset>
                                  </li>
                              </ul>
                                
                              </div>

                            </div>
                          </fieldset>

                         
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>
<!-- Form wizard with step validation section end -->



<div class="json" style="display: none">{{$jobs}}</div>
  

<!-- users edit ends -->
@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jqBootstrapValidation.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>

  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/extensions/jquery.steps.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

@endsection

@section('page-script')
  {{-- Page js files --}}

  <script src="{{ asset(mix('js/scripts/pages/app-user.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/navs/navs.js')) }}"></script>


  {{-- tabs config --}}
  <script>
    var form = $(".steps-validation").show();

    $(".steps-validation").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: 'Submit'
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }

            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
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
          
          if(
            year2 >= year3 &&
            month2 >= month3 &&
            datenum2 >= date3
          ){
            if(
              hr2 > hr3 ||
              (
                hr2 == hr3 && min2 > min3
              )
            ){
              $('#form').submit();
            }else{
              alert('fail2')
            }
            // var url = 'https://web.whatsapp.com/send?phone=92' + $('#cellphone').val() + '&text=Hello%2C%20Thankyou%20' + $('.surname').val() + '%20for%20coming%20to%20us...%0APlease%20login%20to%20your%20portal%20to%20see%20and%20accept%20the%20details%20of%20your%20worksheet%0A%0AUser%3A%20'              + $('#cellphone').val() + '%0APass%3A%20' + $('#license').val();
            // var win = window.open(url, '_blank');
            // win.focus();
            // 
          }else{
            alert('fail')
          }
          
        }
    });

    // Initialize validation
    $(".steps-validation").validate({
        ignore: 'input[type=hidden]', // ignore hidden fields
        errorClass: 'danger',
        successClass: 'success',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        rules: {
            email: {
                email: true
            }
        }
    });
  </script>

  <script>
    function disbaleInvoiceFields() {
      if($('.voucher1').is(':checked')){
        $('.only-invoice').attr('disabled', true);
        $('.only-invoice').attr('required', false);
        $('.only-invoice-group').css('display', "none");
      }else{
        $('.only-invoice').attr('disabled', false);
        $('.only-invoice').attr('required', true);
        $('.only-invoice-group').css('display', "block");
      }
    }
    disbaleInvoiceFields();
    $('.voucher').on('change', () => {
      disbaleInvoiceFields();
    });

    $('.add-failure').on('click', () => {
      var failure = `
                <div class="failure row" style="width: 100%; margin: 0">
                  <div class="form-group col-8">
                    <div class="controls ">
                      <label>Failure ` + ($('.failure').length + 1) + `</label>
                      <input type="text" name="failure[` + $('.failure').length + `][failure_title]"  placeholder="Failure ` + ($('.failure').length + 1) + `" class="form-control failure-input" required>
                    </div>
                  </div>
                  <div class="form-group col-4">
                      <div class="controls">
                        <label>Price Quotation ` + ($('.failure').length + 1) + `</label>
                        <input type="number" name="failure[` + $('.failure').length + `][failure_quotation]" placeholder="300" class="form-control failure-price" required>
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


    // Existing job Finder
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
            <option value="" >Select Job ` + ($('.add-existing-job').length + 1) + `</option>
            @foreach ($jobs as $job)
              <option value="{{ $job->id }}">{{ $job->title }}</option>
            @endforeach
          </select>
          <br>
          <select name="existing_job[` + $('.existing-job-group').length +`][operator]" class="form-control existing_job_operators" id="" required>
            <option value="" >Select Operator ` + ($('.add-existing-job').length + 1) + `</option>
          </select>
        </div>
        <br>
      `;
      $('.existing-job-container').append(existing_job);
    });

    $('.remove-existing-job').on('click', () => {

      if($('.existing-job-group').length > 1){
        $('.existing-job-group').eq($('.existing-job-group').length - 1).remove();

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

    $('#images').on('focusin', () => {
      $('.images').css('border', '1px solid rgba(0, 0, 0, 0.2)');
    });


    $('.add-spare').on('click', () => {
      var spareTemplate = index => {return `
      <tr class="spare">
          <td><input type="text" name="spare[` + index + `][title]" placeholder="Spare Part Title" class="form-control spare-title" required></td>
          <td>
            <fieldset>
              <div class="vs-radio-con">
                <input type="radio" class="spare-radio0" name="spare[` + index + `][available]" checked value="1">
                <span class="vs-radio">
                  <span class="vs-radio--border"></span>
                  <span class="vs-radio--circle"></span>
                </span>
              </div>
            </fieldset>
          </td>
          <td>
            <fieldset>
              <div class="vs-radio-con">
                <input type="radio" class="spare-radio" name="spare[` + index + `][available]" value="0">
                <span class="vs-radio">
                  <span class="vs-radio--border"></span>
                  <span class="vs-radio--circle"></span>
                </span>
              </div>
            </fieldset>
          </td>
          <td>
            <input type="date" name="spare[` + index + `][delivery_date]" class="form-control spare-date" required>   
          </td>
          <td>
            <input type="number" name="spare[` + index + `][price]" class="form-control spare-price" placeholder="0.00" required>
          </td>
          <td><input type="text" name="spare[` + index + `][labor_fee]" class="form-control spare-fee" placeholder="0.00" required></td>
        </tr>
      `};

      $('.spare-container').append(spareTemplate($('.spare').length))
      sparePartRadio();

    });
    $('.remove-spare').on('click', () => {
      if($('.spare').length > 1){
        $('.spare').eq($('.spare').length - 1).remove();
      }
    });


    function sparePartRadio(){
      for(let i=0; i < $('.spare').length; i++){
        if(!$('.spare-radio').eq(i).is(':checked')){
          $('.spare-date').eq(i).attr('readonly', true);
          $('.spare-date').eq(i).attr('required', false);
          $('.spare-date').eq(i).css({
            'background': '#313131',
            'border': '#313131'
          });
        }else{
          $('.spare-date').eq(i).attr('readonly', false);
          $('.spare-date').eq(i).attr('required', true);
          $('.spare-date').eq(i).css({
            'background': '#fff',
            'border': '#fff'
          })
        }
      }
    }
    sparePartRadio();
    $(document).on('change', '.spare-radio, .spare-radio0' ,() => {
      sparePartRadio();
    });


    $('.add-create-job').on('click', () => {
      $('.create_job_title').attr('required', true);
      $('.create_job_operators').attr('required', true);
      $('.create_job_min_time').attr('required', true);
      $('.create_job_max_time').attr('required', true);
      var createJobTemplate = `
      <div class="create-job-group" style=" background: rgba(136, 136, 136, 0.1); padding: 40px;margin-top:15px">  
    
        <input type="text" name="create_job[` + ($('.create-job-group').length) + `][object]" class="form-control  create_job_title" placeholder="Job Title" required>
        <br>
        <select name="create_job[` + ($('.create-job-group').length) + `][operator]" class="form-control create_job_operators" id="">
          <option value="" required>Select Operator</option>
          @foreach ($operators as $operator)
            <option value="{{ $operator->id }}">{{ $operator->name }}</option>
          @endforeach
        </select>
        <br>
        <div class="row">
          <div class="col-6">
            <input type="number" name="create_job[` + ($('.create-job-group').length) + `][min_time]" class="form-control  create_job_min_time" placeholder="Min. Completeion Time" required>
          </div>
          <div class="col-6">
            <input type="number" name="create_job[` + ($('.create-job-group').length) + `][max_time]" class="form-control  create_job_max_time" placeholder="Max. Completeion Time" required >
          </div>
        </div>
        
      </div>
      
      `;
      $('.create-job-container').append(createJobTemplate);
    });
    $('.remove-create-job').on('click', () => {
      if($('.create-job-group').length > 1){
        $('.create-job-group').eq($('.create-job-group').length - 1).remove();

        if($('.create-job-group').length == 1){
          $('.create_job_title').attr('required', false);
          $('.create_job_operators').attr('required', false);
          $('.create_job_min_time').attr('required', false);
          $('.create_job_max_time').attr('required', false);
        }

      }else{
        $('.create_job_title').attr('required', false);
        $('.create_job_operators').attr('required', false);
        $('.create_job_min_time').attr('required', false);
        $('.create_job_max_time').attr('required', false);
      }
    });
    $('.create_job_title').on('change', function(){
      for (let index = 0; index < $('.create_job_title').length; index++) {
        if($('.create_job_title').eq(index).val() != ''){
          $('.create_job_operators').attr('required', true);
          $('.create_job_min_time').attr('required', true);
          $('.create_job_max_time').attr('required', true);
        }else{
          $('.create_job_operators').attr('required', false);
          $('.create_job_min_time').attr('required', false);
          $('.create_job_max_time').attr('required', false);
        }
      }
    })


  </script>
  

@endsection

