@extends('backend.layouts.master')

@section('page-header')
<h1>
    {!! app_name() !!}
    <small>{{ trans('strings.backend.dashboard.title') }}</small>
</h1>
@endsection

@section('content')
<h3>News Feeds</h3>

@if($newsfeeds)
<table class="table">
    <tr>
        <td>ID</td>
        <td>News</td>
 <td>Action</td>
    </tr>
    @foreach($newsfeeds as $newsfeed)
    <tr>
        <td>{{ $newsfeed->id }}</td>
        <td>{{ $newsfeed->news }}</td>
        <td>{!! $newsfeed->action_buttons !!}</td>
    </tr>
    @endforeach
</table>
@endif
@unless($newsfeeds)
<h5>No News</h5>
@endunless

@endsection



