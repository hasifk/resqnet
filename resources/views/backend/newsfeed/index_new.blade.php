

    @foreach($newsfeeds as $newsfeed)
    <tr>
        <td>{{ $newsfeed->id }}</td>
        <td>{{ $newsfeed->news }}</td>
        <td>{!! $newsfeed->action_buttons !!}</td>
    </tr>
    @endforeach
