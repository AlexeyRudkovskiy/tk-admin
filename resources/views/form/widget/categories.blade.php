<div class="form-group">
    <label class="form-control-label">{{ $label }}</label>
    <div class="categories-list">
        @foreach($categories as $category)
        <div class="form-check">
            @spaceless
            <input class="form-check-input"
                   name="{{ $name }}[]"
                   type="checkbox"
                   value="{{ $category->id }}"
                   id="{{ $name }}_{{ $category->id }}"
                   @if(in_array($category->id, $currentCategories)) checked @endif>
            <label class="form-check-label" for="{{ $name }}_{{ $category->id }}">
                {{ $category->name }}
            </label>
            @endspaceless
        </div>
        @endforeach
    </div>
</div>