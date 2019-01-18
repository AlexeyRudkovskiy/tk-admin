<div class="form-group">
    <label class="form-control-label">{{ $label  }}</label>
    @spaceless
    <input class="form-control" type="{{ $type }}"
        name="{{ $name }}"
        @foreach($attributes as $key => $attribute)
            {{ $key }}="{{ $attribute }}"
        @endforeach
        @if(!empty($value)) value="{{ $value }}" @endif
        @if($type === 'checkbox' && isset($checked) && $checked) checked @endif
    />
    @endspaceless
</div>