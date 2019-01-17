@extends('@admin::layout.dashboard')

@section('current-page') {{ $entity->translate('page.header') }} @endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('@admin::dashboard.name') }}</a></li>
    <li class="breadcrumb-item active"><a href="javascript:">{{ $entity->translate('title.index') }}</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mg-t-0">
                <div class="card-title">
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-sm-12"><!-- empty --></div>
                        <div class="col-lg-4 col-md-6 col-sm-12 crud-actions">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            @foreach($fields as $field)
                            @spaceless
                            <th
                                @if(!empty($field->getTotalWidth()))
                                    width="{{ $field->getTotalWidth() }}"
                                @endif>{{ $field->getLabel('label') }}</th>
                            @endspaceless
                            @endforeach
                            <th class="crud-actions" width="100">
                                <ul>
                                    <li><a href="{{ route('admin.crud.create', [ 'entity' => $entity->getShortName() ]) }}">{{ trans('@admin::dashboard.general.create') }}</a></li>
                                </ul>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($records as $record)
                            <tr>
                                @foreach($fields as $field)
                                    @if($record->getField($field->getName())->getView() !== null)
                                        <td>{!! $record->getField($field->getName())->render() !!}</td>
                                    @else
                                        <td>{{ $record->getField($field->getName())->getValue() }}</td>
                                    @endif
                                @endforeach
                                <td class="crud-actions">
                                   <ul>
                                       <li><a href="{{ route('admin.crud.delete', [
                                           'entity' => $entity->getShortName(),
                                           'id' => $record->id ?? -1
                                       ]) }}" class="action-delete"><i class="fa fa-remove"></i></a></li>
                                       <li><a href="{{ route('admin.crud.edit', [
                                           'entity' => $entity->getShortName(),
                                           'id' => $record->id ?? -1
                                       ]) }}"><i class="fa fa-pencil"></i></a></li>
                                   </ul>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div style="margin-top: 15px; float: right">
                        {!! $records->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
