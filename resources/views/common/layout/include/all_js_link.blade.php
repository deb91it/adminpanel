<!-- jQuery UI 1.11.4 -->
<script defer src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script defer>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- metronic js file -->
<script defer src="{!! asset('backend/ezzyr_assets/vendors/base/vendors.bundle.js') !!}" type="text/javascript"></script>
<script defer src="{!! asset('backend/ezzyr_assets/demo/default/base/scripts.bundle.js') !!}" type="text/javascript"></script>
<!--end::Base Scripts -->

<!--begin::Page Snippets -->
<script defer src="{!! asset('backend/ezzyr_assets/app/js/dashboard.js') !!}" type="text/javascript"></script>
<!--end::Page Snippets -->
<!--begin::Page Vendors -->
<script defer src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
<script defer src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
<script defer src="//www.amcharts.com/lib/3/amstock.js" type="text/javascript"></script>
<script defer src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
<script defer src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
<script defer src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>
<!--end::Page Vendors -->
<!--begin::Page Resources -->
<script defer src="{!! asset('backend/ezzyr_assets/app/js/custom.js') !!}" type="text/javascript"></script>
<!-- metronic js file -->
<!-- DataTables -->
<script defer src="{!! asset('backend/plugins/datatables/jquery.dataTables.min.js') !!}"></script>
<script defer src="{!! asset('backend/plugins/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4I-UdSRHCSIMrLQlKE68krPHAQVXpzy4&libraries=places&callback=initMap" async defer></script>
<script defer type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.1/js/dataTables.fixedColumns.min.js"></script>

{{--Alertify plugin--}}
<script defer src="{!! asset('backend/ezzyr_assets/plugin/alertify/alertify.min.js') !!}"></script>
<script defer>
    function reset () {
        $("#toggleCSS").attr("href", "{!! asset('backend/ezzyr_assets/plugin/alertify/alertify.default.css') !!}");
        alertify.set({
            labels : {
                ok     : "OK",
                cancel : "Cancel"
            },
            delay : 7000,
            buttonReverse : false,
            buttonFocus   : "ok",
            notifier : { position : 'top-right' }
        });
    }
</script>
{{--Alertify plugin end--}}