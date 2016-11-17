@extends ('backend.layouts.master')

@section ('title', "Panic Details")

@section('page-header')
<h1>
    Statistics
    <a href="{{URL::previous()}}" class="btn btn-xs btn-danger">
        <i class="fa fa-arrow-circle-left" data-toggle="tooltip" data-placement="top" title="" data-original-title="Back"></i>
    </a>

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
                <a href="{{route('admin.statistics.listsofrescuers')}}" class="btn btn-danger btn-xs">Back</a>
            </th>
        </tr>
    </tbody>
</table>
<?php
 $locations = json_decode($list->locations);
$loc="";
foreach ($locations as $key => $value) {
 if ($key == $list->rescuee_id) 
     $center=$value->lat.",".$value->long;
 else
   $loc=$loc.'['."'$value->addr'".",".$value->lat.",".$value->long.']'.",";
    
}
 $loc=rtrim($loc, ",");
?>
<div class="row col-xs-12">
    <h1>Available ReaQuers Map</h1>
    <div id="map" style="height: 400px; width: 500px;">

    </div>
    <div class="clearfix"></div>
    @endsection
    @section('before-scripts-start')
    <script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDifWuYepsM5Hez8kcwz1xSSY7WvXUFrgY" 
    type="text/javascript"></script>
    
    @endsection

    @section('after-scripts-end')
    <script type="text/javascript">
        
var locations = [
    <?php echo $loc ?>
];
// var locations = [
//      ['Bondi Beach', -33.890542, 151.274856, 4],
//      ['Coogee Beach', -33.923036, 151.259052, 5],
//      ['Cronulla Beach', -34.028249, 151.157507, 3],
//      ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
//      ['Maroubra Beach', -33.950198, 151.259302, 1]
//    ];
var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 10,
    center: new google.maps.LatLng(<?php echo $center; ?>),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
});

var infowindow = new google.maps.InfoWindow();

var marker, i;
var marker = new google.maps.Marker({
          position: map.getCenter(),
          icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 10
          },
          draggable: true,
          map: map,
          title: 'Venue Name'
        });
for (i = 0; i < locations.length; i++) {
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
    });

    google.maps.event.addListener(marker, 'click', (function (marker, i) {
        return function () {
            infowindow.setContent(locations[i][0]);
            infowindow.open(map, marker);
        }
    })(marker, i));
}
    </script>
    @endsection
    @section('before-styles-end')
    <style>
        #map {
            height: 100%;
        }
    </style>
    @endsection
