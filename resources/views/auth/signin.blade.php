
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('arudkovskiy/admin/images/favicon.png') }}">
    <title>Авторизація</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('arudkovskiy/admin/css/lib/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('arudkovskiy/admin/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('arudkovskiy/admin/css/style.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fix-header fix-sidebar">
<!-- Preloader - style you can find in spinners.css -->
<!-- Main wrapper  -->
<div id="main-wrapper">

    <div class="unix-login">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-xl-3 col-sm-4">
                    <div class="login-content card">
                        <div class="login-form">
                            <form method="post" action="{{ route('admin.signin') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="username">{{ trans('@admin::dashboard.auth.username') }}</label>
                                    <input type="text"
                                           value="{{ old('username') }}"
                                           name="username"
                                           id="username"
                                           class="form-control"
                                           placeholder="{{ trans('@admin::dashboard.auth.username') }}"
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="password">{{ trans('@admin::dashboard.auth.password') }}</label>
                                    <input type="password"
                                           class="form-control"
                                           value="{{ old('password') }}"
                                           name="password"
                                           id="password"
                                           placeholder="{{ trans('@admin::dashboard.auth.password') }}"
                                    />
                                </div>
                                <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">{{ trans('@admin::dashboard.auth.sign_in') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Wrapper -->
<!-- All Jquery -->
<script src="{{ asset('arudkovskiy/admin/js/lib/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ asset('arudkovskiy/admin/js/lib/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('arudkovskiy/admin/js/lib/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ asset('arudkovskiy/admin/js/jquery.slimscroll.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ asset('arudkovskiy/admin/js/sidebarmenu.js') }}"></script>
<!--stickey kit -->
<script src="{{ asset('arudkovskiy/admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<!--Custom JavaScript -->
</body>

</html>