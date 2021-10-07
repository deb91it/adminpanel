@extends('admin.layout.master')
@section('title') Settings @stop


@section('page_name')    &nbsp;
Settings
<small></small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.settings', 'App settings') !!} </li>
@stop

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
         <!--begin::Form-->
        {!! Form::open(['route' => 'admin.settings.store', 'role' => 'form', 'method' => 'post', 'class' => "m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed", 'id' => "add-settings", 'files'=>true]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Basic app settings
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <button class="btn m-btn--pill m-btn--air         btn-success m-btn--wide">
                    Save {!! $btnText !!}
                </button>
            </div>
        </div>
            <div class="m-portlet__body">
                <div class="m-form__heading" style="margin-top:10px;">
                    <h4 class="m-form__heading-title">
                        Admin Informatica <small>(Contact)</small>:
                    </h4>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('admin_email') ? 'has-error' : '' !!}">
                                <label> Admin Email Address <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="This email address will get email notification about the request">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('admin_email', $settings->admin_email, ['class' => 'form-control m-input', 'id'=>'admin_email','value'=>Input::old('admin_email'), 'placeholder' => 'Enter admin email address',  'tabindex' => '11']) !!}
                                {!! $errors->first('admin_email', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('admin_phone') ? 'has-error' : '' !!}">
                                <label> Admin Mobile number <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="This mobile number will get SMS notification about the request">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('admin_phone', $settings->admin_phone, ['class' => 'form-control m-input', 'id'=>'admin_phone','value'=>Input::old('admin_phone'), 'placeholder' => 'Enter admin mobile number', 'tabindex' => '12']) !!}
                                {!! $errors->first('admin_phone', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-form__heading" style="margin-top:10px;">
                    <h4 class="m-form__heading-title">
                        Admin Informatica <small>(Contact)</small>:
                    </h4>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('provider_time_out') ? 'has-error' : '' !!}">
                                <label>Provider Timeout <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Maximum time for driver to respond for a request">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('provider_time_out', $settings->provider_time_out, ['class' => 'form-control m-input', 'id'=>'provider_time_out','value'=>Input::old('provider_time_out'), 'placeholder' => 'Enter provider timeout', 'tabindex' => '2']) !!}
                                {!! $errors->first('provider_time_out', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('distance_unit') ? 'has-error' : '' !!}">
                                <label> Default Distance Unit <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Default unit of distance">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::select('distance_unit', array('Km' => 'Km', 'Miles' => 'Miles'), $settings->distance_unit ,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker', 'value'=>Input::old('distance_unit'), 'tabindex' => '1')) !!}
                            </div>                           
                        </div>
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('promo_code_activation') ? 'has-error' : '' !!}">
                                <label> Promotional Code Activation <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="User can get amount through promotional code">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::select('promo_code_activation', array('1' => 'Yes', '0' => 'No'), $settings->promo_code_activation ,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker1', 'value'=>Input::old('promo_code_activation'), 'tabindex' => '10')) !!}
                            </div>
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('merchant_commission_rate') ? 'has-error' : '' !!}">
                                <label> Merchant commission <span class="text-danger">*</span></label>
                                {!! Form::text('merchant_commission_rate', $settings->merchant_commission_rate, ['class' => 'form-control m-input', 'id'=>'merchant_commission_rate','value'=>Input::old('merchant_commission_rate'), 'placeholder' => 'Enter merchant commission', 'tabindex' => '18']) !!}
                                {!! $errors->first('merchant_commission_rate', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-form__heading" style="margin-top:10px;">
                    <h4 class="m-form__heading-title">
                        Notification <small>(Type):</small>
                    </h4>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('push_notification') ? 'has-error' : '' !!}">
                                <label> Push Notification <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Send push notification">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::select('push_notification', array('1' => 'Yes', '0' => 'No'), $settings->push_notification ,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker2', 'value'=>Input::old('push_notification'), 'tabindex' => '6')) !!}
                            </div>
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('sms_notification') ? 'has-error' : '' !!}">
                                <label> Sms Notification <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Send SMS notification">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::select('sms_notification', array('1' => 'Yes', '0' => 'No'), $settings->sms_notification ,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker3', 'value'=>Input::old('sms_notification'), 'tabindex' => '4')) !!}
                            </div>                           
                        </div>
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('email_notification') ? 'has-error' : '' !!}">
                                <label> Email Notification <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Send email notification">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::select('email_notification', array('1' => 'Yes', '0' => 'No'), $settings->email_notification ,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker4', 'value'=>Input::old('email_notification'), 'tabindex' => '5')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-form__heading" style="margin-top:10px;">
                    <h4 class="m-form__heading-title">
                        Map Center <small>(Coordinate)</small>:
                    </h4>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('center_latitude') ? 'has-error' : '' !!}">
                                <label> Map Center Latitude {{--<span class="text-danger">*</span>--}}
                                    <span data-toggle="m-tooltip" data-placement="top" title="Latitude for the center">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                 {!! Form::text('center_latitude', $settings->center_latitude, ['class' => 'form-control m-input', 'id'=>'center_latitude','value'=>Input::old('center_latitude'), 'placeholder' => 'Enter center latitude',  'tabindex' => '13']) !!}
                                {!! $errors->first('center_latitude', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('center_longitude') ? 'has-error' : '' !!}">
                                <label> Map Center Longitude {{--<span class="text-danger">*</span>--}}
                                    <span data-toggle="m-tooltip" data-placement="top" title="Longitude for the center">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('center_longitude', $settings->center_latitude, ['class' => 'form-control m-input', 'id'=>'center_longitude','value'=>Input::old('center_longitude'), 'placeholder' => 'Enter center longitude',  'tabindex' => '14']) !!}
                                {!! $errors->first('center_longitude', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-form__heading" style="margin-top:10px;">
                    <h4 class="m-form__heading-title">
                        Pick hour <small>(08:00 am to 08:00 pm):</small>
                    </h4>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('pk_base_fare') ? 'has-error' : '' !!}">
                                <label> Base fare {{--<span class="text-danger">*</span>--}}
                                    <span data-toggle="m-tooltip" data-placement="top" title="Base fare for pick hour">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('pk_base_fare', $settings->pk_base_fare, ['class' => 'form-control m-input', 'id'=>'pk_base_fare','value'=>Input::old('pk_base_fare'), 'placeholder' => 'Enter base fare',  'tabindex' => '15']) !!}
                                {!! $errors->first('pk_base_fare', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('pk_unit_fare') ? 'has-error' : '' !!}">
                                <label> Unit fare {{--<span class="text-danger">*</span>--}}
                                    <span data-toggle="m-tooltip" data-placement="top" title="Unit fare for pick hour">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('pk_unit_fare', $settings->pk_unit_fare, ['class' => 'form-control m-input', 'id'=>'pk_unit_fare','value'=>Input::old('pk_unit_fare'), 'placeholder' => 'Enter unit fare',  'tabindex' => '16']) !!}
                                {!! $errors->first('pk_unit_fare', '<label class="error_txt_size">:message</label>') !!}
                            </div>                           
                        </div>
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('pk_wtng_min_charge') ? 'has-error' : '' !!}">
                                <label> Waiting minute charge <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Per waiting minute charge for pick hour">
                                       <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('pk_wtng_min_charge', $settings->pk_wtng_min_charge, ['class' => 'form-control m-input', 'id'=>'pk_wtng_min_charge','value'=>Input::old('pk_wtng_min_charge'), 'placeholder' => 'Enter waiting min charge', 'tabindex' => '18']) !!}
                                {!! $errors->first('pk_wtng_min_charge', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-form__heading" style="margin-top:10px;">
                    <h4 class="m-form__heading-title">
                        Off-pick hour <small>(08:00 pm to 08:00 am):</small>
                    </h4>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('opk_base_fare') ? 'has-error' : '' !!}">
                                <label> Base fare {{--<span class="text-danger">*</span>--}}
                                    <span data-toggle="m-tooltip" data-placement="top" title="Base fare for off-pick hour">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('opk_base_fare', $settings->opk_base_fare, ['class' => 'form-control m-input', 'id'=>'opk_base_fare','value'=>Input::old('opk_base_fare'), 'placeholder' => 'Enter base fare',  'tabindex' => '15']) !!}
                                {!! $errors->first('opk_base_fare', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('opk_unit_fare') ? 'has-error' : '' !!}">
                                <label> Unit fare {{--<span class="text-danger">*</span>--}}
                                    <span data-toggle="m-tooltip" data-placement="top" title="Unit fare for off-pick hour">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('opk_unit_fare', $settings->opk_unit_fare, ['class' => 'form-control m-input', 'id'=>'opk_unit_fare','value'=>Input::old('opk_unit_fare'), 'placeholder' => 'Enter unit fare',  'tabindex' => '16']) !!}
                                {!! $errors->first('opk_unit_fare', '<label class="error_txt_size">:message</label>') !!}
                            </div>                           
                        </div>
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('opk_wtng_min_charge') ? 'has-error' : '' !!}">
                                <label> Waiting minute charge <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Per waiting minute charge for off-pick hour">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('opk_wtng_min_charge', $settings->opk_wtng_min_charge, ['class' => 'form-control m-input', 'id'=>'opk_wtng_min_charge','value'=>Input::old('opk_wtng_min_charge'), 'placeholder' => 'Enter waiting min charge', 'tabindex' => '18']) !!}
                                {!! $errors->first('opk_wtng_min_charge', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-form__heading" style="margin-top:10px;">
                    <h4 class="m-form__heading-title">
                        Referral Code <small>(Rules):</small>
                    </h4>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('referral_code_activation') ? 'has-error' : '' !!}">
                                <label> Referral Code Activation <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="User can get amount through the referral code">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::select('referral_code_activation', array('1' => 'Yes', '0' => 'No'), $settings->referral_code_activation ,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker5', 'value'=>Input::old('referral_code_activation'), 'tabindex' => '7')) !!}
                            </div>
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('referral_code_validation') ? 'has-error' : '' !!}">
                                <label> Referral Code Validation <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Referral code validation">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::select('referral_code_validation', array('1' => '1 Month', '2' => '2 Month', '3' => '3 Month', '4' => '4 Month', '5' => '5 Month', '6' => '6 Month', '12' => '1 Years'), $settings->referral_code_validation ,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker6', 'value'=>Input::old('referral_code_validation'), 'tabindex' => '8')) !!}
                            </div>                           
                        </div>
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('total_ride_to_get_referral_bonus') ? 'has-error' : '' !!}">
                                <label> Total rides to get referral bonus <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="How many rides need to get referral bonus ?">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::select('total_ride_to_get_referral_bonus', array('1' => '1 Ride', '2' => '2 Ride', '3' => '3 Ride', '4' => '4 Ride', '5' => '5 Ride', '6' => '6 Ride'), $settings->total_ride_to_get_referral_bonus ,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker7', 'value'=>Input::old('total_ride_to_get_referral_bonus'), 'tabindex' => '7')) !!}
                            </div>
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('bonus_to_refered_user') ? 'has-error' : '' !!}">
                                <label> Default Referral Bonus To Referred User <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Bonus credit that should be added to old registered user incase if user refers another ">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('bonus_to_refered_user', $settings->referral_bonus_to_refered_user, ['class' => 'form-control m-input', 'id'=>'bonus_to_refered_user','value'=>Input::old('bonus_to_refered_user'), 'placeholder' => 'Enter default bonus amount', 'tabindex' => '8']) !!}
                                {!! $errors->first('bonus_to_refered_user', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 padding-bottom-15 {!! $errors->has('bonus_to_referral') ? 'has-error' : '' !!}">
                                <label> Default Referral Bonus To Referral <span class="text-danger">*</span>
                                    <span data-toggle="m-tooltip" data-placement="top" title="Bonus credit that should be added to new registered user incase if user refers another ">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>
                                </label>
                                {!! Form::text('bonus_to_referral', $settings->referral_bonus_to_referral, ['class' => 'form-control m-input', 'id'=>'bonus_to_referral','value'=>Input::old('bonus_to_referral'), 'placeholder' => 'Enter default bonus amount', 'tabindex' => '9']) !!}
                                {!! $errors->first('bonus_to_referral', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                </div>      
            </div>
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions--solid">
                    <div class="row">
                        <div class="col-lg-6">
                            <button class="btn btn-primary">
                                Save {!! $btnText !!}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
         {!! Form::close() !!}
        <!--end::Form-->
    </div>
    <!--end::Portlet-->


    <style>
        .m-bootstrap-select .caret {
            display: none;
        }
    </style>
    <script>
        //== Class definition
        var BootstrapSelect = function () {
            //== Private functions
            var demos = function () {
                // minimum setup
                $('.m_m_selectpicker').selectpicker();
                $('.m_m_selectpicker1').selectpicker();
                $('.m_m_selectpicker2').selectpicker();
                $('.m_m_selectpicker3').selectpicker();
                $('.m_m_selectpicker4').selectpicker();
                $('.m_m_selectpicker5').selectpicker();
                $('.m_m_selectpicker6').selectpicker();
                $('.m_m_selectpicker7').selectpicker();
            }
            return {
                // public functions
                init: function() {
                    demos(); 
                }
            };
        }();

        jQuery(document).ready(function() {    
            BootstrapSelect.init();
        });
    </script>

   {{-- <script type="text/javascript">
        $(document).ready(function(){
            $(".reset-form").click(function() {
                $(':input','#edit-vehicle-type').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
    </script>--}}
@stop