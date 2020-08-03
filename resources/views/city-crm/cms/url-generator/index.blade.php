@extends('backpack::layout')

@section('before_styles')
<style>
    @media screen {
        .lookup-content {
            height: 100%;
            width: 100%;
        }

        .inner-wrapper {
            height: 100%;
        }
    }

    @media screen and (max-width: 999px) {

    }

    @media screen and (min-width: 1000px) {

    }
</style>
@endsection

@section('header')
<section class="content-header">
    <h1>
        Enrollment Page URL Generator<small>Generate a url for payments.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ backpack_url('dashboard') }}">{{ trans('backpack::base.dashboard') }}</a></li>
        <li class="active">Enrollment Page Url Generator</li>
    </ol>
</section>
@endsection

@section('content')
<div class="lookup-content">
    <div class="inner-wrapper">
        <enrollment-url-generator
            :clubs="{{ json_encode($clubs) }}"
            vanilla-url="{!! env('VANILLA_URL') !!}/enroll"
            new-url="{!! env('APP_URL') !!}/enroll"
        ></enrollment-url-generator>
    </div>
</div>
@endsection
