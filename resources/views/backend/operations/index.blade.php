@extends('backend.layouts.master')

@section('page-header')
<h1>
    {!! app_name() !!}
    <small>{{ trans('strings.backend.dashboard.title') }}</small>
</h1>
@endsection

@section('content')
<h3>Rescue Operations</h3>

@if($operations)
<table class="table">
    <tr>
        <td>Rescuee</td>
        <td>Rescuer</td>
 <td>Status</td>
    </tr>
    @foreach($operations as $operation)
    <tr>
        <td>{{ $operation->rescuee_name }}</td>
        <td>{{ $operation->rescuer_name }}</td>
        <td>{{ $operation->operation_status }}</td>
    </tr>
    @endforeach
</table>
@endif
@unless($operations)
<h5>No News</h5>
@endunless

@endsection



