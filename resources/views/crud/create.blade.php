@extends('@admin::layout.dashboard')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('@admin::dashboard.name') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.crud.index', [ 'entity' => $entity_name ]) }}">{{ $entity->translate('title.index') }}</a></li>
    <li class="breadcrumb-item active"><a href="javascript:">{{ $entity->translate('title.create') }}</a></li>
@endsection
@section('current-page')
    {{ $entity->translate('title.create') }}
@endsection()

@section('content')

    <form class="row" method="post" action="{{ route('admin.crud.save', [ 'entity' => $entity_name ]) }}">
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
