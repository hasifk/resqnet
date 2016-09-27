
<div class="col-xs-12 col-md-12">

    <div class="row">
        <div class="col-xs-12 col-md-3 btn-group">
            <label for="office_life" class="control-label">Country <i><font color="red" size="3">*</font></i></label>
            <select name="country_id" id="country_id" class="form-control selectpicker">
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
            <label for="office_life" class="control-label">State <i>(optional)</i></label>
            <select name="state_id" id="state_id" class="form-control">
                <option value="">Please Select</option>

            </select>
        </div>
        <div class="col-xs-12 col-md-2 btn-group">
            <label for="office_life" class="control-label">City <i>(optional)</i></label>
            <select name="area_id" id="area_id" class="form-control">
                <option value="">Please Select</option>

            </select>
        </div>
        <div class="col-xs-12 col-md-2 btn-group">
            <label for="office_life" class="control-label">User Type <i><font color="red" size="3">*</font></i></label>
            <select name="rescur" id="rescur" class="form-control">
                <option value="All">All</option>
                <option value="Rescuer">Rescuer</option>
                <option value="Rescuee">Rescuee</option>
            </select>
        </div>
        <div class="col-xs-12 col-md-2 btn-group m-t-25">
            <label for="office_life" class="control-label"></label>

            <button class="mnotification_delete btn btn-primary" id="search">Search</button>


        </div>
    </div>

</div>
<div class="clearfix"></div>
