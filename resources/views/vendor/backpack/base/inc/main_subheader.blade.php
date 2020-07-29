<div class="c-subheader justify-content-between px-3">

    <ol class="breadcrumb border-0 m-0 px-0 px-md-3">
        <li class="breadcrumb-item">AnchorCMS</li>
        <!-- @todo - Make this the Active Client -->
        <li class="breadcrumb-item"><a href="dashboard">{!! backpack_user()->getActiveClient() !!}</a></li>

        <!-- @todo - Make this the for bread crumb loop  -->
        @switch(request()->route()->uri())
            @case('dashboard')
            <li class="breadcrumb-item active">Dashboard</li>
            @break

            @case('push-notifications')
            <li class="breadcrumb-item active">Push Notifications</li>
            @break

            @default
            <li class="breadcrumb-item"><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
            <li class="breadcrumb-item active">{{ $active_bc }}</li>
        @endswitch
    </ol>

    <!-- @todo - do something REALLY awesome with this! -->
    <div class="c-subheader-nav d-md-down-none mfe-2">
{{--        <a class="c-subheader-nav-link" href="#">--}}
{{--            <i class="fad fa-chart-line c-icon" style="margin-right: 5%;"></i>  Dashboard--}}
{{--        </a>--}}
    </div>
</div>
