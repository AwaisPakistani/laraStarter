<div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="index.html"><img src="{{asset('assets/images/logo/logo.png')}}" alt="Logo" srcset=""></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} ">
                            <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-grid-1x2-fill"></i>
                                <span>Administration</span>
                            </a>
                            <ul class="submenu active">
                                <li class="submenu-item ">
                                    <a href="{{ route('admin.sales.index')}}">Sales</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="{{ route('admin.sales.import')}}">Upload Sales in Excel</a>
                                </li>
                            </ul>
                        </li>
                         <li class="sidebar-item {{ request()->routeIs(
                            'admin.users.*',
                            'admin.roles.*',
                            'admin.permissions.*',
                            'admin.modules.*'

                         ) ? 'active' : '' }} has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-gear-fill"></i>
                                <span>Settings</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="{{ route('admin.users.index') }}">Users</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="{{ route('admin.roles.index') }}">Roles</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="{{ route('admin.permissions.index') }}">Permissions</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="{{ route('admin.modules.index') }}">Modules</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
</div>
