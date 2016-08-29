@extends ('backend.layouts.master')

@section ('title', "User Details")

@section('page-header')
<h1>
    User Details {{ $user->title }}
</h1>
@endsection

@section('content')


<div class="tab-content">

    <div role="tabpanel" class="tab-pane active" id="profile">
        <table class="table table-striped table-hover table-bordered dashboard-table">
            <tr>
                <th colspan="2"><img src="{{$user->avatar_path.'/'.$user->avatar_filename.'.'.$user->avatar_extension}}"></th>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $user->firstname.' '.$user->lastname }}</td>
            </tr>
            <tr>
                <th>Display Name</th>
                <td>{{ $user->displayname }}</td>
            </tr>
            <tr>
                <th>Date Of Birth</th>
                <td>
                    {{ $user->dob }}
                </td>
            </tr>
            <tr>
                <th>Rescuer Type</th>
                <td>
                    {{ $user->rescuer_type }}
                </td>
            </tr>
             <tr>
                <th>Email</th>
                <td>
                    {{ $user->email }}
                </td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>
                    {{ $user->phone }}
                </td>
            </tr>
            <tr>
                <th>Jurisdiction</th>
                <td>
                    {{ $user->jurisdiction }}
                </td>
            </tr>
            <tr>
                <th>Country</th>
                <td>
                    {{ $country->name }}
                </td>
            </tr>
            <tr>
                <th>Area</th>
                <td>
                    {{ $area->name.' - '.$state->name }}
                </td>
            </tr>
        </table>
        <?php $poeple_count = count($doctor); ?>
        @if ($poeple_count)
        <div class="row">
            <div class="col-md-2 text-bold">Doctor Details</div>
            <div class="col-md-10">
                @foreach($doctor as $value)
                <div class="col-md-{{ 12/$poeple_count }}">
                    <table class="table table-striped table-hover table-bordered dashboard-table">
                        <tr>
                            <th>Name</th>
                            <td>{{ $value->name }}</td>
                        </tr>
                        <tr>
                            <th>Surname</th>
                            <td>{{ $value->surname }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $value->phone }}</td>
                        </tr>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        <table class="table table-striped table-hover table-bordered dashboard-table">
            <tr>
                <th>Emergency Contacts</th>
                <td>
                    <?php if (!empty($emergency)) { ?>
                    <table>

                        <tr>
                            <td>{{$emergency->emergency1}}</td>
                        </tr>
                        <?php if ($emergency->emergency2 !== 0) { ?>
                            <tr>
                                <td>{{$emergency->emergency2}}</td>
                            </tr>
                        <?php } if ($emergency->emergency3 !== 0) { ?>
                            <tr>
                                <td>{{$emergency->emergency3}}</td>
                            </tr>
                        <?php } ?>
                    </table>
                    <?php } 
                    else echo "---"
                        ?>
                </td>

            </tr>

            <tr>
                <th>Health Insurance Details</th>
                <td>
                    <?php if (!empty($insurance)) { ?>
                    <table>

                        <tr>
                            <th>Service Provider </th><td>&nbsp;&nbsp;:&nbsp;&nbsp; {{$insurance->service_provider}}</td>
                        </tr>
                        <tr>
                            <th>Insurance No  </th><td> &nbsp;&nbsp;:&nbsp;&nbsp; {{$insurance->insurance_no}}</td>
                        </tr>
                     
                    </table>
                    <?php } else echo "---" ?>
                </td>
            </tr>
           
            <tr>
                <th>Current Medical Conditions</th>
                <td>
                    {{ $user->current_medical_conditions }}
                </td>
            </tr>
            <tr>
                <th>Prior Medical Conditions</th>
                <td>
                    {{ $user->prior_medical_conditions }}
                </td>
            </tr>
            <tr>
                <th>Allergy Information</th>
                <td>
                    {{ $user->allergies }}
                </td>
            </tr>
        </table>



    </div>
    <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{{URL::previous()}}" class="btn btn-danger btn-xs">Back</a>
                </div>

                
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

</div>
@stop
