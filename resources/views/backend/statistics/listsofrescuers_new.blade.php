<div class="row col-xs-12 col-sm-12 col-md-12 btn-group">
    <center>
        <?php echo $lists->links(); ?>
    </center>
</div>

<table class="table table-striped table-bordered table-hover" id="list">
    <thead>
        <tr>
            <th>No</th>
            <th>Users</th>
            <th>List Of ResQuers<br>Availabel</th>
            <th>Emergency Contacts<br>Lists</th>
            <th>Tagged ResQuer</th>
            <th>ResQuer Response <br>(H:M:S) </th>
            <th>Finished <br>(H:M:S) </th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($lists)) {
            print_r($lists);
            $f = 1;
            foreach ($lists as $list):
                ?>
                <tr><th>{{$f++}}</th>
                    <td>

                        <a href="{{route('admin.access.user.shows',$list->rescuee_id)}}"> {{ $list->rescuee_details->firstname.' '.$list->rescuee_details->lastname }} </a>

                    </td>
                    <td>
                        <table> <?php
                            if (!empty($list->rescuers_details)):
                                //$resccuer_id = json_decode($list->rescuers_ids)
                                ?>
                                @foreach($list->rescuers_details as $resid)
                                <tr>
                                    <td>
                                        <a href="{{route('admin.access.user.shows',$resid->id)}}">{{ $resid->firstname.' '.$resid->lastname }}</a>
                                    </td>
                                </tr>
                                @endforeach
                                <?php
                            else:
                                echo "No Rescuers Found";
                            endif;
                            ?>
                        </table>
                    </td>
                    <td>
                        <table> <?php
                            if (!empty($list->emergency_details)):
                                //$resccuer_id = json_decode($list->rescuers_ids)
                                ?>
                                @foreach($list->emergency_details as $resid)
                                <tr>
                                    <td>
                                        <a href="{{route('admin.access.user.shows',$resid->id)}}">{{ $resid->firstname.' '.$resid->lastname }}</a>
                                    </td>
                                </tr>
                                @endforeach
                                <?php
                            else:
                                echo "No Contacts";
                            endif;
                            ?>
                        </table>
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
                </tr>
                <?php
            endforeach;
        }
        else {
            ?>
            <tr><th>No Panic Signals</th></tr>
            <?php
        }
        ?>
    </tbody>
</table>
<div class="row col-xs-12 col-sm-12 col-md-12 btn-group ">
    <center>
        <?php echo $lists->links(); ?>
    </center>
</div>