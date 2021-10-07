<!DOCTYPE html>
<html lang="en" >
    <!-- begin::Head -->
    <head>
        <meta charset="utf-8" />
        <title>
            Provati | Admin Log in
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
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-Q4M9FF7LM3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-Q4M9FF7LM3');
        </script>
        <!--end::Web font -->
        <!--begin::Base Styles -->
        <link href="{!! asset('backend/ezzyr_assets/vendors/base/vendors.bundle.css') !!}" rel="stylesheet" type="text/css" />
        <link href="{!! asset('backend/ezzyr_assets/demo/default/base/style.bundle.css') !!}" rel="stylesheet" type="text/css" />
        <!--end::Base Styles -->
        <link rel="shortcut icon" href="{!! asset('backend/ezzyr_assets/app/media/img/logos/favicon.png') !!}" />
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
                                <h1></h1><img src="{!! asset('backend/ezzyr_assets/app/media/img/logos/provati.png') !!}" width="280px">
                            </a><br>
{{--                            <h6 style="color: white">you select -  we delivered</h6>--}}
                        </div>
                        <div class="m-login__signin">
                            <div class="m-login__head">
                                <h3 class="m-login__title" style="color: #000;!important;">
                                    Login Please
                                </h3>
                            </div>
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                    {!! $error !!}
                                </div>

                            @endforeach

                            {!! Form::open(array()) !!}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            
                            <div class="m-login__form m-form">
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input"   type="email" name="email" value="{{ old('email') }}" placeholder="Email" name="email" autocomplete="off">
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password">
                                </div>
                                {{--<div class="row m-login__form-sub">
                                    <div class="col m--align-left m-login__form-left">
                                        <label class="m-checkbox  m-checkbox--light">
                                            <input type="checkbox" name="remember">
                                            Remember me
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col m--align-right m-login__form-right">
                                        <a href="javascript:;" id="m_login_forget_password" class="m-link">
                                            Forget Password ?
                                        </a>
                                    </div>
                                </div>--}}
                                <div class="m-login__form-action">
                                    <button type="submit" id="m_login_signin_submit" class="btn btn-warning m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">
                                        Sign In
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div style="position: absolute; bottom: 0;margin: 0 auto;left: 0;right: 0;">
                            <h6 style="color: #000000;" class="text-center">
                                <strong>Developed by Provati Courier Ltd</strong>
                            </h6>
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
    padding: 30px 30px 5px 30px;
    background-image: url({!! asset('backend/ezzyr_assets/app/media/img/bg-white-lock.png') !!});
    background-size: cover;
    background-repeat: no-repeat;
    }
    .m-login.m-login--2.m-login-2--skin-3 .m-login__container .m-login__form .form-control {
    color: #7668a4;
    }
</style>
    </body>
    <!-- end::Body -->
</html>
