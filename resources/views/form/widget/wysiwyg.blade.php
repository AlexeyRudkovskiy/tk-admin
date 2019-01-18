<div class="wysiwyg-container" data-name="{{ $name }}">
    <div class="wysiwyg-toolbar"></div>
    <div class="wysiwyg-content" contenteditable>{!! $value ?? '<p><br/></p>' !!}</div>
    <div class="wysiwyg-source-code-editor hidden"></div>
    <div class="wysiwyg-floating-panel hidden"></div>
</div>
@push('scripts-before')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.6.1/Sortable.min.js"></script>
@endpush