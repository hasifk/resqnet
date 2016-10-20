@extends ('backend.layouts.master')

@section ('title', "News Feeds Management")

@section('page-header')
<h1>
    News Feeds
</h1>
@endsection

@section('content')


<table class="table table-striped table-bordered table-hover">

    <tbody>
        @if(!empty($usergroup->image_path))
        <tr>
            <th></th>
            <td><img src="{{$usergroup->newsfeed_image_src}}" width="90" height="90"></td>
        </tr>
        @endif
        <tr>
            <th>To</th>
            <td>{{$usergroup->name}}</td>
        </tr>

        <tr>
            <th>User Name</th>
            <td>{{ $usergroup->firstname." ".$usergroup->lastname }}</td>
        </tr>
        <tr>
            <th>Country</th>
            <td>
                @if($usergroup->members)
                <table>
                <?php
                foreach ($usergroup->members as $value) {
                 echo $value->firstname;
                }
                ?>
                </table>
                @endif
            </td>
        </tr>
        @if(!empty($usergroup->area))
        <tr>
            <th>Area</th>
            <td>{{ $usergroup->area }}</td>
        </tr>
        @endif
        <tr>
            <th>Date</th>
            <th>{{ date('d-m-y h:i:s',strtotime($usergroup->created_at)) }}</th>
        </tr>

        <tr>
            <th>News</th>
            <th>{!! $usergroup->news !!}</th>
        </tr>
        <tr>
            <th></th>
            <th>
                <a href="{{URL::previous()}}" class="btn btn-danger btn-xs">Back</a>
                <!--                <a href="{{route('admin.newsfeed.newsfeedshow',$usergroup->id)}}" class="btn btn-primary btn-xs">
                                    Edit News
                                </a>-->
            </th>
        </tr>

    </tbody>
</table>
<div class="clearfix"></div>
@endsection
