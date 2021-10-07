<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>
        Track Your Sales | Log in
    </title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <link href="{!! asset('backend/ezzyr_assets/vendors/base/vendors.bundle.css') !!}" rel="stylesheet" type="text/css" />
    <link href="{!! asset('backend/ezzyr_assets/demo/default/base/style.bundle.css') !!}" rel="stylesheet" type="text/css" />
    <!--end::Base Styles -->
    <link rel="shortcut icon" href="#" />
</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-3" id="m_login" >
        <div class="m-grid__item m-grid__item--fluid    m-login__wrapper">
            <div class="m-login__container">
                <div class="m-login__logo">
                    <a href="#">
                        <h1></h1><img src="{!! asset('backend/ezzyr_assets/app/media/img/logos/trace-sale.png') !!}" width="140" alt="Ezzyr Logo">
                    </a>
                </div>
                <div class="m-login__signin">
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                            {!! $error !!}
                        </div>
                    @endforeach
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            @if($user['profile_pic'] != '')
                                <img src="{{ url('/resources/profile_pic') }}/{{ $user['profile_pic'] }}" width="100%" alt="User Image">
                            @else
                                <img src="{{ url('backend/dist/img/avatar.png') }}" width="100%" alt="User Image">
                            @endif
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <div class="lockRightTop text-xs-center">
                                <h3 style="color:#ffffff;">{{ $user['name'] }}</h3>
                                <span style="color:#ffffff;"> {{ $user['email'] }} </php> </span><br>
                            </div>
                            <div class="lockRightBottom">
                                <span style="color:#afafaf;">Locked</span>
                                {!! Form::open(array()) !!}
                                <form class="form-inline" action="">
                                    <div class="input-group input-group-md">
                                        <input type="hidden" value="{{ $user['email'] }}" name="email" >
                                        <input class="form-control" type="password" placeholder="Password" name="password">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-arrow-right"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <!-- /input-group -->
                                </form>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Page -->
<!--begin::Base Scripts -->
<script src="{!! asset('backend/ezzyr_assets/vendors/base/vendors.bundle.js') !!}" type="text/javascript"></script>
<script src="{!! asset('backend/ezzyr_assets/demo/default/base/scripts.bundle.js') !!}" type="text/javascript"></script>
<!--end::Base Scripts -->

<!-- Metronic 4 js -->
<script src="{!! asset('backend/ezzyr_assets/plugin/backstretch/jquery.backstretch.js') !!}" type="text/javascript"></script>

<!-- Bacground image slide -->
<script>
    $.backstretch([
            "{!! asset('backend/ezzyr_assets/app/media/img//bg/bg-2.jpg') !!}",
            "{!! asset('backend/ezzyr_assets/app/media/img//bg/bg-4.jpg') !!}",
            "{!! asset('backend/ezzyr_assets/app/media/img//bg/bg-5.jpg') !!}",
            "{!! asset('backend/ezzyr_assets/app/media/img//bg/bg-6.jpg') !!}",
            "{!! asset('backend/ezzyr_assets/app/media/img//bg/bg-7.jpg') !!}",
            "{!! asset('backend/ezzyr_assets/app/media/img//bg/bg-8.jpg') !!}"
        ], {
            fade: 1000,
            duration: 8000
        }
    );
</script>
<style>
    .m-login__signin {
        padding: 30px 30px 30px 30px;
        background-image: url({!! asset('backend/ezzyr_assets/app/media/img/bg-white-lock.png') !!});
        background-size: cover;
        background-repeat: no-repeat;
    }
    .lockRightTop{
        min-height: 80px;
    }
    @media (max-width: 767px) {
        .text-xs-left { text-align: left; }
        .text-xs-right { text-align: right; }
        .text-xs-center { text-align: center; }
        .text-xs-justify { text-align: justify; }
        .lockRightTop {
            min-height: 60px;
            margin-top: 15px;
        }
    }
</style>
</body>
<!-- end::Body -->
</html>
