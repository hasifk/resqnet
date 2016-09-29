<?php
    if ($newsfeeds->currentPage() > 1)
        $f = ($newsfeeds->currentPage() - 1) * $newsfeeds->perPage() + 1;
    else
        $f = 1;
    ?>
    <div class="row col-xs-12 col-sm-12 col-md-12 btn-group m-t-25">
        <center>
            <?php echo $newsfeeds->links(); ?>
        </center>
    </div>
    <table class="table table-responsive m-t-20">
        <thead>
            <tr class="danger">
                <th>ID</th>
                <th>Title</th>
                <th>User</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($newsfeeds) > 0)
            @foreach($newsfeeds as $newsfeed)
            <tr>
                <td>{{ $f++ }}</td>
                <td>{{ $newsfeed->news_title }}</td>
                <td><a href="{{route('admin.access.user.shows',$newsfeed->id)}}"><span>{{ $newsfeed->firstname." ". $newsfeed->lastname }}</span></a></td>
                <td>{{ $newsfeed->newsfeed_type }}</td>
                <td>{!! $newsfeed->action_buttons !!}</td>
            </tr>
            @endforeach
            @else
            <tr><th colspan="3"> No news feeds Found</th></tr>
            @endif
        </tbody>
    </table>
    @if($newsfeeds->count() == $newsfeeds->perPage())
    <div class="row col-xs-12 col-sm-12 col-md-12 btn-group">
        <center>
            <?php echo $newsfeeds->links(); ?>
        </center>
    </div>
    @endif