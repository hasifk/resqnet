

<div class="col-xs-12 col-md-12">
    <div class="row">

        <div class="col-xs-12 col-md-2 btn-group m-t-25">
            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Notifications <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{route('backend.admin.notificationcreate')}}" id="cms_create">Create New</a></li>

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
        <div class="col-xs-12 col-md-2 btn-group">
            <label for="office_life" class="col-md-4 control-label">Country<i><font color="red" size="3">*</font></i></label>
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

        <div class="col-xs-12 col-md-3 btn-group">
            <label for="office_life" class="control-label">State</label>
            <select data-placeholder="Choose a State..." name="state_id" id="state_id" class="form-control chosen-select">    
                <option value="">Please Select</option>

            </select>
        </div>
        <div class="col-xs-12 col-md-3 btn-group">
            <label for="office_life" class="control-label">City</label>
             <select data-placeholder="Choose a City..." name="area_id" id="area_id" class="form-control chosen-select">
                <option value="">Please Select</option>
            </select>
        </div>
        <div class="col-xs-12 col-md-2 btn-group m-t-25">
            <label for="office_life" class="control-label"></label>

            <button class="mnotification_delete btn btn-primary" id="search">Search</button>


        </div>
    </div>
</div>

<div class="clearfix"></div>
