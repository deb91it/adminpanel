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
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--singin" id="m_login">
                <div id="left" class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1  m-login__content">
                    <div class="m-grid__item m-grid__item--middle">
                        <h3 class="m-login__welcome">
                            Sales Tracking System
                        </h3>
                        {{--<p class="m-login__msg">--}}
                            {{--Lorem ipsum dolor sit amet, coectetuer adipiscing--}}
                            {{--<br>--}}
                            {{--elit sed diam nonummy et nibh euismod--}}
                        {{--</p>--}}
                    </div>
                </div>
                <div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
                    <div class="m-stack m-stack--hor m-stack--desktop">
                        <div class="m-stack__item m-stack__item--fluid">
                            <div class="m-login__wrapper">
                                <div class="m-login__logo mb-0">
                                    <a href="#">
                                        <img src="{!! asset('backend/ezzyr_assets/app/media/img/logos/trace-sale.png') !!}" width="60%">
                                    </a>
                                </div>
                                <div class="m-login__signin">
                                    <div class="m-login__head">
                                        <h3 class="m-login__title">
                                            Login Please
                                        </h3>
                                    </div>
                                    @foreach($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                            {!! $error !!}
                                        </div>
                                    @endforeach
                                    {!! Form::open(['route' => 'agent.user.login', 'role' => 'form', 'method' => 'post', 'id' => 'agent-login']) !!}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="m-login__form m-form">
                                            <div class="form-group m-form__group">
                                                <input class="form-control m-input" type="text" placeholder="Email/Mobile" name="user_name" autocomplete="off">
                                            </div>
                                            <div class="form-group m-form__group">
                                                <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password">
                                            </div>
                                            
                                            <div class="m-login__form-action">
                                                <button id="m_login_signin_submit" class="btn btn-warning m-btn m-btn--pill m-btn--custom m-btn--air">
                                                    Sign In
                                                </button>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="m-login__signup">
                                    <div class="m-login__head">
                                        <h3 class="m-login__title">
                                            Sign Up
                                        </h3>
                                        <div class="m-login__desc">
                                            Enter your details to create your account:
                                        </div>
                                    </div>
                                    <form class="m-login__form m-form" action="">
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="text" placeholder="Fullname" name="fullname">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="text" placeholder="Email" name="email" autocomplete="off">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="password" placeholder="Password" name="password">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Confirm Password" name="rpassword">
                                        </div>
                                        <div class="row form-group m-form__group m-login__form-sub">
                                            <div class="col m--align-left">
                                                <label class="m-checkbox m-checkbox--focus">
                                                    <input type="checkbox" name="agree">
                                                    I Agree the
                                                    <a href="#" class="m-link m-link--focus">
                                                        terms and conditions
                                                    </a>
                                                    .
                                                    <span></span>
                                                </label>
                                                <span class="m-form__help"></span>
                                            </div>
                                        </div>
                                        <div class="m-login__form-action">
                                            <button id="m_login_signup_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
                                                Sign Up
                                            </button>
                                            <button id="m_login_signup_cancel" class="btn btn-outline-focus  m-btn m-btn--pill m-btn--custom">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="m-login__forget-password">
                                    <div class="m-login__head">
                                        <h3 class="m-login__title">
                                            Forgotten Password ?
                                        </h3>
                                        <div class="m-login__desc">
                                            Enter your email to reset your password:
                                        </div>
                                    </div>
                                    <form class="m-login__form m-form" action="">
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="text" placeholder="Email" name="email" id="m_email" autocomplete="off">
                                        </div>
                                        <div class="m-login__form-action">
                                            <button id="m_login_forget_password_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
                                                Request
                                            </button>
                                            <button id="m_login_forget_password_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
        <!--End::Page -->
        <!--begin::Base Scripts -->
        <script src="{!! asset('backend/ezzyr_assets/vendors/base/vendors.bundle.js') !!}" type="text/javascript"></script>
        <script src="{!! asset('backend/ezzyr_assets/demo/default/base/scripts.bundle.js') !!}" type="text/javascript"></script>
        <!--end::Base Scripts -->

        <!-- Metronic 4 js -->
        <script src="{!! asset('backend/ezzyr_assets/plugin/backstretch/jquery.backstretch.js') !!}" type="text/javascript"></script>

        <!-- Bacground image slide -->
        <script>
        $("#left").backstretch([
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

    .btn-focus {
    color: #fff;
    background-color: #98CA3C!important;
    border-color: #98CA3C!important;
    }
</style>
    </body>
    <!-- end::Body -->
</html>
