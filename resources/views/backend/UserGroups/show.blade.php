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
        @if(!empty($usergroup->gp_image_path))
        <tr>
            <th></th>
            <td><img src="{{$usergroup->gp_image_src}}" width="90" height="90"></td>
        </tr>
        @endif
        <tr>
            <th>Group Name</th>
            <td>{{$usergroup->name}}</td>
        </tr>

        <tr>
            <th>Group Pin</th>
            <td>{{ $usergroup->gp_pin }}</td>
        </tr>
        <tr>
            <th>Members</th>
            <td>
                @if($usergroup->members)
                <table class="table table-bordered">
                    
                <?php
                foreach ($usergroup->members as $value) {
                    ?>
                    <tr><td>{{ $value->firstname ." ".$value->lastname}} @if($value->role==1) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <sub><font color="gray" size="2"><i> Administrator </i></font></sub>@endif</td></tr>
                 <?php
                }
                ?>
                </table>
                @endif
            </td>
        </tr>
        
        <tr>
            <th>Date</th>
            <th>{{ date('d-m-y h:i:s',strtotime($usergroup->created_at)) }}</th>
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
