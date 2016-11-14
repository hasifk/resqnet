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
                <a href="{{URL::previous()}}" class="btn btn-danger btn-xs">Back</a>
            </th>
        </tr>
    </tbody>
</table>
<div class="row col-xs-12">
    <h1>Track Details</h1>
    <div id="map"></div>
    
</div>

<div class="clearfix"></div>
@endsection
@section('before-scripts-start')
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDifWuYepsM5Hez8kcwz1xSSY7WvXUFrgY&callback=initMap">
    </script>
<script>

      function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 3,
          center: {lat: -28.024, lng: 140.887}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }
      var locations = [
        {lat: -31.563910, lng: 147.154312},
        {lat: -33.718234, lng: 150.363181},
        {lat: -33.727111, lng: 150.371124},
        {lat: -33.848588, lng: 151.209834},
        {lat: -33.851702, lng: 151.216968},
        {lat: -34.671264, lng: 150.863657},
        {lat: -35.304724, lng: 148.662905},
        {lat: -36.817685, lng: 175.699196},
        {lat: -36.828611, lng: 175.790222},
        {lat: -37.750000, lng: 145.116667},
        {lat: -37.759859, lng: 145.128708},
        {lat: -37.765015, lng: 145.133858},
        {lat: -37.770104, lng: 145.143299},
        {lat: -37.773700, lng: 145.145187},
        {lat: -37.774785, lng: 145.137978},
        {lat: -37.819616, lng: 144.968119},
        {lat: -38.330766, lng: 144.695692},
        {lat: -39.927193, lng: 175.053218},
        {lat: -41.330162, lng: 174.865694},
        {lat: -42.734358, lng: 147.439506},
        {lat: -42.734358, lng: 147.501315},
        {lat: -42.735258, lng: 147.438000},
        {lat: -43.999792, lng: 170.463352}
      ]
    </script>
    
@endsection
@section('before-styles-end')
<style>
    #map {
        height: 100%;
    }
</style>
@endsection
