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
        <tr>
            <th>To</th>
            <td>{{$newsfeed->newsfeed_type}}</td>
        </tr>
        @if(!empty($newsfeed->image_path))
        <tr>
            <th></th>
            <td><img src="{{$newsfeed->image_path.'/'.$newsfeed->image_filename.'.'.$newsfeed->image_extension}}"</td>
        </tr>
        @endif
        <tr>
            <th>News</th>
            <td>{!! $newsfeed->news !!}</td>
        </tr>
        <tr>
            <th></th>
            <th>
                <a href="{{route('admin.newsfeed.newsfeedshow',$newsfeed->id)}}" class="btn btn-primary btn-xs">
                    Edit News
                </a>
            </th>
        </tr>
    </tbody>
</table>
<div class="clearfix"></div>
@endsection
