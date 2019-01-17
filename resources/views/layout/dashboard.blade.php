<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('arudkovskiy/admin/images/favicon.png') }}">
    <title>{{ trans($title) }}</title>
    <link href="{{ asset('arudkovskiy/admin/css/lib/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('arudkovskiy/admin/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('arudkovskiy/admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('arudkovskiy/admin/css/custom.css') }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <meta name="csrf_token" content="{{ csrf_token() }}" />

    @stack('styles')
</head>

<body class="fix-header fix-sidebar" id="vue-app">
<div id="main-wrapper">
    <div class="header">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <div class="navbar-header">
                <div style="height: 60px;"></div>
            </div>
            <div class="navbar-collapse">
                <ul class="navbar-nav mr-auto mt-md-0">
                </ul>
                <ul class="navbar-nav my-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $administrator->full_name }}</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="dropdown-user">
                                <li><a href="{{ route('admin.sign_out') }}">Вийти</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="left-sidebar">
        <div class="scroll-sidebar">
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="nav-devider"></li>
                    <li class="nav-label">Объекты</li>
                    @foreach($admin->getEntities('default') as $entity)
                    <li>
                        <a href="{{ route('admin.crud.index', [ 'entity' => $entity->getShortName() ]) }}"><i class="{{ $entity->getIcon() }}"></i><span class="hide-menu">{{ $entity->getTranslations()['sidebar'] }}</span></a>
                    </li>
                    @endforeach
                    <li class="nav-label">Система</li>
                    @foreach($admin->getEntities('system') as $entity)
                    <li>
                        <a href="{{ route('admin.crud.index', [ 'entity' => $entity->getShortName() ]) }}"><i class="{{ $entity->getIcon() }}"></i><span class="hide-menu">{{ $entity->getTranslations()['sidebar'] }}</span></a>
                    </li>
                    @endforeach
                    <li><a href="{{ route('admin.media.index') }}"><i class="fa fa-video-camera"></i><span class="hide-menu">@lang('@admin::dashboard.menu.media')</span></a></li>
                    <li><a href="{{ route('admin.files.index') }}"><i class="fa fa-folder"></i><span class="hide-menu">@lang('@admin::dashboard.menu.files')</span></a></li>
                    <li><a href="{{ route('admin.config.index') }}"><i class="fa fa-cogs"></i><span class="hide-menu">@lang('@admin::dashboard.menu.config')</span></a></li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-primary">{{ $title }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    @yield('breadcrumbs')
                </ol>
            </div>
        </div>
        <div class="container-fluid">
            @if(session('messages'))
                <div class="flash-alerts mg-b-15">
                    @foreach(session()->get('messages') as $message)
                        <div class="alert alert-light @if(!$loop->last) mg-b-15 @endif">
                            {{ $message }}
                        </div>
                    @endforeach
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>

<div class="popup-container hidden">
    <div class="popup-content-container popup-large">
        <div class="popup-header">
            <div class="popup-header-label"></div>
            <div class="popup-close"><i class="fa fa-close"></i></div>
        </div>
        <div class="popup-content"></div>
    </div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/js-beautify/1.8.0-rc5/beautify.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/js-beautify/1.8.0-rc5/beautify-css.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/js-beautify/1.8.0-rc5/beautify-html.min.js"></script>

<script src="{{ asset('arudkovskiy/admin/js/lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('arudkovskiy/admin/js/lib/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('arudkovskiy/admin/js/lib/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('arudkovskiy/admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>

@stack('scripts-before')

<script src="{{ asset('arudkovskiy/admin/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('arudkovskiy/admin/js/custom.min.js') }}"></script>
<script src="{{ asset('arudkovskiy/admin/js/admin.bundle.js') }}"></script>

@stack('scripts')

</body>

</html>