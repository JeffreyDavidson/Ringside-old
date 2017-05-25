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
                        <a href="{{ route('wrestlers.index') }}">
                            <i class="site-menu-icon icon fa-group" aria-hidden="true"></i>
                            <span class="site-menu-title">Wrestlers</span>
                        </a>
                    </li>
                    <li class="site-menu-item has-sub">
                        <a href="{{ route('events.index') }}">
                            <i class="site-menu-icon icon fa-calendar" aria-hidden="true"></i>
                            <span class="site-menu-title">Events</span>
                        </a>
                    </li>
                    <li class="site-menu-item has-sub">
                        <a href="{{ route('titles.index') }}">
                            <i class="site-menu-icon icon fa-trophy" aria-hidden="true"></i>
                            <span class="site-menu-title">Titles</span>
                        </a>
                    </li>
                    <li class="site-menu-item has-sub">
                        <a href="{{ route('stipulations.index') }}">
                            <i class="site-menu-icon icon fa-gavel" aria-hidden="true"></i>
                            <span class="site-menu-title">Stipulations</span>
                        </a>
                    </li>
                    <li class="site-menu-item has-sub">
                        <a href="{{ route('venues.index') }}">
                            <i class="site-menu-icon icon fa-building" aria-hidden="true"></i>
                            <span class="site-menu-title">Venues</span>
                        </a>
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