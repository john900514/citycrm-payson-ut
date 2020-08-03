<div class="custom-backpack-widget">
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    <image-scheduler
        start-date="{!! is_null($entry) ? '' : $entry->schedule_start !!}"
        end-date="{!! is_null($entry) ? '' : $entry->schedule_end !!}"
    ></image-scheduler>
</div>

@push('crud_fields_scripts')

@endpush
