@extends('@admin::layout.dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('@admin::dashboard.name')</a></li>
    <li class="breadcrumb-item active"><a href="javascript:">@lang('@admin::dashboard.title.config.index')</a></li>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mg-t-0">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.config.save') }}" class="form">
                        {{ csrf_field() }}
                        @foreach($configs as $config)
                            @if($config['type'] == 'entity')
                                <div class="form-group">
                                    <label class="config_{{ $config['name'] }}">{{ $config['label'] }}</label>
                                    @php($repository = new \ARudkovskiy\Admin\Services\Repository($admin->getEntity($config['entity'])))
                                    @php($data = $repository->findAllOrdered($config['order']['by'], $config['order']['type']))
                                    <select name="config[{{ $config['name'] }}]" id="config_{{ $config['name'] }}" class="form-control" value="{{ $default[$config['name']] ?? null }}">
                                        @spaceless
                                        @if($config['allow_empty'])
                                            <option value="">-------------------</option>
                                        @endif
                                        @foreach($data as $item)
                                            @php($value = $item->getField($config['fields']['value'])->getValue())
                                            @php($text = $item->getField($config['fields']['text'])->getValue())
                                            <option
                                                value="{{ $value }}"
                                                @if($default[$config['name']] == $value) selected @endif
                                            >{{ $text }}</option>
                                        @endforeach
                                        @endspaceless
                                    </select>
                                </div>
                            @elseif($config['type'] === 'text')
                                <div class="form-group">
                                    <label for="text">{{ $config['label'] }}</label>
                                    <input type="text" name="config[{{ $config['name'] }}]" id="text" class="form-control" value="{{ $default[$config['name']] }}" />
                                </div>
                            @endif
                        @endforeach
                        <div class="form-group">
                            <input type="submit" value="@lang('@admin::dashboard.general.save')" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection