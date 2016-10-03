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
                            <div class="row">
                                <div class="form-group col-md-4" id="country" style="display: none;">
                                    <label for="office_life">Countries<i><font color="red" size="3">*</font></i></label>

                                    <select data-placeholder="Choose a Country..." name="country_id" id="country_id" class="form-control chosen-select">
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
                                <div class="form-group col-md-4" id="state" style="display: none;">
                                    <label for="office_life">State</label>

                                    <select data-placeholder="Choose a State..." name="state_id" id="state_id" class="form-control chosen-select">    
                                        <option value="">Please select</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4" id="area" style="display: none;">
                                    <label for="office_life">Areas</label>

                                    <select data-placeholder="Choose a City..." name="area_id" id="area_id" class="form-control chosen-select">
                                        <option value="">Please select</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Message</label>
                                <textarea class="form-control textarea" id="notification" name="notification" cols="30" rows="5"></textarea>
                            </div>

                        </div><!-- /.box-body -->


                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div>
        </div>
    </div>
</section>
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
        $("#notif_cat").change(function () {
            var id = $(this).children(":selected").attr("id");
            $('#country').hide();
            $('#state').hide();
            $('#area').hide();
            if (id == "Per Country")
            {
                $('#country').show();
                $('#state').show();
                $('#area').show();
            }
        });
        $('#country_id').on('change', function () {

            $('#state_id').html('<option value="">Please Select</option>');
            $('#area_id').html('<option value="">Please Select</option>');
            $("#state_id").trigger("chosen:updated");
            $("#area_id").trigger("chosen:updated");
            if ($(this).val() != '') {
                $.getJSON('/admin/getstates/' + $(this).val(), function (json) {
                    $.each(json, function (key, value)
                    {
                        //listitems += '<option value=' + value.id + '>' + value.name + '</option>';
                        $('#state_id').append('<option value=' + value.id + '>' + value.name + '</option>');
                    });

                    //$('#state_id').html(listitems);
                    $("#state_id").trigger("chosen:updated"); //Updating Chosen Dynamically

                });
            } 
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
        $('#submit').on('click', function () {
            var id = $("#notif_cat").children(":selected").attr("id");
            if (id == "Per Country")
            {
                if ($('#country_id').val() == ""){
                    alert('Please Select Country');
                    $('#country_id').focus();
                    return false;
                }
                else if ($('#state_id').val() != "" && $('#area_id').val() == ""){
                    $('#area_id').focus();
                    alert('Please Select Area');
                    return false;
                }
            }
            if($('#notification').val().trim()=="")
            {
             $('#notification').focus();
             return false;
            }
           
        })
    });
</script>
@endsection