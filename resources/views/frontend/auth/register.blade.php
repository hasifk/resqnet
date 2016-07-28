@extends('frontend.layouts.master')

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('labels.frontend.auth.register_box_title') }}</div>

                <div class="panel-body">

                    {!! Form::open(['url' => 'register', 'class' => 'form-horizontal']) !!}

                        <div class="form-group">
                            {!! Form::label('name', trans('validation.attributes.frontend.name'), ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::input('name', 'name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.name')]) !!}
                            </div><!--col-md-6-->
                        </div><!--form-group-->

                        <div class="form-group">
                            {!! Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::input('email', 'email', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.email')]) !!}
                            </div><!--col-md-6-->
                        </div><!--form-group-->

                        <div class="form-group">
                            {!! Form::label('password', trans('validation.attributes.frontend.password'), ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::input('password', 'password', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.password')]) !!}
                            </div><!--col-md-6-->
                        </div><!--form-group-->

                        <div class="form-group">
                            {!! Form::label('password_confirmation', trans('validation.attributes.frontend.password_confirmation'), ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.password_confirmation')]) !!}
                            </div><!--col-md-6-->
                        </div><!--form-group-->

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                {!! Form::submit(trans('labels.frontend.auth.register_button'), ['class' => 'btn btn-primary']) !!}
                            </div><!--col-md-6-->
                        </div><!--form-group-->
                        <div class="form-group">
            <label for="office_life" class="col-md-4 control-label">Country</label>
            <div class="col-md-6">
                <select name="country_id" id="country_id" class="form-control">
                    <option value="">Please select</option>
                    @foreach($countries as $country)
                        <option
                                value="{{ $country->id }}"
                                {{ old('country_id') && $country->id == old('country_id') ? 'selected="selected"' : '' }}
                        >
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
                        <div class="form-group">
            <label for="office_life" class="col-md-4 control-label">State</label>
            <div class="col-md-6">
                <select name="state_id" id="state_id" class="form-control">
                    <option value="">Please select</option>
                    @foreach($states as $state)
                        <option
                                value="{{ $state->id }}"
                                {{ old('state_id') && $state->id == old('state_id') ? 'selected="selected"' : '' }}
                        >
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

                    {!! Form::close() !!}

                </div><!-- panel body -->

            </div><!-- panel -->

        </div><!-- col-md-8 -->

    </div><!-- row -->
@endsection