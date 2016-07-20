<div style="margin-bottom:10px">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Departments <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">

            <li><a href="{{route('backend.admin.department_create')}}" id="cms_create">Create New</a></li>

            <li class="divider"></li>

            <li>
                <a href="{{route('backend.admin.rescure_departments')}}">All</a>
            </li>


            @foreach($types as $type)
            <li>
                <a href="{{route('backend.admin.rescure_departments').'/'.$type->id}}">{{ $type->type }}</a>
            </li>
            @endforeach


        </ul>
    </div>
</div>
