@extends ('backend.layouts.master')
@section('page-header')
<h1>
    Statistics
</h1>
@endsection

@section('content')

<h3>Amount of NewsFeeds</h3>

<section class="content">
    <div class="row">
        <div class="row">
            <div class="col-md-12">
                <!-- TO DO List -->
                <div class="box box-primary">
                    <div class="m-t-20">
                        <div class="col-xs-12 col-sm-6 col-md-2 btn-group">
                            <label for="office_life" class="control-label">Country <i><font color="red" size="3">*</font></i></label></label>
                            <select data-placeholder="Choose a Country..." name="country_id" id="country_id" class="form-control chosen-select">
                                <option value="">Please Select</option>
                                @foreach($countries as $country)
                                <option
                                    value="{{ $country->id }}"
                                    >
                                    {{ $country->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-2 btn-group">
                            <label for="office_life" class="control-label">State <i>(optional)</i></label>
                            <select data-placeholder="Choose a State..." name="state_id" id="state_id" class="form-control chosen-select"> 
                                <option value="">Please Select</option>

                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-2 btn-group">
                            <label for="office_life" class="control-label">City <i>(optional)</i></label>
                            <select data-placeholder="Choose a City..." name="area_id" id="area_id" class="form-control chosen-select">
                                <option value="">Please Select</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-3 btn-group">
                            <label for="office_life" class="control-label">User Type <i><font color="red" size="3">*</font></i></label>
                            <select name="rescur" id="rescur" class="form-control">
                                <option value="All">All</option>
                                <option value="Rescuer">Rescuer</option>
                                <option value="User">User</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-3 btn-group m-t-25">
                            <label for="office_life" class="control-label"></label>
                            <button class="mnotification_delete btn btn-primary" id="search">Search</button>
                        </div>
                    </div><!-- /.box -->
                </div>
            </div>

            <div class="col-md-12 m-t-25">
                <table class="table table-striped table-bordered table-hover">
                    <tr><th>Total Amount Of News Feeds is : {{$amount}}</th></tr>
                    <tr id="newsamount"></tr>
                </table>
                <div >

                </div>


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
        $('#country_id').on('change', function () {
            $('#state_id').html('<option value="">Please Select</option>');
                $('#area_id').html('<option value="">Please Select</option>');
                $("#state_id").trigger("chosen:updated");
                $("#area_id").trigger("chosen:updated");
            $.getJSON('/admin/getstates/' + $(this).val(), function (json) {
                
                $.each(json, function (key, value)
                {
                     $('#state_id').append('<option value=' + value.id + '>' + value.name + '</option>');
                });
                 $("#state_id").trigger("chosen:updated"); //Updating Chosen Dynamically
                
            });
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
                var type = $('#rescur').val();
                var formData = {
                    country_id: $('#country_id').val(),
                    state_id: $('#state_id').val(),
                    area_id: $('#area_id').val(),
                    rescur: type,
                }
                $.getJSON('/admin/newsfeedamount/', formData, function result(data) {
                    //console.log(data);
                    if (type != 'All')
                        type = type;
                    else
                        type= "All Users";
                    var listitems = '<th>The Amount Of News Feeds Sent To  ' + type + ' In ' + data.place + ' is : ' + data.amount + '</th>';
                    $('#newsamount').html(listitems);

                });
            }
        });
    });

</script>
@endsection

