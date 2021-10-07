<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>
        Merchant | Registration
    </title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    {!! Html::style('backend/bootstrap/css/bootstrap.min.css') !!}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
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
    <style>
        .header_label{
            border-bottom: #E6E8EB solid 1px;
            padding-top: 5px;
            padding-bottom: 10px;
            margin-bottom: 10px;
            color: #716ACA;
            font-size: 16px;
        }
        .form-gap{
            margin-top: 15px;
        }
    </style>
    <!--begin::Portlet-->
    <body style="background-color: #F8F9FA">
    <div>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FFFFFF">
            <a href="">
                <img style="width: 150px" src="{{asset('backend/ezzyr_assets/app/media/img/logos/provati.png')}}">
            </a>
        </nav>
    </div>
    <div class="container">
        <div class="row justify-content-center" style="padding-top: 40px">
            <div class="m-portlet">

                <div class="m-portlet__body">
                    <div class="text-center">
                        <span><i class="fa fa-check-circle" style="font-size: 180px; color: green"></i></span>
                        <div>
                            <h4>Congratulations  <br>Your registration has been successfully completed.</h4>
                        </div>
                        <a class="btn btn-default" href="http://merchant.provaticourier.com/"> Go To Login </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </body>
</html>
    <!--end::Portlet-->
    <script>
        $("#bank_account_details").hide();
        function changeIDType(type){
            switch(type) {

                case 'nid':
                    $("#label_id_number").html("NID Number <span class=\"text-danger\">*</span>");
                    $("#id_number").attr("placeholder", "Enter NID Number");
                    break;
                case 'passport':
                    $("#label_id_number").html("Passport Number <span class=\"text-danger\">*</span>");
                    $("#id_number").attr("placeholder", "Enter Passport Number");
                    break;
                case 'birth_certificate':
                    $("#label_id_number").html("Birth Certificate Number <span class=\"text-danger\">*</span>");
                    $("#id_number").attr("placeholder", "Enter Birth Certificate Number");
                    break;
                case 'driving_licence':
                    $("#label_id_number").html("Driving Licence Number <span class=\"text-danger\">*</span>");
                    $("#id_number").attr("placeholder", "Enter Driving Licence Number");
                    break;
                default:
                    alert('Invalid Type Selection');
            }
        }
        function changePaymentType(value) {
            switch(value) {
                case 1:
                    $("#mobile_account_details").show();
                    $("#bank_account_details").hide();
                    break;
                case 2:
                    $("#bank_account_details").show();
                    $("#mobile_account_details").hide();
                    break;
                default:
                    alert('Invalid Type Selection');
            }
        }
        function chevron_activity(activity,action1,action2,data_content) {
            // console.log(activity+"  "+action1+"  "+action2+"  "+data_content);
            switch(activity) {
                case 'up':
                    $("#"+action1).show();
                    $("#"+data_content).hide();
                    $("#"+action2).hide();
                    break;
                case 'down':
                    $("#"+action2).hide();
                    $("#"+data_content).show();
                    $("#"+action1).show();
                    break;
                default:
                    alert('Invalid Type Selection');
            }
        }
    </script>

