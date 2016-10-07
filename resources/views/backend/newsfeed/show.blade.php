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
        @if(!empty($newsfeed->image_path))
        <tr>
            <th></th>
            <td><img src="{{$newsfeed->newsfeed_image_src}}" width="90" height="90"></td>
        </tr>
        @endif
        <tr>
            <th>To</th>
            <td>{{$newsfeed->newsfeed_type}}</td>
        </tr>
        
        <tr>
            <th>User Name</th>
            <td>{{ $newsfeed->firstname." ".$newsfeed->lastname }}</td>
        </tr>
         <tr>
            <th>Country</th>
            <td>{{ $newsfeed->name }}</td>
        </tr>
         @if(!empty($newsfeed->area))
         <tr>
            <th>Area</th>
            <td>{{ $newsfeed->area }}</td>
        </tr>
        @endif
        <tr>
            <th>Date</th>
            <th>{{ date('d-m-y h:i:s',strtotime($newsfeed->created_at)) }}</th>
        </tr>
<!--        <tr>
            <th>Title</th>
            <th>{!! $newsfeed->news_title !!}</th>
        </tr>-->
        <tr>
            <th>News</th>
            <th>{!! $newsfeed->news !!}</th>
        </tr>
        <tr>
            <th></th>
            <th>
                <a href="{{URL::previous()}}" class="btn btn-danger btn-xs">Back</a>
<!--                <a href="{{route('admin.newsfeed.newsfeedshow',$newsfeed->id)}}" class="btn btn-primary btn-xs">
                    Edit News
                </a>-->
            </th>
        </tr>
        
    </tbody>
</table>
<div class="clearfix"></div>
@endsection
