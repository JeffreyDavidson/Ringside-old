<div class="site-menubar">
    <div class="site-menubar-body">
        <div>
            <div>
                <ul class="site-menu" data-plugin="menu">
                    <li class="site-menu-category">General</li>
                    <li class="site-menu-item has-sub">
                        <a href="{{ route('dashboard') }}">
                            <i class="site-menu-icon wb-dashboard" aria-hidden="true"></i>
                            <span class="site-menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="site-menu-category">Administrator</li>
                    <li class="site-menu-item has-sub">
                        <a href="javascript:void(0)">
                            <i class="site-menu-icon icon fa-group" aria-hidden="true"></i>
                            <span class="site-menu-title">Roster</span>
                            <span class="site-menu-arrow"></span>
                        </a>
                        <ul class="site-menu-sub">
                            <li class="site-menu-item has-sub">
                                <a href="javascript:void(0)">
                                    <span class="site-menu-title">Singles</span>
                                    <span class="site-menu-arrow"></span>
                                </a>
                                <ul class="site-menu-sub">
                                    <li class="site-menu-item">
                                        <a class="animsition-link" href="{{ route('wrestlers.active') }}">
                                            <span class="site-menu-title">Active</span>
                                        </a>
                                    </li>
                                    <li class="site-menu-item">
                                        <a class="animsition-link" href="{{ route('wrestlers.inactive') }}">
                                            <span class="site-menu-title">Inactive</span>
                                        </a>
                                    </li>
                                    <li class="site-menu-item">
                                        <a class="animsition-link" href="{{ route('wrestlers.injured') }}">
                                            <span class="site-menu-title">Injured</span>
                                        </a>
                                    </li>
                                    <li class="site-menu-item">
                                        <a class="animsition-link" href="{{ route('wrestlers.suspended') }}">
                                            <span class="site-menu-title">Suspended</span>
                                        </a>
                                    </li>
                                    <li class="site-menu-item">
                                        <a class="animsition-link" href="{{ route('wrestlers.retired') }}">
                                            <span class="site-menu-title">Retired</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="site-menubar-footer">
        <a href="javascript: void(0);" class="fold-show" data-placement="top" data-toggle="tooltip"
           data-original-title="Settings">
            <span class="icon wb-settings" aria-hidden="true"></span>
        </a>
        <a href="javascript: void(0);" data-placement="top" data-toggle="tooltip" data-original-title="Lock">
            <span class="icon wb-eye-close" aria-hidden="true"></span>
        </a>
        <a href="javascript: void(0);" data-placement="top" data-toggle="tooltip" data-original-title="Logout">
            <span class="icon wb-power" aria-hidden="true"></span>
        </a>
    </div>
</div>