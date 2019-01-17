<div class="form-group">
    <label class="form-control-label">{{ $label  }}</label>
    @spaceless
    <textarea class="form-control"
           name="{{ $name }}"
    @foreach($attributes as $key => $attribute)
        {{ $key }}="{{ $attribute }}"
    @endforeach
    style="height: unset !important;">{{ $value or '' }}</textarea>
    @endspaceless
</div>