@extends('backend.layouts.master')

@section('page-header')
<h1>
    {!! app_name() !!}
    <small>{{ trans('strings.backend.dashboard.title') }}</small>
</h1>
@endsection
@section('content')
<h3>News Feeds</h3>
@include('backend.includes.partials.newsfeed.header-buttons')
<div id="newsfeeds">
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
</div>
@endsection
@section('after-scripts-end')
<script type="text/javascript">
    function doChosen() {
        $(".chosen-select").chosen({});
        $(".chosen-select-deselect").chosen({allow_single_deselect: true});
        $(".chosen-select-no-single").chosen({disable_search_threshold: 10});
        $(".chosen-select-no-results").chosen({no_results_text: 'Oops, nothing found!'});
        $(".chosen-select-width").chosen({width: "95%"});
    }
</script>
<script>
    $(document).ready(function () {
        doChosen();
        $('#country_id').on('change', function () {
            $('#state_id').html('<option value=""></option>');
            $('#area_id').html('<option value=""></option>');
            $.getJSON('/admin/getstates/' + $(this).val(), function (json) {
                var listitems = '<option value=""></option>';
                $.each(json, function (key, value)
                {
                    //listitems += '<option value=' + value.id + '>' + value.name + '</option>';
                    $('#state_id').append('<option value=' + value.id + '>' + value.name + '</option>');
                });

                //$('#state_id').html(listitems);
                $("#state_id").trigger("chosen:updated"); //Updating Chosen Dynamically
                $('#area_id').html('<option value="">Please Select</option>');
            });

        });

        $('#state_id').on('change', function () {
            $('#area_id').html('<option value=""></option>');
            $.getJSON('/admin/getareas/' + $(this).val(), function (json) {
                $.each(json, function (key, value)
                {
                    //listitems += '<option value=' + value.id + '>' + value.name + '</option>';
                    $('#area_id').append('<option value=' + value.id + '>' + value.name + '</option>');
                });
                // $('#area_id').html(listitems);
                $("#area_id").trigger("chosen:updated"); //Updating Chosen Dynamically
            });
        });
        $('#search').on('click', function () {
            if ($('#country_id').val() == '')
            {
                alert("Please Select Country");
                $('#country_id').focus();
            } else if ($('#state_id').val() != '' && $('#area_id').val() == '')
            {
                alert("Please Select City");
                $('#area_id').focus();
            } else
            {
                var formData = {
                    country_id: $('#country_id').val(),
                    state_id: $('#state_id').val(),
                    area_id: $('#area_id').val(),
                    rescur: $('#rescur').val(),
                }
                $.ajax({
                    type: "get",
                    url: '/admin/newsfeedsearch/',
                    data: formData,
                    cache: false,
                    success: function (data) {
                        //console.log(data);
                        $('#newsfeeds').html(data);
                    }
                })

            }
            return false;
        });
    });
</script>
@endsection

