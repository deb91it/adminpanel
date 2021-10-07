<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>
        Provati | Merchant Registration
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
        .icon-size{
            font-size: 18px;
            cursor: pointer;
        }
    </style>
    <!--begin::Portlet-->
    <body style="background-color: #F8F9FA">
    <div>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FFFFFF">
            <div class="row" style="width:100%;">
                <div class="col-md-4">
                    <a href="" style="float: left">
                        <img style="width: 150px" src="{{asset('backend/ezzyr_assets/app/media/img/logos/provati.png')}}">
                    </a>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <a style="float: right" class="btn btn-default" href="http://merchant.provaticourier.com"> Go To Login </a>
                </div>
            </div>
        </nav>

    </div>
    <div class="container">
        <div class="row justify-content-center" style="padding-top: 40px">
            <div class="col-md-9">
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption text-center">
                            <div class="m-portlet__head-title" style="width: 100%">
                                <h2 class="m-portlet__head-text" style="font-size: 28px;color: #716ACA">
                                    Merchant Registration
                                </h2>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    {!! Form::open(['route' => 'admin.merchant.store.registration', 'role' => 'form', 'method' => 'post', 'id' => "add-Merchant",'enctype'=>"multipart/form-data"]) !!}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="m-form m-form--fit m-form--label-align-right ">
                        <div class="m-portlet__body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group m-form__group row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header_label" style="">
                                            <span>Business Details</span>
                                            <span class="pull-right">
                                    <i id="business_chevron_up" onclick="chevron_activity('up','business_chevron_down','business_chevron_up','business_section')" class="fa fa-chevron-circle-up icon-size"></i>
                                    <i id="business_chevron_down" onclick="chevron_activity('down','business_chevron_up','business_chevron_down','business_section')" style="display: none" class="fa fa-chevron-circle-down icon-size"></i>
                                </span>
                                        </div>
                                        <div id="business_section">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('business_name') ? 'has-error' : '' !!}">
                                                <label>Name of Your Business <span class="text-danger">*</span></label>
                                                {!! Form::text('business_name', null, ['class' => 'form-control m-input','id'=>'business_name','value'=>Input::old('business_name'), 'placeholder' => 'Enter Business Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('business_name', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('media_link') ? 'has-error' : '' !!}">
                                                <label>Media Link </label>
                                                {!! Form::text('media_link', null, ['class' => 'form-control m-input', 'id'=>'media_link','value'=>Input::old('media_link'), 'placeholder' => 'Enter Media Link', 'tabindex' => '2']) !!}
                                                {!! $errors->first('media_link', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-gap {!! $errors->has('address') ? 'has-error' : '' !!}">
                                                <label>Address <span class="text-danger">*</span></label>
                                                {!! Form::textArea('address', null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'address','value'=>Input::old('address'), 'placeholder' => 'Enter Address', 'tabindex' => '2']) !!}
                                                {!! $errors->first('address', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header_label" style="">
                                            <span>Owner Details</span>
                                            <span class="pull-right">
                                    <i id="owner_chevron_up" onclick="chevron_activity('up','owner_chevron_down','owner_chevron_up','owner_details')" class="fa fa-chevron-circle-up icon-size"></i>
                                    <i id="owner_chevron_down" onclick="chevron_activity('down','owner_chevron_up','owner_chevron_down','owner_details')" style="display: none" class="fa fa-chevron-circle-down icon-size"></i>
                                </span>
                                        </div>
                                        <div id="owner_details">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('first_name') ? 'has-error' : '' !!}">
                                                <label>Business Owner First Name <span class="text-danger">*</span></label>
                                                {!! Form::text('first_name', null, ['class' => 'form-control m-input','id'=>'first_name','value'=>Input::old('first_name'), 'placeholder' => 'Enter First Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('first_name', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('owner_name') ? 'has-error' : '' !!}">
                                                <label>Business Owner Last Name <span class="text-danger">*</span></label>
                                                {!! Form::text('last_name', null, ['class' => 'form-control m-input','id'=>'last_name','value'=>Input::old('last_name'), 'placeholder' => 'Enter Last Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('last_name', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('mobile_no') ? 'has-error' : '' !!}">
                                                <label>Business Owner Number <span class="text-danger">*</span> </label>
                                                {!! Form::text('mobile_no', null, ['class' => 'form-control m-input', 'id'=>'mobile_no','value'=>Input::old('mobile_no'), 'placeholder' => 'Enter Business Owner Number', 'tabindex' => '2']) !!}
                                                {!! $errors->first('mobile_no', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('email') ? 'has-error' : '' !!}">
                                                <label>Business Owner Email <span class="text-danger">*</span></label>
                                                {!! Form::text('email', null, ['class' => 'form-control m-input','id'=>'email','value'=>Input::old('email'), 'placeholder' => 'Enter Business Owner Email', 'tabindex' => '1']) !!}
                                                {!! $errors->first('email', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('password') ? 'has-error' : '' !!}">
                                                <label>Password <span class="text-danger">*</span></label>
                                                {!! Form::password('password', ['class' => 'form-control m-input','id'=>'password','value'=>Input::old('password'), 'placeholder' => 'Enter password', 'tabindex' => '1']) !!}
                                                {!! $errors->first('password', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('password_confirmation') ? 'has-error' : '' !!}">
                                                <label>Confirm Password <span class="text-danger">*</span></label>
                                                {!! Form::password('password_confirmation', ['class' => 'form-control m-input','id'=>'password_confirmation','value'=>Input::old('password_confirmation'), 'placeholder' => 'Enter password again', 'tabindex' => '1']) !!}
                                                {!! $errors->first('password_confirmation', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('profile_pic') ? 'has-error' : '' !!}">
                                                <label>Upload Image </label>
                                                {!! Form::file('profile_pic', null, ['class' => 'form-control m-input', 'id'=>'profile_pic','value'=>Input::old('profile_pic'), 'placeholder' => 'Enter Last Name', 'tabindex' => '2']) !!}
                                                {!! $errors->first('profile_pic', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('hub_id') ? 'has-error' : '' !!}">
                                                <label>Select Hub </label>
                                                {!! Form::select('hub_id',$hubs, null, ['class' => 'form-control m-input', 'id'=>'hub_id','value'=>Input::old('hub_id'), 'placeholder' => 'Select Hub', 'tabindex' => '2']) !!}
                                                {!! $errors->first('hub_id', '<label class="error_txt_size">:message</label>') !!}
                                            </div>


                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-gap {!! $errors->has('last_name') ? 'has-error' : '' !!}">
                                                <label>ID Type <span class="text-danger">*</span> </label>
                                                <div class="row form-gap">
                                                    <div class="col-md-3">
                                                        <label class="m-radio m-radio--solid m-radio--brand">
                                                            <input type="radio" checked name="id_type" onclick="changeIDType('nid')" value="nid">
                                                            NID
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="m-radio m-radio--solid m-radio--brand">
                                                            <input type="radio" name="id_type" onclick="changeIDType('passport')" value="passport">
                                                            Passport
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="m-radio m-radio--solid m-radio--brand">
                                                            <input type="radio" name="id_type" onclick="changeIDType('birth_certificate')" value="birth_certificate">
                                                            Birth Certificate
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="m-radio m-radio--solid m-radio--brand">
                                                            <input type="radio" name="id_type" onclick="changeIDType('driving_licence')" value="driving_licence">
                                                            Driving Licence
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                {!! $errors->first('last_name', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('id_number') ? 'has-error' : '' !!}">
                                                <label id="label_id_number">NID Number <span class="text-danger">*</span> </label>
                                                {!! Form::text('id_number', null, ['class' => 'form-control m-input', 'id'=>'id_number','value'=>Input::old('id_number'), 'placeholder' => 'Enter NID Number', 'tabindex' => '2']) !!}
                                                {!! $errors->first('id_number', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('front_image') ? 'has-error' : '' !!}">
                                                <label>Front Image <span class="text-danger">*</span> </label>
                                                {!! Form::file('front_image', null, ['class' => 'form-control m-input custom-file-input', 'id'=>'front_image','value'=>Input::old('front_image'), 'placeholder' => 'Enter Last Name', 'tabindex' => '2']) !!}
                                                {!! $errors->first('front_image', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header_label" style="">
                                            <span>Contact Person Details</span>
                                            <span class="pull-right">
                                    <i id="operator_chevron_up" onclick="chevron_activity('up','operator_chevron_down','operator_chevron_up','operator_details')" class="fa fa-chevron-circle-up icon-size"></i>
                                    <i id="operator_chevron_down" onclick="chevron_activity('down','operator_chevron_up','operator_chevron_down','operator_details')" style="display: none" class="fa fa-chevron-circle-down icon-size"></i>
                                </span>
                                        </div>
                                        <div id="operator_details">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('operator_name') ? 'has-error' : '' !!}">
                                                <label>Contact Person Name</label>
                                                {!! Form::text('operator_name', null, ['class' => 'form-control m-input','id'=>'operator_name','value'=>Input::old('operator_name'), 'placeholder' => 'Enter Contact Person Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('operator_name', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('operator_number') ? 'has-error' : '' !!}">
                                                <label>Contact Person Number </label>
                                                {!! Form::text('operator_number', null, ['class' => 'form-control m-input', 'id'=>'operator_number','value'=>Input::old('operator_number'), 'placeholder' => 'Enter Contact Person Number', 'tabindex' => '2']) !!}
                                                {!! $errors->first('operator_number', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('operator_email') ? 'has-error' : '' !!}">
                                                <label>Contact Person Email</label>
                                                {!! Form::text('operator_email', null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'operator_email','value'=>Input::old('operator_email'), 'placeholder' => 'Enter Contact Person email', 'tabindex' => '2']) !!}
                                                {!! $errors->first('operator_email', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header_label" style="">
                                            <span>Payment Details</span>
                                            <span class="pull-right">
                                                <i id="payment_chevron_up" onclick="chevron_activity('up','payment_chevron_down','payment_chevron_up','payment_details')" class="fa fa-chevron-circle-up"></i>
                                                <i id="payment_chevron_down" onclick="chevron_activity('down','payment_chevron_up','payment_chevron_down','payment_details')" style="display: none" class="fa fa-chevron-circle-down"></i>
                                            </span>
                                        </div>
                                        <div id="payment_details">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 {!! $errors->has('first_name') ? 'has-error' : '' !!}">
                                                <label>Payment type</label>
                                                <div class="row form-gap">
                                                    <div class="col-md-3">
                                                        <label class="m-radio m-radio--solid m-radio--brand">
                                                            <input type="radio" name="payment_type" checked id="payment_type" onclick="changePaymentType(1)" value="1" tabindex="">
                                                            Mobile
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="m-radio m-radio--solid m-radio--brand">
                                                            <input type="radio" name="payment_type" id="payment_type" onclick="changePaymentType(2)" value="2" tabindex="">
                                                            Bank
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="m-radio m-radio--solid m-radio--brand">
                                                            <input type="radio" name="payment_type" id="payment_type" onclick="changePaymentType(3)" value="3" tabindex="">
                                                            Both
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="m-radio m-radio--solid m-radio--brand">
                                                            <input type="radio" name="payment_type" id="payment_type" onclick="changePaymentType(4)" value="4" tabindex="">
                                                            Cash
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="mobile_account_details" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('wallet_provider_mobile') ? 'has-error' : '' !!}" style="margin-top: 14px;">
                                                    <label>Select Mobile Wallet Provider </label>
                                                    {!! Form::select('wallet_provider_mobile',$mobile, null, ['class' => 'form-control m-input', 'id'=>'wallet_provider_mobile','value'=>Input::old('wallet_provider_mobile'), 'placeholder' => 'Select wallet provider', 'tabindex' => '17']) !!}
                                                    {!! $errors->first('wallet_provider_mobile', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('account_holder_name_mobile') ? 'has-error' : '' !!}">
                                                    <label>Mobile Account Name <span class="text-danger">*</span></label>
                                                    {!! Form::text('account_holder_name_mobile', null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'account_holder_name_mobile','value'=>Input::old('account_holder_name_mobile'), 'placeholder' => 'Enter Mobile Account Holder Name', 'tabindex' => '18']) !!}
                                                    {!! $errors->first('account_holder_name_mobile', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('account_number_mobile') ? 'has-error' : '' !!}">
                                                    <label>Mobile Account Number <span class="text-danger">*</span></label>
                                                    {!! Form::text('account_number_mobile', null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'account_number_mobile','value'=>Input::old('account_number_mobile'), 'placeholder' => 'Enter Account Number', 'tabindex' => '19']) !!}
                                                    {!! $errors->first('account_number_mobile', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                            </div>

                                            <div id="bank_account_details" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('wallet_provider_bank') ? 'has-error' : '' !!}" style="margin-top: 14px;">
                                                    <label>Select Bank Wallet Provider </label>
                                                    {!! Form::select('wallet_provider_bank', $bankInfo,null, ['class' => 'form-control m-input', 'id'=>'wallet_provider_bank','value'=>Input::old('wallet_provider_bank'), 'placeholder' => 'Select wallet provider', 'tabindex' => '20']) !!}
                                                    {!! $errors->first('wallet_provider_bank', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('account_holder_name_bank') ? 'has-error' : '' !!}">
                                                    <label>Bank Account Holder Name <span class="text-danger">*</span></label>
                                                    {!! Form::text('account_holder_name_bank', null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'account_holder_name_bank','value'=>Input::old('account_holder_name_bank'), 'placeholder' => 'Enter Bank Account Holder Name', 'tabindex' => '21']) !!}
                                                    {!! $errors->first('account_holder_name_bank', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('account_number_bank') ? 'has-error' : '' !!}">
                                                    <label>Bank Wallet Number <span class="text-danger">*</span></label>
                                                    {!! Form::text('account_number_bank', null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'account_number_bank','value'=>Input::old('account_number'), 'placeholder' => 'Enter Account Number', 'tabindex' => '22']) !!}
                                                    {!! $errors->first('account_number_bank', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('bank_account_type') ? 'has-error' : '' !!}">
                                                    <label>Bank Account Type <span class="text-danger">*</span></label>
                                                    {!! Form::select('bank_account_type',['current'=>'Current','savings'=>'Savings'], null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'last_name','value'=>Input::old('bank_account_type'), 'placeholder' => 'Select Account Type', 'tabindex' => '23']) !!}
                                                    {!! $errors->first('bank_account_type', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('bank_brunch_name') ? 'has-error' : '' !!}">
                                                    <label>Bank Brunch Name <span class="text-danger">*</span></label>
                                                    {!! Form::text('bank_brunch_name', null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'bank_brunch_name','value'=>Input::old('bank_brunch_name'), 'placeholder' => 'Enter Bank Brunch Name', 'tabindex' => '24']) !!}
                                                    {!! $errors->first('bank_brunch_name', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                {{--                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('bank_routing_number') ? 'has-error' : '' !!}">--}}
                                                {{--                                        <label>Bank Routing No. <span class="text-danger">*</span></label>--}}
                                                {{--                                        {!! Form::text('bank_routing_number', null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'bank_routing_number','value'=>Input::old('bank_routing_number'), 'placeholder' => 'Enter bank Routing Number', 'tabindex' => '25']) !!}--}}
                                                {{--                                        {!! $errors->first('bank_routing_number', '<label class="error_txt_size">:message</label>') !!}--}}
                                                {{--                                    </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div><!--END LEFT COL -->
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions--solid">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offser-2 col-md-offset-2 col-sm-offset-2">
                                        <div class="pull-left">
                                            <button class="btn btn-default" tabindex="20">
                                                <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                                                <span class="text-success"> Submit </span>
                                            </button>
                                            &nbsp;
                                            <span class="btn btn-default reset-form" tabindex="20">
                                    <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                                    <span class="text-success"> Reset </span>
                                </span>
                                            &nbsp;
{{--                                            <a href="{!! route('admin.merchants') !!}" class="btn btn-default" tabindex="20">--}}
{{--                                                --}}{{-- <span class="glyphicon glyphicon-remove text-danger"></span>&nbsp; <span class="text-danger"> Cancel </span>--}}
{{--                                                <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>--}}
{{--                                            </a>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                <!--end::Form-->
                </div>
            </div>
        </div>
        <div class="row">
            <div style="margin: 0 auto;left: 0;right: 0;">
                <h6 style="color: black" class="text-center">
                    <strong>Developed by <a href="#">Provati Courier LTD</a></strong>
                </h6>
            </div>
        </div>
    </div>
    </body>
</html>
    <!--end::Portlet-->
    <script>
        $(".reset-form").click(function () {
            $("#add-Merchant")[0].reset();
        });
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
                case 3:
                    $("#bank_account_details").show();
                    $("#mobile_account_details").show();
                    break;
                case 4:
                    $("#bank_account_details").hide();
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

