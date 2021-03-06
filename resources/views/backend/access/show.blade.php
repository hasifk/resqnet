@extends ('backend.layouts.master')

@section ('title', "User Details")

@section('page-header')
<h1>
    User Details 
</h1>
@endsection

@section('content')


<div class="tab-content">

    <div role="tabpanel" class="tab-pane active" id="profile">
        <table class="table table-striped table-hover table-bordered dashboard-table">
            <tr>
                @if(!empty($avatar))
                <th colspan="2"><center><img src="{{$avatar}}" width="150" height="150"></center></th>
            @else
            <th colspan="2"><img src="https://placeholdit.imgix.net/~text?txtsize=28&txt=150%C3%9784&w=150&h=84"/></th>
            @endif

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
                    @if ($user->roles()->count() > 0)
                                        @foreach ($user->roles as $role)
                                            {!! $role->name !!}<br/>
                                        @endforeach
                                    @else
                                        {{ trans('labels.general.none') }}
                                    @endif
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
                        <?php
                    } else
                        echo "---"
                        ?>
                </td>
            </tr>
            <?php if (!empty($usergroups)) { ?>
                <tr>
                    <th>User Groups</th>
                    <td>

                        <table>
                            <?php foreach ($usergroups as $groups): ?>
                                <tr>
                                    <td>

                                        <a href="{{route('user.groups.view',$groups->id)}}"> {{ $groups->name }} </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </table>

                    </td>
                </tr>
            <?php } ?>
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
            <tr><th colspan="2"><center>Other Informations</center></th></tr>
            @if($user->role_id==5)
            
            <tr>
                <th>Payment Details</th>
                @if(!empty($payments))
                <td>

                    <table> <tr>

                            <th>Status</th>
                            <td>
                                &nbsp;&nbsp;:&nbsp;&nbsp;  {{ $payments->payment_status }}
                            </td>
                        </tr>

                        <tr>

                            <th>Subscription ends at</th>
                            <td>
                                &nbsp;&nbsp;:&nbsp;&nbsp;  {{ $payments->subscription_ends_at }}
                            </td>
                        </tr>

                    </table>

                </td>
                @else
                <th>No payment Details yet</th>
                @endif
                
            </tr>
            
            @endif
            <tr>
                <th>Device Infromation</th>
                <td>

                    <table> <tr>

                            <th>Device Type</th>
                            <td>
                                &nbsp;&nbsp;:&nbsp;&nbsp;  {{ $user->device_type }}
                            </td>
                        </tr>

                        <tr>

                            <th>Device ID</th>
                            <td>
                                &nbsp;&nbsp;:&nbsp;&nbsp;  {{ $user->app_id }}
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
            <tr>
                <th>Permenent Location</th>
                <td>

                    <table> <tr>

                            <th>Location(Lat,Long)</th>
                            <td>
                                &nbsp;&nbsp;:&nbsp;&nbsp;  {{ $user->per_lat.",".$user->per_lng }}
                            </td>
                        </tr>

                        <tr>

                            <th>Address</th>
                            <td>
                                &nbsp;&nbsp;:&nbsp;&nbsp;  {{ $user->per_address }}
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
            <tr>
                <th>Current Location</th>
                <td>

                    <table> <tr>

                            <th>Location(Lat,Long)</th>
                            <td>
                                &nbsp;&nbsp;:&nbsp;&nbsp;  {{ $user->lat.",".$user->lng }}
                            </td>
                        </tr>

                        <tr>

                            <th>Address</th>
                            <td>
                                &nbsp;&nbsp;:&nbsp;&nbsp;  {{ $user->address }}
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
            @if($user->role_id!=5 && $user->role_id!=1)
            <tr><th>Online Status</th><th>{{ $user->online_status==1?"Online":"Offline" }}</th></tr>
            @endif
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
