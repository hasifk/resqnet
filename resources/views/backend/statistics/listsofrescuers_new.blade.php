<div class="row col-xs-12 col-sm-12 col-md-12 btn-group">
    <center>
        <?php echo $lists->links(); ?>
    </center>
</div>

<table class="table table-striped table-bordered table-hover" id="list">
    <thead>
        <tr>
            <th><input type="checkbox" id="selectall"/></th>
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