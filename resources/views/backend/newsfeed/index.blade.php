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
<div class="box-header with-border">
    <div class="pull-left">
        <?php echo $newsfeeds->links(); ?>
    </div>
</div><!-- /.box-header -->
@if($newsfeeds)
<table class="table table-responsive m-t-20">
    <thead>
        <tr class="danger">
            <td>ID</td>
            <td>News</td>
            <td>Action</td>
        </tr>
    </thead>
    <tbody id="newsfeeds">
        @foreach($newsfeeds as $newsfeed)
        <tr>
            <td>{{ $newsfeed->id }}</td>
            <td>{{ $newsfeed->news }}</td>
            <td>{!! $newsfeed->action_buttons !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@unless($newsfeeds)
<h5>No News Feeds</h5>
@endunless

@endsection

@section('after-scripts-end')
<script>
    $(document).ready(function () {

        $('#country_id').on('change', function () {
            $('#state_id').html('<option value="">Please Select</option>');
            $('#area_id').html('<option value="">Please Select</option>');
            $.getJSON('/admin/getstates/' + $(this).val(), function (json) {
                var listitems = '<option value="">Please Select</option>';
                $.each(json, function (key, value)
                {
                    listitems += '<option value=' + value.id + '>' + value.name + '</option>';
                });
                $('#state_id').html(listitems);
                $('#area_id').html('<option value="">Please Select</option>');
            });
        });

        $('#state_id').on('change', function () {
            $('#area_id').html('<option value="">Please Select</option>');
            $.getJSON('/admin/getareas/' + $(this).val(), function (json) {
                var listitems = '<option value="">Please Select</option>';
                $.each(json, function (key, value)
                {
                    listitems += '<option value=' + value.id + '>' + value.name + '</option>';
                });
                $('#area_id').html(listitems);
            });
        });
        $('#search').on('click', function () {
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
            return false;
        });
    });
</script>
@endsection

