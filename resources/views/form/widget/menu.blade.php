<div class="vue-app">
    <menu-builder id="{{ $id ?? $name }}" field-name="{{ $name }}"></menu-builder>
    {{--<menu-field :id="'{{ $id ?? $name }}'" :field-name="'{{ $name }}'"></menu-field>--}}
</div>
@push('scripts-before')
    <script>
        if (typeof window.menus === "undefined") {
            window.menus = {
            };
        }
        var menuObject = {
            items: {!! $items ?? [] !!},
            entities: JSON.parse('{!! $menuable ?? [] !!}')
        };
        window.menus['{{ $id ?? $name }}'] = menuObject;
    </script>
@endpush