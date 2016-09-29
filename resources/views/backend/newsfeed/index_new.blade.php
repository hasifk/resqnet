<?php
if ($newsfeeds->currentPage() > 1)
    $f = ($newsfeeds->currentPage() - 1) * $newsfeeds->perPage() + 1;
else
    $f = 1;
?>
<div class="row col-xs-12 col-sm-12 col-md-12 btn-group">
    <center>
        <?php echo $newsfeeds->links(); ?>
    </center>
</div>
<table class="table table-responsive m-t-20">
    <thead>
        <tr class="danger">
            <td>ID</td>
            <td>Title</td>
            <td>Type</td>
            <td>Action</td>
        </tr>
    </thead>
    <tbody>

        @if(count($newsfeeds) > 0)
        @foreach($newsfeeds as $newsfeed)
        <tr>
            <td>{{ $f++ }}</td>
            <td>{{ $newsfeed->news_title }}</td>
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