<div class="add_menu_item_wrapper vue-app" data-id="{{ $id ?? $name }}">
    <add-menu-item :id="'{{ $id ?? $name }}'"></add-menu-item>
</div>
@push('scripts-before')
    <script src="{{ asset('arudkovskiy/admin/js/lib/select2/select2.full.min.js') }}"></script>
@endpush