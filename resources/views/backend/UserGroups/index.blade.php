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
                            <button class="mnotification_delete btn btn-primary" id="search">Search</button>
                        </div>


                    </div><!-- /.box -->
                </div>
                <div class="col-md-12 m-t-25">
                    <div id="lists">
                        <div class="row col-xs-12 col-sm-12 col-md-12 btn-group">
                            <center>
                                <?php
                                echo $usergroups->links();
                                ?>
                            </center>
                        </div>
                        <table class="table table-striped table-bordered table-hover" id="usergroups">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Group Name</th>
                                    <th>Group Image</th>
                                    <th>Total Members</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($usergroups) > 0) {
                                    if ($usergroups->currentPage() > 1)
                                        $f = ($usergroups->currentPage() - 1) * $usergroups->perPage() + 1;
                                    else
                                        $f = 1;
                                    foreach ($usergroups as $groups):
                                        ?>
                                        <tr><th>{{$f++}}</th>
                                            <td>

                                                <a href="{{route('user.groups.view',$groups->id)}}"> {{ $groups->name }} </a>

                                            </td>
                                            <td>
                                               <img src="{{ $groups->gp_image_src }}" width="25" height="25">
                                            </td>
                                            <td>
                                                {{$groups->amount}}
                                            </td>
                                            <td>
                                                <a href="{{route('admin.access.user.shows',$groups->id)}}"> {{ $groups->created_at }} </a>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                }
                                else {
                                    ?>
                                    <tr><td colspan="8"><font color="red">No groups </font></td></tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="row col-xs-12 col-sm-12 col-md-12 btn-group ">
                            <center>
                                <?php echo $usergroups->links(); ?>
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