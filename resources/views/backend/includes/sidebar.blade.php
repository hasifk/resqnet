<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! access()->user()->picture !!}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>{!! access()->user()->name !!}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('strings.backend.general.status.online') }}</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{ trans('strings.backend.general.search_placeholder') }}"/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        @role('Administrator')
        <ul class="sidebar-menu">
            <li class="header">{{ trans('menus.backend.sidebar.general') }}</li>

            <!-- Optionally, you can add icons to the links -->
            <li class="{{ Active::pattern('admin/dashboard') }}">
                <a href="{!! route('admin.dashboard') !!}"><span>{{ trans('menus.backend.sidebar.dashboard') }}</span></a>
            </li>
            @permission('view-access-management')
            <li class="{{ Active::pattern('admin/access/*') }}">
                <a href="{!!url('admin/access/users')!!}"><span>{{ trans('menus.backend.access.title') }}</span></a>
            </li>
            @endauth
            <li class="{{ Active::pattern('admin/newsfeed/*') }}">
                <a href="{!!url('admin/newsfeeds')!!}"><span>News Feeds</span></a>
            </li>
            <li class="{{ Active::pattern('admin/rescure_departments/*') }}">
                <a href="{{route('backend.admin.rescure_departments')}}"><span>Department</span></a>
            </li>
            <li class="{{ Active::pattern('admin/usergroup/*') }}">
                <a href="{{route('user.groups.viewgroups')}}"><span>User Groups</span></a>
            </li>
            <li class="{{ Active::pattern('admin/department/*') }}">
                <a href="{{route('backend.admin.rescue_operations')}}"><span>Operations</span></a>
            </li>
            <li class="{{ Active::pattern('admin/notifications/*') }}">
                <a href="{{route('backend.admin.notifications')}}"><span>Notifications</span></a>
            <li class="{{ Active::pattern('admin/statistics/*') }} treeview">
                <a href="#"><span>{{ trans('menus.backend.statistics.main') }}</span>
                    <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu {{ Active::pattern('admin/statistics*', 'menu-open') }}" style="display: none; {{ Active::pattern('admin/log-viewer*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/usersamount') }}">
                        <a href="{{route('backend.admin.amountofusers')}}">Amount of Users</a>
                    </li>
                    <li class="{{ Active::pattern('admin/rescuersamount') }}">
                        <a href="{{route('admin.statistics.amountofrescuers')}}">Amount of Rescuers</a>
                    </li>
                    <li class="{{ Active::pattern('admin/panicsignalamount') }}">
                        <a href="{{route('admin.statistics.amountofpanicsignals')}}">Amount of Panic Signals</a>
                    </li>
                    <li class="{{ Active::pattern('admin/panicrescuers') }}">
                        <a href="{{route('admin.statistics.listsofrescuers')}}">Panic Signal Statistics</a>
                    </li>
                    <li class="{{ Active::pattern('admin/newsfeedsamount') }}">
                        <a href="{{route('admin.statistics.amountofnewsfeeds')}}">Amount of Newsfeeds</a>
                    </li>
                    <li class="{{ Active::pattern('admin/usersaccess') }}">
                        <a href="#">App accessing users</a>
                    </li>

                </ul>
            </li>
            <li class="{{ Active::pattern('admin/log-viewer*') }} treeview">
                <a href="#">
                    <span>{{ trans('menus.backend.log-viewer.main') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ Active::pattern('admin/log-viewer*', 'menu-open') }}" style="display: none; {{ Active::pattern('admin/log-viewer*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/log-viewer') }}">
                        <a href="{!! url('admin/log-viewer') !!}">{{ trans('menus.backend.log-viewer.dashboard') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/log-viewer/logs') }}">
                        <a href="{!! url('admin/log-viewer/logs') !!}">{{ trans('menus.backend.log-viewer.logs') }}</a>
                    </li>
                </ul>
            </li>
        </ul><!-- /.sidebar-menu -->
        @endauth
    </section>
    <!-- /.sidebar -->
</aside>