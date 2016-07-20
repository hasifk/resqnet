@extends ('backend.layouts.master')

@section ('title', 'department')

@section('page-header')
<h1>
    Create Department
</h1>
@endsection

@section('content')

@include('backend.includes.partials.department.header-buttons')

<section class="content">
    <div class="row" id='notification_add'>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <!--                <div class="box-header with-border">
                                        <h3 class="box-title">Add Push Notifications</h3>
                                    </div> /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="{{ route('backend.admin.department_save') }}" accept-charset="UTF-8" role="form" >
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="rescuer_type" class="col-md-4 control-label">Rescuer Type</label>
                                <div class="col-md-6">
                                    <select name="type_id" id="country_id" class="form-control">
                                        <option value="">Please select</option>
                                        @foreach($types as $type)
                                        <option
                                            value="{{ $type->id }}">
                                            {{ $type->type }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="department" class="col-md-4 control-label">Department</label>
                                <div class="col-md-6">
                                    {!! Form::input('name', 'department','',['class' => 'form-control','placeholder'=>'department']) !!}
                                </div>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <label for="submit" class="col-md-4 control-label"></label> 
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div>
        </div>
    </div>
</section>
@endsection
