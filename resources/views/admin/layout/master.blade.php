<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />
        <title>@yield('title')</title>
		<link rel="shortcut icon" href="{!! asset('backend/ezzyr_assets/app/media/img/logos/favicon.png') !!}" />
		<meta name="description" content="@yield('meta_description', 'Ezzyr, Ride Safety')">
        <meta HTTP-EQUIV="Pragma" CONTENT="no-cache">	
		<!-- Tell the browser to be responsive to screen width -->
		<!--meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"-->
        @yield('meta')
		
		@include('common.layout.include.all_css_link')
		<script type="text/javascript">
            $('#modal-item').on('hidden', function() {
                $(".modal-body input").val("")
                $(this).removeData('modal');
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
	</head>
	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<!-- Header -->
			@php
				$roles = userRolePermissionArray()
			@endphp
			<header class="m-grid__item    m-header "  data-minimize-mobile="hide" data-minimize-offset="200" data-minimize-mobile-offset="200" data-minimize="minimize" >
				<div class="m-container m-container--fluid m-container--full-height">
					<div class="m-stack m-stack--ver m-stack--desktop">
						<div class="m-stack__item m-brand  m-brand--skin-dark ">
							<div class="m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-stack__item--middle m-brand__logo">
									<a href="{{ url('/admin') }}" class="m-brand__logo-wrapper" style="color:#e3e3e3">
										{{--<h2>EZZYR</h2>--}}
										<img src="{!! asset('backend/ezzyr_assets/app/media/img/logos/provati.png') !!}" width="110px">
									</a>
								</div>
								<div class="m-stack__item m-stack__item--middle m-brand__tools">
									<!-- BEGIN: Left Aside Minimize Toggle -->
									<a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block
					 ">
										<span></span>
									</a>
									<!-- END -->
									<!-- BEGIN: Responsive Aside Left Menu Toggler -->
									<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
										<span></span>
									</a>
									<!-- END -->
									<!-- BEGIN: Responsive Header Menu Toggler -->
									<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
										<span></span>
									</a>
									<!-- END -->
									<!-- BEGIN: Topbar Toggler -->
									<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
										<i class="flaticon-more"></i>
									</a>
									<!-- BEGIN: Topbar Toggler -->
								</div>
							</div>
						</div>
						<!-- END: Brand -->
					@include('admin.layout.include.top_nav')
					</div>
				</div>
			</header>
			<!-- main body -->
				
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
				<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
					@include('admin.layout.include.left_sidebar')
				</div>
				<div class="m-grid__item m-grid__item--fluid m-wrapper">
					<div class="m-subheader clearfix" style="padding: 5px 30px 0 30px!important;">
						<h1 class="m-subheader__title pull-left">						
							@section('page_name')
								//Page name will come here
							@show
						</h1>
						<ol class="breadcrumb pull-right" style="background-color: inherit!important;margin-bottom: 0px!important;">
	                        @section('breadcrumbs')
	                            {{--//Breadcrumb will come here--}}
	                        @show
						</ol>
					</div>
					<div class="m-content" style="padding-top: 5px;">
						<!--Flash Message-->
                     	@include('admin.layout.include.flash')
                    	<!--Content-->
						@yield('content')
					</div>
				</div>			
			</div>
			   
			<!-- main body -->

			<footer class="m-grid__item	m-footer ">
				@include('admin.layout.include.footer')
			</footer>
		</div><!-- ./wrapper -->
		<!-- Right sidebar for changing settings -->
		@include('admin.layout.include.right_sidebar')
		<!-- All JS Links -->
		@include('common.layout.include.all_js_link')

		<style>
			.input-group-btn {
		    width: unset!important;
		}
		</style>
		
	</body>
</html>
