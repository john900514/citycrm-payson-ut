<div class="custom-backpack-widget">
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    <text-with-file-upload
        type="text"
        name="{{ $field['name'] }}"
        value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
    ></text-with-file-upload>
</div>


@push('crud_fields_scripts')

@endpush
