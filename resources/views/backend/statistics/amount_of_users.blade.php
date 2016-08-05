@extends ('backend.layouts.master')



@section('page-header')
    <h1>
        Statistics
    </h1>
@endsection


@section('content')


    <h3>Amount of Users within a Country/Area</h3>

   @if(!empty($countries))
    <table class="table">

        <tr>
            {!! Form::open(array('url' =>  route('admin.statistics.checkcountry'),'class' => 'form-horizontal' )) !!}
            {{ csrf_field() }}
            <td>Select a country</td>
            <td>
            <select name="country_id">
                @foreach($countries as $key=> $value)
                <option value="{!! $value->id!!}">{{$value->name}}</option>
                @endforeach
            </select>
            </td>


            <td>
                {!! Form::submit('Go', array('class' => 'btn btn-success btn-xs')) !!}
            </td>
            {!! Form::close() !!}
        </tr>
    </table>
    @endif
    <div class="clearfix"></div>
    <hr>
    @if(!empty($areas))
        <table class="table">

            <tr>
                {!! Form::open(array('url' =>  route('admin.statistics.checkarea'),'class' => 'form-horizontal' )) !!}
                {{ csrf_field() }}
                <td>Select an area</td>
                <td>
                    <select name="area_id">
                        @foreach($areas as $key=> $value)
                            <option value="{!! $value->id!!}">{{$value->name}}</option>
                        @endforeach
                    </select>
                </td>


                <td>
                    {!! Form::submit('Go', array('class' => 'btn btn-success btn-xs')) !!}
                </td>
                {!! Form::close() !!}
            </tr>
        </table>
    @endif

    <div class="clearfix"></div>
@stop
