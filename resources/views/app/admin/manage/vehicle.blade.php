
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
         <div class="card-title">Manage Vehicle</div>
       </div>
        <div class="card-body">
          
          <form action="{{ route('admin.manage.vehicle_update', request()->id) }}" method="POST">
            @csrf
            @method('PUT')
          
              <div class="row">
                <div class="form-group col-4">
                  <div class="controls">
                    <label>Date of Acceptance</label>
                    <input type="date" name="vehicle[date_of_acceptance]" class="form-control" readonly value="{{ $vehicle->date_of_acceptance }}" >
                  </div>
                </div>
                <div class="form-group col-4">
                  <div class="controls">
                    <label>License Plate</label>
                    <input type="text" name="vehicle[license_plate]" value="{{ $vehicle->license_plate }}" class="form-control" id="license" placeholder="AAAA####" required>
                  </div>
                </div>
                <div class="form-group col-4">
                  <div class="controls">
                    <label>Engine</label>
                      <select name="vehicle[engine_variant]" class="form-control" id="" required>
                        <option value="">Select Engine Variant</option>
                        <option value="diesel" {{ ($vehicle->engine_variant == 'diesel') ? 'selected' : '' }}>Diesel</option>
                        <option value="petrol" {{ ($vehicle->engine_variant == 'petrol') ? 'selected' : '' }}>Petrol</option>
                        <option value="gas" {{ ($vehicle->engine_variant == 'gas') ? 'selected' : '' }}>Gas</option>
                      </select>
                  </div>
                </div>
                <div class="form-group col-4">
                  <div class="controls">
                    <label>Car Brand</label>
                    <input type="text" name="vehicle[car_brand]" value="{{ $vehicle->car_brand }}" class="form-control" placeholder="Toyota" required>
                  </div>
                </div>
                <div class="form-group col-4">
                  <div class="controls">
                    <label>Car Model</label>
                    <input type="text" name="vehicle[car_model]" value="{{ $vehicle->car_model }}" class="form-control" placeholder="Corolla" required>
                  </div>
                </div>
                <div class="form-group col-4">
                  <div class="controls">
                    <label>Engine Displaceement</label>
                    <input type="number" name="vehicle[engine_displacement]" value="{{ $vehicle->engine_displacement }}" class="form-control" placeholder="1799cc" required>
                  </div>
                </div>
                <div class="form-group col-4">
                  <div class="controls">
                    <label>Revision Due Date</label>
                    <input type="date" name="vehicle[revision_due_date]" value="{{ $vehicle->revision_due_date }}" class="form-control" required>
                  </div>
                </div>
                <div class="form-group col-4">
                  <div class="controls">
                    <label>Fuel Level</label>
                    <input type="number" name="vehicle[fuel_level]" value="{{ $vehicle->fuel_level }}" placeholder="78%" class="form-control" required>
                  </div>
                </div>
                <div class="form-group col-4">
                  <div class="controls">
                    <label>Mileage</label>
                    <input type="number" name="vehicle[mileage]" value="{{ $vehicle->mileage }}" placeholder="40000" class="form-control" required>
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
    
@endsection
