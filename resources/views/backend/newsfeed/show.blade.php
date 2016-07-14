@extends ('backend.layouts.master')

@section ('title', "News Feeds Management")

@section('page-header')
<h1>
    Company Management
</h1>
@endsection

@section('content')

@include('backend.admin.includes.partials.newsfeeds.header-buttons')

<table class="table table-striped table-bordered table-hover">

    <tbody>

        <tr>
            <th>News</th>
            <td>{!! $newsfeed->news !!}</td>

        </tr>
        <tr><th></th><th>
                <a href="{{route('backend.admin.newsfeed.edit',$newsfeed->id)}}" class="btn btn-primary btn-xs">
                    Edit News
                </a>
            </th></tr>

    </tbody>
</table>
<div class="clearfix"></div>
@endsection
