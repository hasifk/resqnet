@extends ('backend.layouts.master')

@section ('title', "Panic Details")

@section('page-header')
<h1>
    Statistics
</h1>
@endsection

@section('content')


<table class="table table-striped table-bordered table-hover">

    <tbody>
        <tr>
            <th>User</th>
            <td><a href="{{route('admin.access.user.shows',$list->rescuee_id)}}"> {{ $list->rescuee_details->firstname.' '.$list->rescuee_details->lastname }} </a></td>
        </tr>

        <tr>
            <th>Available ResQueres</th>
            <td>
                <table> <?php
                    if (!empty($list->rescuers_details)):
                        //$resccuer_id = json_decode($list->rescuers_ids)
                        ?>
                        @foreach($list->rescuers_details as $resid)
                        <tr>
                            <td>
                                <a href="{{route('admin.access.user.shows',$resid->id)}}">{{ $resid->firstname.' '.$resid->lastname }}</a>
                            </td>
                        </tr>
                        @endforeach
                        <?php
                    else:
                        echo "No Rescuers Found";
                    endif;
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <th>Tagged ResQuer</th>
            <td>
                <?php if (!empty($list->tagged)): ?>
                    <a href="{{route('admin.access.user.shows',$list->tagged->id)}}">
                        {{ $list->tagged->firstname.' '.$list->tagged->lastname }}</a>
                    <?php
                else:
                    echo "No Rescuer Tagged";
                endif;
                ?> 
            </td>
        </tr>

        <tr>
            <th>Emergency Contacts</th>
            <td><table> <?php
                    if (!empty($list->emergency_details)):
                        //$resccuer_id = json_decode($list->rescuers_ids)
                        ?>
                        @foreach($list->emergency_details as $resid)
                        <tr>
                            <td>
                                <a href="{{route('admin.access.user.shows',$resid->id)}}">{{ $resid->firstname.' '.$resid->lastname }}</a>
                            </td>
                        </tr>
                        @endforeach
                        <?php
                    else:
                        echo "No Contacts";
                    endif;
                    ?>
                </table></td>
        </tr>
        <tr>
            <th>Emergency Groups</th>
            <td><table> <?php
                    if (!empty($list->emergency_groups)):
                        //print_r($list->emergency_groups);
                        ?>
                        @foreach($list->emergency_groups as $resid)
                        <tr>
                            <td>
                                <ul class="accordion" id="heading_{{$resid->id}}">
                                    <li>
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{$resid->id}}" aria-expanded="false" aria-controls="collapse_{{$resid->id}}">
                                            <span class="text"> {{ $resid->name }}</span>
                                        </a>
                                    </li>
                                    <li id="collapse_{{$resid->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{$resid->id}}">
                                        @foreach($list->group_details[$resid->id] as $value)
                                        <ul><li><a href="{{route('admin.access.user.shows',$value->user_id)}}">{{ $value->firstname.' '.$value->lastname }}</a></li>

                                        </ul>
                                        @endforeach
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        @endforeach
                        <?php
                    else:
                        echo "No Emergecy Groups";
                    endif;
                    ?>
                </table></td>
        </tr>
        <tr>
            <th>ResQuer Response (H:M:S) </th>
            <th> @if(!empty($list->rescuerresponse)){{ $list->rescuerresponse}} @else 00:00:00 @endif </th>
        </tr>
        <tr>
            <th>Finished (H:M:S) </th>
            <th> @if(!empty($list->finished)){{ $list->finished}} @else 00:00:00 @endif </th>
        </tr>
        <tr>
            <th>Date</th>
            <th>{{$list->created_at}}</th>
        </tr>
        <tr>
            <th></th>
            <th>
                <a href="{{URL::previous()}}" class="btn btn-danger btn-xs">Back</a>
            </th>
        </tr>

    </tbody>
</table>
<div class="clearfix"></div>
@endsection
