
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
{!! Html::style('backend/bootstrap/css/bootstrap.min.css') !!}
<link rel="stylesheet" href="{!! asset('backend/plugins/datatables/dataTables.bootstrap.css') !!}"/>

<link rel="stylesheet" href="{!! asset('backend/plugins/datatables/jquery.dataTables.min.css') !!}"/>
<!-- metronic demo2 css file -->
<link href="{!! asset('backend/ezzyr_assets/vendors/base/vendors.bundle2.css') !!}" rel="stylesheet" type="text/css" />
<link href="{!! asset('backend/ezzyr_assets/demo/demo2/base/style.bundle2.css') !!}" rel="stylesheet" type="text/css" />
<link href="{!! asset('backend/ezzyr_assets/demo/demo2/base/agent_custom.css') !!}" rel="stylesheet" type="text/css" />
<!-- metronic demo2 css file end -->

{!! Html::script('backend/plugins/jQuery/jQuery-2.1.4.min.js') !!}



