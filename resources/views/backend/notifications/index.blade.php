@extends ('backend.layouts.master')
@section('content')

<h3><b><center>Manage Notification</center></b></h3>
@include('backend.includes.partials.notifications.header-buttons')
<section class="content">
    <div class="row">
    <div class="row">
        <div class="col-md-12">
            <!-- TO DO List -->
            <div class="box box-primary" >
                <div class="box-header with-border">
                    <h3 class="box-title"><input type="checkbox" id="selectall"/> Notifications</h3>
                    <div class="box-tools pull-right">
                            <?php echo $notification->links(); ?>
                    </div>
                    <a href="{{route('backend.admin.notificationcreate')}}"> <button class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button></a>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        @if(count($notification)>0)
                        @foreach($notification as $value)
                        <?php $info=strip_tags($value->notification); ?>
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
    </div>
    </div>
    
</section>
@endsection
@section('after-scripts-end')
    <script>
        $(document).ready(function(){
            $('#country_id').on('change', function(){
                $.getJSON('/admin/getstates/'+$(this).val(), function(json){
                    var listitems = '<option value="">Please select</option>';
                    $.each(json,function(key, value)
                    {
                        listitems += '<option value=' + value.id + '>' + value.name + '</option>';
                    });
                    $('#state_id').html(listitems);
                    $('#area_id').html(listitems);
                });
            });
            
            $('#state_id').on('change', function(){
                $.getJSON('/admin/getareas/'+$(this).val(), function(json){
                    var listitems = '<option value="">Please select</option>';
                    $.each(json,function(key, value)
                    {
                        listitems += '<option value=' + value.id + '>' + value.name + '</option>';
                    });
                    $('#area_id').html(listitems);
                });
            });
        });
    
        
    </script>
@endsection