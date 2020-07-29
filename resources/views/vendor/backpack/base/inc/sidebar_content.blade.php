<li class="c-sidebar-nav-item">
    <a href="{{ backpack_url('dashboard') }}" class="router-link-exact-active c-active c-sidebar-nav-link"><i class="c-sidebar-nav-icon fad fa-tachometer-alt-fast"></i> <span>{{ trans('backpack::base.dashboard') }}</span><span class="badge badge-primary"> NEW! </span></a>
</li>
<li class="c-sidebar-nav-title">Navigation</li>
@if(count($nav_options = \AnchorCMS\MenuOptions::getOptions('sidebar', 'Navigation', $page)) > 0)
    @foreach($nav_options as $nav_option)
        @if(($nav_option->permitted_role == 'any') || \Silber\Bouncer\BouncerFacade::is(backpack_user())->a($nav_option->permitted_role))
            @if((is_null($nav_option->ability)) || backpack_user()->can($nav_option->ability))
                @if($nav_option->active == 1)
                    @if($nav_option->is_submenu == 1)
                        <li class="c-sidebar-nav-dropdown">
                            @if((($nav_option->is_host_user == 1) && (backpack_user()->isHostUser()))
                                || (($nav_option->is_host_user == 0) && (backpack_user()->client_id == $nav_option->client_id))
                                )
                                <a class="c-sidebar-nav-dropdown-toggle" @if(!is_null($nav_option->route))href="{!! $nav_option->route !!}"@endif>
                                    @if(!is_null($nav_option->icon))<i class="{!! $nav_option->icon !!}"></i>@endif{!!  $nav_option->name !!}
                                </a>
                                @if(count($sub_options = \AnchorCMS\MenuOptions::getOptions('sidebar', $nav_option->name)) > 0)
                                    <ul class="c-sidebar-nav-dropdown-items">
                                        @foreach($sub_options as $sub_option)
                                            @if((($sub_option->is_host_user == 1) && (backpack_user()->isHostUser()))
                                            || (($sub_option->is_host_user == 0) && (backpack_user()->client_id == $sub_option->client_id))
                                            )
                                                <li class="c-sidebar-nav-item">
                                                    <a @if(!is_null($sub_option->route))href="{!! $sub_option->route !!}"@endif class="c-sidebar-nav-link" target="_self">
                                                        @if(!is_null($sub_option->icon))<i class="{!! $sub_option->icon !!}"></i>@endif{!!  $sub_option->name !!}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </li>
                    @else
                        @if((($nav_option->is_host_user == 1) && (backpack_user()->isHostUser()))
                        || (($nav_option->is_host_user == 0) && (backpack_user()->client_id == $nav_option->client_id))
                        )
                            <li class="c-sidebar-nav-item">
                                <a @if(!is_null($nav_option->route))href="{!! $nav_option->route !!}"@endif class="c-sidebar-nav-link" target="_self">
                                    @if(!is_null($nav_option->icon))<i class="{!! $nav_option->icon !!}"></i>@endif{!!  $nav_option->name !!}
                                </a>
                            </li>
                        @endif
                    @endif
                @endif
            @endif
        @endif
    @endforeach
    <!--
<li class="c-sidebar-nav-dropdown">
    <a class="c-sidebar-nav-dropdown-toggle" href="#">
        <i class="fad fa-link c-sidebar-nav-icon"></i>Clients
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a href="#/base/breadcrumbs" class="c-sidebar-nav-link" target="_self">AllCommerce</a></li>
        <li class="c-sidebar-nav-item">
            <a href="#/base/cards"       class="c-sidebar-nav-link" target="_self">TruFit Athletic Clubs</a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="#/base/carousels"   class="c-sidebar-nav-link" target="_self">THE Athletic Club</a>
        </li>
    </ul>
</li>
-->
@endif

@if((backpack_user()->isHostUser() && \Silber\Bouncer\BouncerFacade::is(backpack_user())->a('god', 'admin')) || backpack_user()->can('enable-admin-options'))
<li class="c-sidebar-nav-title">Admin</li>
@if(count($nav_options = \AnchorCMS\MenuOptions::getOptions('sidebar', 'Admin', $page)) > 0)
    @foreach($nav_options as $nav_option)
        @if(($nav_option->permitted_role == 'any') || \Silber\Bouncer\BouncerFacade::is(backpack_user())->a($nav_option->permitted_role))
            @if((is_null($nav_option->ability)) || backpack_user()->can($nav_option->ability))
                @if($nav_option->active == 1)
                    @if($nav_option->is_submenu == 1)
                        <li class="c-sidebar-nav-dropdown">
                            @if((($nav_option->is_host_user == 1) && (backpack_user()->isHostUser()))
                                || (($nav_option->is_host_user == 0) && (backpack_user()->client_id == $nav_option->client_id))
                                )
                                <a class="c-sidebar-nav-dropdown-toggle" @if(!is_null($nav_option->route))href="{!! $nav_option->route !!}"@endif>
                                    @if(!is_null($nav_option->icon))<i class="{!! $nav_option->icon !!}"></i>@endif{!!  $nav_option->name !!}
                                </a>
                                @if(count($sub_options = \AnchorCMS\MenuOptions::getOptions('sidebar', $nav_option->name)) > 0)
                                    <ul class="c-sidebar-nav-dropdown-items">
                                        @foreach($sub_options as $sub_option)
                                            @if((($sub_option->is_host_user == 1) && (backpack_user()->isHostUser()))
                                            || (($sub_option->is_host_user == 0) && (backpack_user()->client_id == $sub_option->client_id))
                                            )
                                                <li class="c-sidebar-nav-item">
                                                    <a @if(!is_null($sub_option->route))href="{!! $sub_option->route !!}"@endif class="c-sidebar-nav-link" target="_self">
                                                        @if(!is_null($sub_option->icon))<i class="{!! $sub_option->icon !!}"></i>@endif{!!  $sub_option->name !!}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </li>
                    @else
                        @if((($nav_option->is_host_user == 1) && (backpack_user()->isHostUser()))
                        || (($nav_option->is_host_user == 0) && (backpack_user()->client_id == $nav_option->client_id))
                        )
                            <li class="c-sidebar-nav-item">
                                <a @if(!is_null($nav_option->route))href="{!! $nav_option->route !!}"@endif class="c-sidebar-nav-link" target="_self">
                                    @if(!is_null($nav_option->icon))<i class="{!! $nav_option->icon !!}"></i>@endif{!!  $nav_option->name !!}
                                </a>
                            </li>
                        @endif
                    @endif
                @endif
            @endif
        @endif
    @endforeach
    <!--
<li class="c-sidebar-nav-dropdown">
    <a class="c-sidebar-nav-dropdown-toggle" href="#">
        <i class="fad fa-link c-sidebar-nav-icon"></i>Clients
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a href="#/base/breadcrumbs" class="c-sidebar-nav-link" target="_self">AllCommerce</a></li>
        <li class="c-sidebar-nav-item">
            <a href="#/base/cards"       class="c-sidebar-nav-link" target="_self">TruFit Athletic Clubs</a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="#/base/carousels"   class="c-sidebar-nav-link" target="_self">THE Athletic Club</a>
        </li>
    </ul>
</li>
-->
@endif
@endif
