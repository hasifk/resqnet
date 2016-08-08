
<div class="pull-left" style="margin-bottom:10px">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Departments <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{route('backend.admin.department_create')}}" id="cms_create">Create New</a></li>

            <li class="divider"></li>

            @foreach($category as $value)
            <?php
            if (in_array($value->id, ['3', '4', '5'])) {
                ?>
                <li>
                    <a href="{{route('backend.admin.notifications').'/'.$value->id}}">{{ $value->category }}</a>
                </li>
                <li class="divider"></li>
            <?php } ?>
            @endforeach
        </ul>
    </div>
    <div class="btn-group">
        <label for="office_life" class="col-md-4 control-label">Country</label>
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

    <div class="btn-group">
        <label for="office_life" class="col-md-4 control-label">State</label>
        <select name="state_id" id="state_id" class="form-control">
            <option value="">Please select</option>
            
        </select>
    </div>
    <div class="btn-group">
        <label for="office_life" class="col-md-4 control-label">City</label>
        <select name="area_id" id="area_id" class="form-control">
            <option value="">Please select</option>
            
        </select>
    </div>
    <div class="btn-group">
        <label for="office_life" class="col-md-4 control-label"></label>
                   
       <button class="mnotification_delete btn btn-primary" >Search</button>
                   
                    
    </div>
</div>

<div class="clearfix"></div>
