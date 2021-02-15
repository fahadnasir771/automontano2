@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
        <!-- vendor css files -->
@endsection
@section('page-style')
        <!-- Page css files -->
  @endsection

  @section('content')
   <section>
     Admin
   </section>
  @endsection

@section('vendor-script')
        <!-- vendor files -->
        <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/extensions/tether.min.js')) }}"></script>
@endsection
@section('page-script')
        <!-- Page js files -->
@endsection
