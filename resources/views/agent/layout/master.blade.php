<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />
        <title>@yield('title')</title>
        <meta name="description" content="@yield('meta_description', 'Ezzyr, Ride Safety')">
        <meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
		<link rel="shortcut icon" href="{!! asset('backend/ezzyr_assets/app/media/img/logos/favicon.png') !!}" />
		<!-- Tell the browser to be responsive to screen width -->
		<!--meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"-->
        @yield('meta')
		
		@include('agent.layout.include.all_css')
		<script type="text/javascript">
            $('#modal-item').on('hidden', function() {
                $(".modal-body input").val("")
                $(this).removeData('modal');
            });
		</script>
	</head>
	<body class="m-page--wide m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default">
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<!-- Header -->
			@php
				$roles = userRolePermissionArray();
				$company = get_company_details();
			@endphp
			<header class="m-grid__item	m-header "  data-minimize="minimize" data-minimize-offset="200" data-minimize-mobile-offset="200" >
				@include('agent.layout.include.header_top')
				@include('agent.layout.include.header_bottom')
			</header>
			<!-- main body -->
				
			<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop m-container m-container--responsive m-container--xxl m-page__container m-body">
				<div class="m-grid__item m-grid__item--fluid m-wrapper">
					<div class="m-subheader clearfix">
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
                     	@include('agent.layout.include.flash')
                    	<!--Content-->
						@yield('content')
					</div>
				</div>			
			</div>
			   
			<!-- main body -->

			<footer class="m-grid__item	m-footer ">
				@include('agent.layout.include.footer')
			</footer>
		</div><!-- ./wrapper -->
		<!-- Right sidebar for changing settings -->
		@include('agent.layout.include.right_sidebar')
		<!-- All JS Links -->
		@include('agent.layout.include.all_js')

		<style>
			.input-group-btn {
		    width: unset!important;
		}
		</style>
		
	</body>
</html>
