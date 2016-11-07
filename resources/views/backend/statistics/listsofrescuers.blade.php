@extends ('backend.layouts.master')
@section('page-header')
<h1>
    Statistics
</h1>
@endsection

@section('content')

<h3>Panic Signals And Tagged ResQuers</h3>

<section class="content">
    <div class="row">
        <div class="row">
            <div class="col-md-12">
                <!-- TO DO List -->
                <div class="box box-primary">
                    <div class="col-xs-12 m-t-20">

                        <div class="col-xs-12 col-sm-6 col-md-3 btn-group">
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
                        <div class="col-xs-12 col-sm-6 col-md-3 btn-group">
                            <label for="office_life" class="control-label">State <i>(optional)</i></label>
                            <select data-placeholder="Choose a State..." name="state_id" id="state_id" class="form-control chosen-select"> 
                                <option value="">Please Select</option>

                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3 btn-group">
                            <label for="office_life" class="control-label">City <i>(optional)</i></label>
                            <select data-placeholder="Choose a City..." name="area_id" id="area_id" class="form-control chosen-select">
                                <option value="">Please Select</option>

                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-3 btn-group">
                            <label for="office_life" class="control-label">Rescuer Type</label>
                            <select name="rescuertype" id="rescuertype" class="form-control">
                                <option value="All">All</option>
                                @foreach($rescuertype as $type)
                                <option
                                    value="{{ $type->type }}"
                                    >
                                    {{ $type->type }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 m-t-20">

                        <div class="col-xs-12 col-sm-3 col-md-3 btn-group">
                            <label for="office_life" class="control-label">Category</label>
                            <select name="category" id="category" class="form-control">
                                <option value="All">All</option>
                                <option value="Medical">Medical</option>
                                <option value="Crime">Crime</option>
                            </select>
                        </div>

                        <div class="col-xs-12 col-sm-3 col-md-3 btn-group input-append date">
                            <label for="office_life" class="control-label">Date <i>(optional)</i></label>
                            <input data-format="dd/MM/yyyy" type="text" class="form-control" id="datepicker"></input>
                        </div>

                        <div class="col-xs-12 col-sm-3 col-md-2 btn-group m-t-25">
                            <label for="office_life" class="control-label"></label>
                            <button class=" btn btn-primary" id="search">Search</button>
                        </div>


                    </div><!-- /.box -->
                </div>
                <div class="col-md-12 m-t-25">
                    <div id="lists">
                        <div class="row col-xs-12 col-sm-12 col-md-12 btn-group">
                            <center>
                                <?php
                                echo $lists->links();
                                ?>
                            </center>
                        </div>

                        <table class="table table-striped table-bordered table-hover" id="list">
                            <thead>
                                <tr><th><input type="checkbox" id="selectall"/></th>
                                    <th>No</th>
                                    <th>Users</th>
                                    <th>Tagged ResQuer</th>
                                    <th>ResQuer Response <br>(H:M:S) </th>
                                    <th>Finished <br>(H:M:S) </th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($lists) > 0) {
                                    if ($lists->currentPage() > 1)
                                        $f = ($lists->currentPage() - 1) * $lists->perPage() + 1;
                                    else
                                        $f = 1;
                                    foreach ($lists as $list):
                                        ?>
                                        <tr>
                                            <th><input type="checkbox" class="checkbox" name="check[]" value="{{$list->id}}" id="{{$list->id}}"/></th>
                                            <th>{{$f++}}</th>
                                            <td>
                                                <a href="{{route('admin.access.user.shows',$list->rescuee_id)}}"> {{ $list->rescuee_details->firstname.' '.$list->rescuee_details->lastname }} </a>
                                            </td>
                                            <td>
                                                <?php if (!empty($list->tagged)): ?>
                                                    <a href="{{route('admin.access.user.shows',$list->tagged->id)}}">
                                                        {{ $list->tagged->firstname.' '.$list->tagged->lastname }}</a>
                                                    <?php
                                                else:
                                                    echo "No Rescuer Tagged";
                                                endif;
                                                ?> 
                                            </td>
                                            <th> @if(!empty($list->rescuerresponse)){{ $list->rescuerresponse}} @else 00:00:00 @endif </th>
                                            <th> @if(!empty($list->finished)){{ $list->finished}} @else 00:00:00 @endif </th>
                                            <th>{{$list->created_at}}</th>
                                            <th>{!! $list->action_buttons !!}</th>
                                        </tr>
                                        <?php
                                    endforeach;
                                }
                                else {
                                    ?>
                                    <tr><td colspan="8"><font color="red">No Panic Signals</font></td></tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        
                        <div class="row col-xs-12 col-sm-12 col-md-12 btn-group ">
                            @if(count($lists) > 0)
                            <button class="mnotification_delete btn btn-primary" >Delete</button>
                            @endif
                            <center>
                                <?php echo $lists->links(); ?>
                            </center>
                        </div>
                    </div>
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
                var formData = {
                    country_id: $('#country_id').val(),
                    state_id: $('#state_id').val(),
                    area_id: $('#area_id').val(),
                    rescur: $('#rescuertype').val(),
                    category: $('#category').val(),
                    date: $('#datepicker').val(),
                }

//                $.getJSON('/admin/rescuerslists/', formData, function result(data) {
//                    console.log(data);
//
//                });
                $.ajax({
                    type: "get",
                    url: '/admin/rescuerslists/',
                    data: formData,
                    cache: false,
                    success: function (data) {
                        console.log(data);
                        $('#lists').html(data);
                    }
                })
            }
        });

        $('.notification_delete').on('click', function () {
            if (confirm("Are you sure want to delete")) {
                return true;
            }
            return false;
        });
        
        
        $(document).on("click", '.notification_delete,.mnotification_delete', function () {
        var cursel = this;
        var value = new Array();
        var j = 0;
        if (cursel.className.split(' ')[0] == 'notification_delete')
        {
            var ids = cursel.id;
            $(".checkbox").each(function () {
                if ($(this).is(":checked"))
                {
                    $(this).removeAttr("checked");

                }
            });
            $("#selectall").removeAttr("checked");
            $("#" + ids).prop("checked", true);
        }

        if ($(".checkbox:checked").length > 0)
        {
            $(".checkbox:checked").each(function () {
                value[j++] = $(this).val();
            });
            if (confirm("Are sure want to delete"))
            {
                $.ajax({
                    type: "GET",
                    url: '/admin/deletepanic',
                    data: "id=" + value,
                    cache: false,
                    success: function (data) {
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        alert(error);
                    }
                });
            }
        } else
            alert("Please select atleast one")
    });
    $(document).on("click", '#selectall', function () {
        if (this.checked) { // check select status
            $('.checkbox').each(function () { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        } else {
            $('.checkbox').each(function () { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });
        }
    });
    $(document).on("change", '#selectall', function () {
        $(".checkbox").prop('checked', $(this).prop("checked"));
    });

    $(document).on("click", '.checkbox', function () {
        // alert($(".checkbox").length + "----" + $(".checkbox:checked").length);
        if ($(".checkbox").length == $(".checkbox:checked").length) {
            $("#selectall").prop("checked", true);
        } else {
            $("#selectall").removeAttr("checked");
        }
    });
        
        
    });
    $(document).ready(function () {

        $('#datepicker').datepicker({
            format: "dd/mm/yyyy"
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
                rescur: $('#rescuertype').val(),
                category: $('#category').val(),
                date: $('#datepicker').val(),
            }

            $.ajax({
                type: "get",
                url: url,
                data: formData,
                cache: false,
                success: function (data) {
                    console.log(data);
                    $('#lists').html(data);
                }
            })
        });
    });
</script>
@endsection