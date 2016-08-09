<div id="search_result">
    <div class="box-body">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            @if(count($notification)>0)
            @foreach($notification as $value)
            <?php $info = strip_tags($value->notification); ?>
            <div class="panel panel-default" id="removal">
                <div class="panel-heading" role="tab" id="heading_{{$value->id}}">
                    <h4 class="panel-title">
                        <span class="tools pull-left"><input type="checkbox" class="checkbox" name="check[]" value="{{$value->id}}" id="{{$value->id}}"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{$value->id}}" aria-expanded="false" aria-controls="collapse_{{$value->id}}">
                            <span class="text"> {!!Str::limit($info,50)!!}</span>
                        </a>
                        <!-- General tools such as edit or delete-->
                        <span class="tools pull-right">
                            <i class="notification_delete fa fa-trash-o" id="{{$value->id}}"></i>
                        </span>
                    </h4>
                </div>
                <div id="collapse_{{$value->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{$value->id}}">
                    <div class="panel-body">
                        {!!$value->notification!!}
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading">
                    <h4 class="panel-title">
                        <span class="text">No Notifications</span>
                    </h4>
                </div>
            </div>
            @endif
        </div>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix no-border">
        @if(count($notification)>0)
        <button class="mnotification_delete btn btn-primary" >Delete</button>
        @endif

    </div>
</div><!-- /.box -->
</div>