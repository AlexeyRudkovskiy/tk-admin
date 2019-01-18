<div class="form-group">
    <label for="select__{{ $name }}">{{ $label }}</label>
    <select class="form-control" id="select__{{ $name }}" name="{{ $name }}" @if(isset($select2)) data-use-select2="{{ route('admin.select2', [ 'entity' => $config['entity'], 'id' => $foreign_key, 'value' => $foreign_field ]) }}" @endif>
        @spaceless
        @if($select2_value !== null && $select2_text !== null)
            <option value="{{ $select2_value }}" selected>{{ $select2_text }}</option>
        @endif
        @foreach($relation_data as $item)
            @if($item->id === $select2_value)
                @continue
            @endif
            <option
                    value="{{ $item->{$foreign_key} }}"
                    @if($item->{$foreign_key} == $value)
                        selected
                    @endif
            >
                {{ $item->{$foreign_field} }}
            </option>
        @endforeach
        @endspaceless
    </select>
</div>
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script>
        $(function () {
            $('[data-use-select2]').each(function (index, element) {
                $(element).select2({
                    ajax: {
                        url: $(element).data('use-select2'),
                        dataType: 'json'
                        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                    }
                });
            });
            @if($select2_value !== null && $select2_text !== null)
                const value = {{ $select2_value }};
                const text = '{{ $select2_text }}';

                $('#select__{{ $name }}').val(value).trigger('change');
            @endif
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
@endpush