@spaceless
<a href="{{ route('admin.crud.toggle_boolean', [ 'entity' => $entity_name, 'id' => $record_id, 'field' => $name ]) }}" class="toggle-using-ajax">
    @if($checked)
        @lang('@admin::dashboard.general.yes')
    @else
        @lang('@admin::dashboard.general.no')
    @endif
</a>
@endspaceless