
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
         <div class="card-title">Manage Spare parts</div>
       </div>
        <div class="card-body">
          
          <form action="{{ route('admin.manage.spare_update', request()->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table table-default table-bordered">
              <thead>
                <th style="width: 55%">Spare Part Title</th>
                <th style="width: 1px">Available</th>
                <th style="width: 1px">Order</th>
                <th style="width: 1px">Delivery Date</th>
                <th style="width: 15%">Price</th>
                <th style="width: 15%">Labor Fee</th>
              </thead>
              <tbody class="spare-container">
                @php
                  $i=0;
                @endphp
                @foreach ($spares as $s)
                  <tr class="spare">
                    <td><input type="text" value="{{ $s->title }}" name="spare[{{ $i }}][title]" placeholder="Spare Part Title" class="form-control spare-title" required></td>
                    <td>
                      <fieldset>
                        <div class="vs-radio-con">
                          <input type="radio" class="spare-radio0" name="spare[{{ $i }}][available]" 
                            {{ ($s->available == 1) ? 'checked ' : '' }}
                            value="1"
                          >
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
                          <input type="radio" class="spare-radio" {{ ($s->available == 0) ? 'checked ' : '' }} name="spare[{{ $i }}][available]" value="0">
                          <span class="vs-radio">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                          </span>
                        </div>
                      </fieldset>
                    </td>
                    <td>
                      <input type="date" name="spare[{{ $i }}][delivery_date]" value="{{ ($s->available == 0) ? $s->delivery_date : '' }}"  class="form-control spare-date" required>   
                    </td>
                    <td>
                      <input type="number" name="spare[{{ $i }}][price]" value={{ $s->price }} class="form-control spare-price" placeholder="0.00" required>
                    </td>
                    <td><input type="number" name="spare[{{ $i }}][labor_fee]" value="{{ $s->labor_fee }}" class="form-control spare-fee" placeholder="0.00" required></td>
                  </tr>
                  @php
                    $i += 1;
                  @endphp
                @endforeach

              </tbody>
            </table>
              
              
              
            
    
            <br>
            <div class="col-lg-12 col-md-12 mb-1" style="right: 0; position: relative">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-outline-success  square mr-1 mb-1 add-spare" style="width: 120px">Add</button>
                <button type="button" class="btn btn-outline-danger square mr-1 mb-1 remove-spare" style="width: 120px">Remove</button>
              
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
          
        }else{
          $('.spare-date').eq(i).attr('readonly', false);
          $('.spare-date').eq(i).attr('required', true);
          
        }
      }
    }
    sparePartRadio();
    $(document).on('change', '.spare-radio, .spare-radio0' ,() => {
      sparePartRadio();
    });

    </script>
    
@endsection
