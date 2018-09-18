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
                    @can('index', \App\Models\Wrestler::class)
                        <li class="site-menu-item has-sub">
                            <a href="javascript:void(0)">
                                <i class="site-menu-icon icon fa-group" aria-hidden="true"></i>
                                <span class="site-menu-title">Wrestlers</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <ul class="site-menu-sub">
                                @can('create', App\Models\Wrestler::class)
                                    <li class="site-menu-item">
                                        <a href="{{ route('wrestlers.create') }}"><span class="site-menu-title">Create Wrestler</span></a>
                                    </li>
                                @endcan
                                <li class="site-menu-item">
                                    <a href="{{ route('active-wrestlers.index') }}"><span class="site-menu-title">Active</span></a>
                                </li>
                                <li class="site-menu-item">
                                    <a href="{{ route('inactive-wrestlers.index') }}"><span class="site-menu-title">Inactive</span></a>
                                </li>
                                <li class="site-menu-item">
                                    <a href="{{ route('retired-wrestlers.index') }}"><span class="site-menu-title">Retired</span></a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can('index', \App\Models\Event::class)
                        <li class="site-menu-item has-sub">
                            <a href="javascript:void(0)">
                                <i class="site-menu-icon icon fa-calendar" aria-hidden="true"></i>
                                <span class="site-menu-title">Events</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <ul class="site-menu-sub">
                                @can('create', App\Models\Event::class)
                                    <li class="site-menu-item">
                                        <a href="{{ route('events.create') }}"><span class="site-menu-title">Schedule Event</span></a>
                                    </li>
                                @endcan
                                <li class="site-menu-item">
                                    <a href="{{ route('scheduled-events.index') }}"><span class="site-menu-title">Scheduled</span></a>
                                </li>
                                <li class="site-menu-item">
                                    <a href="{{ route('past-events.index') }}"><span class="site-menu-title">Past</span></a>
                                </li>
                                <li class="site-menu-item">
                                    <a href="{{ route('archived-events.index') }}"><span class="site-menu-title">Archived</span></a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can('index', \App\Models\Title::class)
                        <li class="site-menu-item">
                            <a href="javascript:void(0)">
                                <i class="site-menu-icon icon fa-trophy" aria-hidden="true"></i>
                                <span class="site-menu-title">Titles</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <ul class="site-menu-sub">
                                @can('create', App\Models\Title::class)
                                    <li class="site-menu-item">
                                        <a href="{{ route('titles.create') }}"><span class="site-menu-title">Create Title</span></a>
                                    </li>
                                @endcan
                                <li class="site-menu-item">
                                    <a href="{{ route('active-titles.index') }}"><span class="site-menu-title">Active</span></a>
                                </li>
                                <li class="site-menu-item">
                                    <a href="{{ route('retired-titles.index') }}"><span class="site-menu-title">Retired</span></a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can('index', \App\Models\Stipulation::class)
                        <li class="site-menu-item">
                            <a href="javascript:void(0)">
                                <i class="site-menu-icon icon fa-gavel" aria-hidden="true"></i>
                                <span class="site-menu-title">Stipulations</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <ul class="site-menu-sub">
                                @can('create', App\Models\Stipulation::class)
                                    <li class="site-menu-item">
                                        <a href="{{ route('stipulations.create') }}"><span class="site-menu-title">Create Stipulation</span></a>
                                    </li>
                                @endcan
                                <li class="site-menu-item">
                                    <a href="{{ route('stipulations.index') }}"><span class="site-menu-title">View All</span></a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can('index', \App\Models\Venue::class)
                        <li class="site-menu-item">
                            <a href="javascript:void(0)">
                                <i class="site-menu-icon icon fa-building" aria-hidden="true"></i>
                                <span class="site-menu-title">Venues</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <ul class="site-menu-sub">
                                @can('create', App\Models\Venue::class)
                                    <li class="site-menu-item">
                                        <a href="{{ route('venues.create') }}"><span class="site-menu-title">Create Venue</span></a>
                                    </li>
                                @endcan
                                <li class="site-menu-item">
                                    <a href="{{ route('venues.index') }}"><span class="site-menu-title">View All</span></a>
                                </li>
                            </ul>
                        </li>
                    @endcan
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