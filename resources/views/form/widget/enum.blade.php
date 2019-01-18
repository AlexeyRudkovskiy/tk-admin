<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <select name="{{ $name }}" class="form-control" id="{{ $name }}">
        @foreach($elements as $key => $val)
            <option value="{{ $key }}" @if($key === $value) selected @endif>{{ $val }}</option>
        @endforeach
    </select>
</div>