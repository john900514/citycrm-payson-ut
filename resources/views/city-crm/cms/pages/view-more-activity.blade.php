@extends('backpack::layout')

@section('before_scripts')
    <style>
        @media screen and (min-width: 1000px) {
            .inner {
                margin: 2.5% 5%;
            }

            li {
                list-style-type: none;
            }
        }
    </style>
@endsection

@section('header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? 'View more '.$crud->entity_name.' details' !!}.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
            <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
            <li class="active">{{ trans('backpack::crud.edit') }}</li>
        </ol>
    </section>
@endsection

@section('content')
    @if ($crud->hasAccess('list'))
        <a href="{{ url()->previous() }}" id="view-more-breadcrumb" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a>
    @endif

    <div class="row m-t-20">
        <div class="inner">
            <cms-visitor-activity
                api-url="/api"
                :record="{{ !empty($entry) ? json_encode($entry->toArray()) : '' }}"
                :maps-api-key="'{!! env('GOOGLE_MAPS_API_KEY') !!}'"
            ></cms-visitor-activity>
        </div>
    </div>
@endsection
