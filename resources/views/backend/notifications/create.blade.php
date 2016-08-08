@extends ('backend.layouts.master')

@section ('title', 'Admin Create Company Details')

@section('page-header')
<h1>
    Send Notifications
</h1>
@endsection

@section('content')

<section class="content">
    <div class="row" id='notification_add'>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <!--                <div class="box-header with-border">
                                        <h3 class="box-title">Add Push Notifications</h3>
                                    </div> /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="{{ route('backend.admin.notificationsave') }}" accept-charset="UTF-8" role="form" >
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="office_life">Send To</label>

                                <select name="notif_cat" id="notif_cat" class="form-control">
                                    <option value="">Please select</option>
                                    @foreach($category as $value)
                                    <option
                                        value="{{ $value->id }}"
                                        id="{{ $value->category }}">
                                        {{ $value->category }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="country" style="display: none;">
                                <label for="office_life">Countries</label>

                                <select name="country_id" id="country_id" class="form-control">
                                    <option value="">Please select</option>
                                    @foreach($countries as $country)
                                    <option
                                        value="{{ $country->id }}"
                                        >
                                        {{ $country->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="area" style="display: none;">
                                <label for="office_life">Areas</label>

                                <select name="area_id" id="area_id" class="form-control">
                                    <option value="">Please select</option>
                                    @foreach($areas as $area)
                                    <option
                                        value="{{ $area->id }}"
                                        >
                                        {{ $area->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Message</label>
                                <textarea class="form-control textarea" name="notification" cols="30" rows="5"></textarea>
                            </div>

                        </div><!-- /.box-body -->


                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div>
        </div>
    </div>
</section>
@endsection
@section('after-scripts-end')
<script>
    $(document).ready(function () {
        $("#notif_cat").change(function () {
            var id = $(this).children(":selected").attr("id");
            $('#country').hide();
            $('#area').hide();
            if (id == "Per Country")
            {
                $('#country').show();
                $('#area').hide();
            } else if (id == "Per Area")
            {
                $('#country').hide();
                $('#area').show();
            }
        });

    });
</script>
@endsection