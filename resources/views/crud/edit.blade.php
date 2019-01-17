@extends('@admin::layout.dashboard')

@section('current-page') {{ $entity->translate('title.edit') }} @endsection
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('@admin::dashboard.name') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.crud.index', [ 'entity' => $entity_name ]) }}">{{ $entity->translate('title.index') }}</a></li>
    <li class="breadcrumb-item active"><a href="javascript:">{{ $entity->translate('title.edit') }}</a></li>
@endsection

@section('content')

    <form class="row" method="post" action="{{ route('admin.crud.update', [ 'entity' => $entity_name, 'id' => $entity->getId() ]) }}">
        {{ csrf_field() }}

        <div class="col-lg-8 col-md-8 col-sm-12">
            <div class="entity-fields-wrapper">
                @foreach($fields->get('content') as $field)
                    <div class="entity-field">
                        {!! $field->renderEditable() !!}
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="entity-fields-wrapper">
                @foreach($fields->get('sidebar') as $field)
                    <div class="entity-field">
                        {!! $field->renderEditable() !!}
                    </div>
                @endforeach
            </div>
        </div>
    </form>

@endsection
