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
    <div class="row col-xs-12 col-sm-12 col-md-12 btn-group m-t-25">
        <center>
            <?php echo $newsfeeds->links(); ?>
        </center>
    </div>
    <table class="table table-responsive m-t-20">
        <thead>
            <tr class="danger">
                <th>ID</th>
<!--                <th>Title</th>-->
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
<!--                <td>{{ $newsfeed->news_title }}</td>-->
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
           
             $('#state_id').html('<option value="">Please Select</option>');
                $('#area_id').html('<option value="">Please Select</option>');
                $("#state_id").trigger("chosen:updated");
                $("#area_id").trigger("chosen:updated");
                if ($(this).val() != '') {
            $.getJSON('/admin/getstates/' + $(this).val(), function (json) {
                $.each(json, function (key, value)
                {
                    $('#state_id').append('<option value=' + value.id + '>' + value.name + '</option>');
                });

                
                $("#state_id").trigger("chosen:updated"); //Updating Chosen Dynamically

            });
        }
        else
            location.reload();
        });

        $('#state_id').on('change', function () {
            $('#area_id').html('<option value="">Please Select</option>');
            if ($(this).val() != '') {
                $.getJSON('/admin/getareas/' + $(this).val(), function (json) {

                    $.each(json, function (key, value)
                    {
                        $('#area_id').append('<option value=' + value.id + '>' + value.name + '</option>');
                    });

                    $("#area_id").trigger("chosen:updated"); //Updating Chosen Dynamically
                });
            } else
                $("#area_id").trigger("chosen:updated"); //Updating Chosen Dynamically
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
    $(document).ajaxComplete(function () {
        $('.pagination li a').click(function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var formData = {
                country_id: $('#country_id').val(),
                state_id: $('#state_id').val(),
                area_id: $('#area_id').val(),
                rescur: $('#rescur').val(),
            }

            $.ajax({
                type: "get",
                url: url,
                data: formData,
                cache: false,
                success: function (data) {
                    console.log(data);
                    $('#newsfeeds').html(data);
                }
            })
        });
    });
</script>
@endsection

