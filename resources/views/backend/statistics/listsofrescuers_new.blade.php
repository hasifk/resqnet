
        <?php
        $f = 1;
        foreach ($lists as $list):
            ?>
            <tr><td>{{$f++}}</td>
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
                    <?php if (!empty($list->tagged)): ?>
                        <a href="{{route('admin.access.user.shows',$list->tagged->id)}}">
                            {{ $list->tagged->firstname.' '.$list->tagged->lastname }}</a>
                        <?php
                    else:
                        echo "No Rescuer Tagged";
                    endif;
                    ?> 
                </td>
                <td> @if(!empty($list->panicresponse)){{ $list->panicresponse}} @else No Rescuer Tagged @endif </td>
                <td> @if(!empty($list->rescuerresponse)){{ $list->rescuerresponse}} @else No Rescuer Tagged @endif </td>
                <td>{{$list->created_at}}</td>
            </tr>
            <?php
        endforeach;
        ?>