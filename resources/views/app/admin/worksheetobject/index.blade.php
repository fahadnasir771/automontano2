
@extends('layouts/contentLayoutMaster')

@section('title', 'List View')

@section('vendor-style')
        {{-- vendor files --}}
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')) }}">
@endsection
@section('page-style')
        {{-- Page css files --}}
        <link rel="stylesheet" href="{{ asset(mix('css/plugins/file-uploaders/dropzone.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">
@endsection

@section('content')
{{-- Data list view starts --}}
<section id="data-list-view" class="data-list-view-header">
    {{-- DataTable starts --}}
    <div class="table-responsive">
      <table class="table data-list-view">
        <thead>
          <tr>
            <th style="display: none"></th>
            <th>Title</th>
            <th>Min Time</th>
            <th>Max Time</th>
            <th>Operator</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($worksheet_objects as $data)

            <tr onclick="location.href = '{{ route('admin.worksheetobject.edit', $data->id) }}'">
              <td style="display: none"></td>
              <td class="product-category">{{ $data->title }}</td>
              <td class="product-category">{{ $data->min_time }}</td>
              <td class="product-category">{{ $data->max_time }}</td>
              <td>
                @foreach($data->operators as $operator)
                <div class="chip chip-success">
                  <div class="chip-body">
                    <div class="chip-text">{{ $operator->name }}</div>
                  </div>
                </div>
                @endforeach
              </td>
              <td class="product-action">
                <span class="action-edit"><a href="{{ route('admin.worksheetobject.edit', $data->id) }}" ><i class="feather icon-edit"></i></a></span>
                <span class="action-delete">
                  <form method="POST" style="display: inline-block;" action="{{ route('admin.worksheetobject.destroy', $data->id) }}" >
                   @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn"><i style="color: red" class="feather icon-trash"></i></button>
                  </form>
                </span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    {{-- DataTable ends --}}
    <style>
      .action-btns {
        display: none !important
      }
    </style>


  </section>
  {{-- Data list view end --}}
@endsection
@section('vendor-script')
{{-- vendor js files --}}
        <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.select.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
@endsection
@section('page-script')
        {{-- Page js files --}}
        <script src="{{ asset(mix('js/scripts/ui/data-list-view.js')) }}"></script>
@endsection
