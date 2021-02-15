
@extends('layouts/contentLayoutMaster')

@section('title', 'All users')

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
{{-- Data All users starts --}}
<section id="data-list-view" class="data-list-view-header">
    {{-- DataTable starts --}}
    <div class="table-responsive">
      <table class="table data-list-view">
        <thead>
          <tr>
            <th style="display: none"></th>
            <th>NAME</th>
            <th>E-MAIL</th>
            <th>ROLE</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            @if($user["role"] === 1)
              <?php $color = "success" ?>
            @elseif($user["role"] === 2)
              <?php $color = "primary" ?>
            @elseif($user["role"] === 3)
              <?php $color = "warning" ?>
            @elseif($user["role"] === 4)
              <?php $color = "info" ?>
            @endif
            <?php
              $arr = array('success', 'primary', 'info', 'warning', 'danger');
            ?>

            <tr onclick="location.href = '{{ route('admin.users.edit', $user['id']) }}'">
              <td style="display: none"></td>
              <td class="product-category">{{ $user["name"] }}</td>
              <td class="product-category">{{ $user["email"] }}</td>
              <td>
                <div class="chip chip-{{$color}}">
                  <div class="chip-body">
                    @php
                        $roles = [1 => 'Admin', 2 => 'Acceptor', 3 => 'Operator', 4 => 'Customer'];
                        $role = $roles[$user['role']]
                    @endphp
                    <div class="chip-text">{{ $role }}</div>
                  </div>
                </div>
              </td>
              <td class="product-action">
                <span class="action-edit"><a href="{{ route('admin.users.edit', $user['id']) }}" ><i class="feather icon-edit"></i></a></span>
                <span class="action-delete">
                  <form method="POST" style="display: inline-block" action="{{ route('admin.users.destroy', $user['id']) }}" >
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
  {{-- Data All users end --}}
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
