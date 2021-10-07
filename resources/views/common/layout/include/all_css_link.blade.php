<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.5 -->
{!! Html::style('backend/bootstrap/css/bootstrap.min.css') !!}
<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"/>
<!-- Ionicons -->
<link rel="stylesheet" href="{!! asset('backend/plugins/datatables/dataTables.bootstrap.css') !!}"/>
<link rel="stylesheet" href="{!! asset('backend/plugins/datatables/jquery.dataTables.min.css') !!}"/>
<!--metronic css link -->
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

<link href="{!! asset('backend/ezzyr_assets/demo/default/base/ezzyr_custom.css') !!}" rel="stylesheet" type="text/css" />
<!--end::Base Styles -->
<link href="//www.amcharts.com/lib/3/plugins/export/export.css" rel="stylesheet" type="text/css" />

<!--metronic css link -->

{{--Alertify plugin--}}
<link href="{!! asset('backend/ezzyr_assets/plugin/alertify/alertify.core.css') !!}" rel="stylesheet" type="text/css" />
<link href="{!! asset('backend/ezzyr_assets/plugin/alertify/alertify.default.css') !!}" rel="stylesheet" type="text/css" id="toggleCSS" />
{{--Alertify plugin end--}}



<!-- jQuery 2.1.4 -->
{!! Html::script('backend/plugins/jQuery/jQuery-2.1.4.min.js') !!}

