@extends ('backend.layouts.master')

@section ('title', "ResQuer Management")

@section('page-header')
<h1>
    Resquer Management
</h1>
@endsection

@section('content')

@include('backend.includes.partials.department.header-buttons')

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Rescuer Type</th>
             <th>Department</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $f = 1; ?>
        @foreach ($departments as $department)
        <tr>
            <td>{{ $f++ }}</td>
            <td>{{ $department->resquerType->type }}</td>
            <td>{{ $department->department }}</td>

            <td>{!! $department->actionbuttons !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-left">
    {!! count($departments) !!} Departments Total
</div>

<div class="pull-right">
  
</div>
<div class="clearfix"></div>
@endsection
@section('after-scripts-end')
<script>
    $(document).ready(function () {
        $('.department_delete').on('click', function () {
            if (confirm("Are you sure want to delete")) {
                return true;
            }
            return false;
        });
    });
</script>
@endsection