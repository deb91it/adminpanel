@extends('admin.layout.master')
@section('title') Create New Delivery @stop

@section('page_name')
    Delivery  Management
    <small>All Delivery </small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.deliverys', ' Delivery Management') !!} </li>
@stop

@section('content')
    <?php
/*    	echo '<pre>';
    	print_r($zone);
    	echo '</pre>';
    	exit;
    */?>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Delivery creation area
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::open(['route' => 'admin.deliveries.store', 'role' => 'form', 'method' => 'post', 'id' => "add-delivery"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <h5 style="margin-bottom: 10px;">Recipient Details</h5>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4 {!! $errors->has('recipient_name') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Recipient Name<span class="text-danger">*</span></label>
                                {!! Form::text('recipient_name', null, ['class' => 'form-control m-input','id'=>'recipient_name','value'=>Input::old('recipient_name'), 'placeholder' => 'Enter recipient name', 'tabindex' => '1']) !!}
                                {!! $errors->first('recipient_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>

                        <div class="col-md-4 {!! $errors->has('recipient_number') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Recipient Number<span class="text-danger">*</span></label>
                                {!! Form::text('recipient_number', null, ['class' => 'form-control m-input','id'=>'recipient_number','value'=>Input::old('recipient_number'), 'placeholder' => 'Enter recipient number !', 'tabindex' => '2']) !!}
                                {!! $errors->first('recipient_number', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-4 {!! $errors->has('recipient_email') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Recipient Email</label>
                                {!! Form::email('recipient_email', null, ['class' => 'form-control m-input','id'=>'recipient_email','value'=>Input::old('recipient_email'), 'placeholder' => 'Enter recipient email !', 'tabindex' => '3']) !!}
                                {!! $errors->first('recipient_email', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>

                        <div class="col-md-4 {!! $errors->has('recipient_zone') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Recipient Zone</label>
                                {!! Form::select('recipient_zone', $zone, null, array('class' => 'form-control m-input','id'=>'selectZone','value'=>Input::old('recipient_zone'), 'placeholder' => 'Select recipient zone !', 'tabindex' => '4')) !!}
                                {!! $errors->first('recipient_zone', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8 {!! $errors->has('recipient_address') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Recipient Address<span class="text-danger">*</span></label>
                                {!! Form::textarea('recipient_address', null, ['class' => 'form-control m-input','id'=>'recipient_address', 'rows' => "6", 'value'=>Input::old('recipient_address'), 'placeholder' => 'Enter recipient address !', 'tabindex' => '5']) !!}
                                {!! $errors->first('recipient_address', '<label class="error_txt_size">:message</label>') !!}
                            </div>

                        </div>
                    </div>
                    <div class="row justify-content-center mt-5">
                        <div class="col-md-8">
                            <h5 style="margin-bottom: 10px;">Item Details</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-2 {!! $errors->has('merchant') ? 'has-error' : 'merchant'  !!}">
                            <div class="form-group">
                                <label>Merchant</label>
                                {!! Form::select('merchant', $merchant, null, array('class' => 'form-control m-input selectMerchant','id'=>'selectMerchant','value'=>Input::old('merchant'), 'placeholder' => 'Select merchant!', 'tabindex' => '6')) !!}
                                {!! $errors->first('merchant', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4 {!! $errors->has('store') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Store</label>
                                {!! Form::select('store', ['First select merchant'], null, array('class' => 'form-control m-input','id'=>'selectStore','value'=>Input::old('store'), 'tabindex' => '6')) !!}
                                {!! $errors->first('store', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="col-md-4 {!! $errors->has('is_active') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Product</label>
                                <select name="products[]" class="form-control m-input" id="selectProduct" multiple>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8 {!! $errors->has('package_description') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Package Description</label>
                                {!! Form::textarea('package_description', null, ['class' => 'form-control m-input','id'=>'package_description', 'rows' => "6", 'value'=>Input::old('package_description'), 'placeholder' => 'Enter recipient address !', 'tabindex' => '7']) !!}
                                {!! $errors->first('package_description', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4 {!! $errors->has('amount_to_be_collected') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Amount to be Collected</label>
                                {!! Form::number('amount_to_be_collected', null, ['class' => 'form-control m-input','id'=>'amount_to_be_collected', 'value'=>Input::old('amount_to_be_collected'), 'placeholder' => 'Enter amount !', 'tabindex' => '8']) !!}
                                {!! $errors->first('amount_to_be_collected', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="col-md-4 {!! $errors->has('plan') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Plan<span class="text-danger">*</span></label>
                                {!! Form::select('plan', $plan, null, array('class' => 'form-control m-input','id'=>'selectPlan', 'value'=>Input::old('plan'), 'placeholder' => 'select plan !', 'tabindex' => '8')) !!}
                                {!! $errors->first('plan', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4 {!! $errors->has('delivery_date') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Deliver On</label>
                                <div class='input-group date' id='m_datetimepicker_5'>
                                    {!! Form::text('delivery_date', null, ['class' => 'form-control m-input','id'=>'delivery_date','value'=>Input::old('delivery_date'), 'placeholder' => 'Enter delivery date', 'tabindex' => '1']) !!}
                                    {!! $errors->first('delivery_date', '<label class="error_txt_size">:message</label>') !!}
                                    <span class="input-group-addon">
                                        <i class="la la-calendar glyphicon-th"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 {!! $errors->has('merchant_order_id') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Merchant Order ID</label>
                                {!! Form::text('merchant_order_id', null, ['class' => 'form-control m-input','id'=>'merchant_order_id','value'=>Input::old('merchant_order_id'), 'placeholder' => 'Enter Merchant Order ID', 'tabindex' => '1']) !!}
                                {!! $errors->first('merchant_order_id', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-5">
                        <div class="col-md-8">
                            <h5 style="margin-bottom: 10px;margin-top: 10px;">Other Details</h5>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8 {!! $errors->has('special_instruction') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Special Instructions</label>
                                {!! Form::textarea('special_instruction', null, ['class' => 'form-control m-input','id'=>'special_instruction', 'rows' => "6", 'value'=>Input::old('special_instruction'), 'placeholder' => 'Enter instruction !', 'tabindex' => '11']) !!}
                                {!! $errors->first('special_instruction', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot clearfix">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offser-2 col-md-offset-2 col-sm-offset-2">
                    <div class="pull-left">
                        <button class="btn btn-default" tabindex="20">
                            <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                            <span class="text-success"> Save </span>
                        </button>
                        &nbsp;
                        <span class="btn btn-default reset-form" tabindex="20">
                            <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                            <span class="text-success"> Reset </span>
                        </span>
                        &nbsp;
                        <a href="{!! route('admin.deliverys') !!}" class="btn btn-default" tabindex="20">
                            <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <!--end::Section-->
    </div>
    <!--end::Portlet-->

    <script type="text/javascript">
        //== Class definition
        var Select2 = function() {
            //== Private functions
            var demos = function() {
                // basic
                $('#selectZone').select2({
                    placeholder: "Select a zone"
                });
                $('#selectMerchant').select2({
                    placeholder: "Select a merchant"
                });
                $('#selectProduct').select2({
                    placeholder: "First select merchant"
                });
                $('#selectStore').select2({
                    //placeholder: "Select store"
                });
                $('#selectPlan').select2({
                    placeholder: "Select plan"
                });
            };

            //== Public functions
            return {
                init: function() {
                    demos();
                }
            };
        }();

        //== Initialization
        jQuery(document).ready(function() {
            Select2.init();
        });
    </script>
    <script>
        //== Class definition
        var BootstrapDatetimepicker = function () {

            //== Private functions
            var demos = function () {
                $('#m_datetimepicker_5').datetimepicker({
                    //format: "'Y-m-d H:i:s'",
                    showMeridian: true,
                    todayHighlight: true,
                    autoclose: true,
                    pickerPosition: 'top-left'
                });
            }

            return {
                // public functions
                init: function() {
                    demos();
                }
            };
        }();

        jQuery(document).ready(function() {
            BootstrapDatetimepicker.init();
        });
    </script>
    <script>
        //City Select
        $(document).ready(function () {
            $(".selectMerchant").change(function()
            {

                var merchant_id = $(this).val();
                if (merchant_id == '')
                    return false;
                $.ajax
                ({
                    type: "POST",
                    url: "{!! route('admin.delivery.products') !!}",
                    data: {"_token": "{{ csrf_token() }}", "merchant_id": merchant_id},
                    cache: false,
                    beforeSend: function(){
                        $('#loader1').show();
                    },
                    complete: function(){
                        $('#loader1').hide();
                    },
                    success: function(html)
                    {
                        $("#selectProduct").html(html);
                    }
                });
                $.ajax
                ({
                    type: "POST",
                    url: "{!! route('admin.delivery.stores') !!}",
                    data: {"_token": "{{ csrf_token() }}", "merchant_id": merchant_id},
                    cache: false,
                    beforeSend: function(){
                        $('#loader1').show();
                    },
                    complete: function(){
                        $('#loader1').hide();
                    },
                    success: function(html)
                    {
                        $("#selectStore").html(html);
                    }
                });
            });
        });
    </script>
@stop