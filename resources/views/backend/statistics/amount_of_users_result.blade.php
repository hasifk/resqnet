@extends ('backend.layouts.master')



@section('page-header')
    <h1>
        Statistics
    </h1>
@endsection


@section('content')


    <h3>Amount of Users within a Country/Area</h3>


   @if(isset($place))
       @if(isset($amount))
           The Amount of users in {{$place}} is:{{$amount}}
           @endif
       @endif

    <div class="clearfix"></div>
@stop
