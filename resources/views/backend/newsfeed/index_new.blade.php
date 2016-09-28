
@if(count($newsfeeds) > 0)
@foreach($newsfeeds as $newsfeed)
<tr>
    <td>{{ $newsfeed->id }}</td>
    <td>{{ $newsfeed->title }}</td>
    <td>{{ $newsfeed->newsfeed_type }}</td>
    <td>{!! $newsfeed->action_buttons !!}</td>
</tr>
@endforeach
@else
<tr><th colspan="3"> No news feeds Found</th></tr>
@endif
