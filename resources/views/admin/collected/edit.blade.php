@extends('admin.layout.master')
@section('title') Update Delivery @stop

@section('page_name')
    Delivery  Management
    <small>All Delivery </small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.deliverys', ' Delivery Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Delivery update area
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                @if(!empty($tracker))
                    <h5>Tracking Details</h5>
                    @include('admin.delivery.partial.tracking_details',['Tracker'=>$tracker])
                @endif
                <form method="post" action="{{route('admin.delivery.riders.store')}}" id="form-sorting">
                    {{csrf_field()}}
                    <input type="hidden" name="deliveries_id" value="{{$delivery->id}}">
                    <div class="row">
                        <div class="col-md-4 {!! $errors->has('flag_status_id') ? 'has-error' : ''  !!}">
                            <label>Sorting <span class="text-danger">*</span></label>
                            <select class="form-control m-input" name="flag_status_id" id="flag_status_id">
                                <option value="">select an option</option>
                                @forelse($getStatus as $status)
                                    <option value="{{$status->id}}">{{$status->flag_text}}</option>
                                    @empty
                                    <option></option>
                                @endforelse
                            </select>
                            {!! $errors->first('flag_status_id', '<label class="error_txt_size">:message</label>') !!}
                        </div>
                        <div class="col-md-3 {!! $errors->has('is_hub') ? 'has-error' : ''  !!}" id="div_hub">
                            <label>Is Hub <span class="text-danger">*</span></label>
                            <select class="form-control m-input" name="is_hub" id="is_hub">
                                <option value="">select an option</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                            </select>
                            {!! $errors->first('is_hub', '<label class="error_txt_size">:message</label>') !!}
                        </div>
                        <div class="col-md-3 {!! $errors->has('assign_to') ? 'has-error' : ''  !!}" id="div_assign">
                            <label>Assign To <span class="text-danger">*</span></label>
                            <select class="form-control m-input" name="assign_to" id="assign_to">
                            </select>
                            {!! $errors->first('assign_to', '<label class="error_txt_size">:message</label>') !!}
                        </div>
                        <div class="col-md-2">
                            <label> &nbsp;</label>
                            <button style="margin-top: 27px;" type="submit" class="btn btn-brand" href="#" onclick="sortingDeliveris()">submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        {!! Form::open(['route' => ['admin.deliveries.update', $delivery->id], 'role' => 'form', 'method' => 'post', 'id' => "add-delivery"]) !!}
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
                        <div class="col-md-4">
                            <div class="form-group {!! $errors->has('recipient_name') ? 'has-error' : ''  !!}">
                                <label>Recipient Name<span class="text-danger">*</span></label>
                                {!! Form::text('recipient_name', $delivery->recipient_name, ['class' => 'form-control m-input','id'=>'recipient_name','value'=>Input::old('recipient_name'), 'placeholder' => 'Enter recipient name', 'tabindex' => '1']) !!}
                                {!! $errors->first('recipient_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>

                        <div class="col-md-4 {!! $errors->has('recipient_number') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Recipient Number<span class="text-danger">*</span></label>
                                {!! Form::text('recipient_number', $delivery->recipient_number, ['class' => 'form-control m-input','id'=>'recipient_number','value'=>Input::old('recipient_number'), 'placeholder' => 'Enter recipient number !', 'tabindex' => '2']) !!}
                                {!! $errors->first('recipient_number', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-4 {!! $errors->has('recipient_email') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Recipient Email</label>
                                {!! Form::email('recipient_email', $delivery->recipient_email, ['class' => 'form-control m-input','id'=>'recipient_email','value'=>Input::old('recipient_email'), 'placeholder' => 'Enter recipient email !', 'tabindex' => '3']) !!}
                                {!! $errors->first('recipient_email', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>

                        <div class="col-md-4 {!! $errors->has('recipient_zone') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Recipient Zone</label>
                                {!! Form::select('recipient_zone', $zone, $delivery->recipient_zone_id, array('class' => 'form-control m-input','id'=>'selectZone','value'=>Input::old('recipient_zone'), 'placeholder' => 'Select recipient zone !', 'tabindex' => '4')) !!}
                                {!! $errors->first('recipient_zone', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8 {!! $errors->has('recipient_address') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Recipient Address<span class="text-danger">*</span></label>
                                {!! Form::textarea('recipient_address', $delivery->recipient_address, ['class' => 'form-control m-input','id'=>'recipient_address', 'rows' => "6", 'value'=>Input::old('recipient_address'), 'placeholder' => 'Enter recipient address !', 'tabindex' => '5']) !!}
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
                                {!! Form::select('merchant', $merchant, $delivery->merchant_id, array('class' => 'form-control m-input selectMerchant','id'=>'selectMerchant','value'=>Input::old('merchant'), 'placeholder' => 'Select merchant!', 'tabindex' => '6')) !!}
                                {!! $errors->first('merchant', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4 {!! $errors->has('store') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Store</label>
                                {!! Form::select('store', $store, $delivery->store_id, array('class' => 'form-control m-input','id'=>'selectStore','value'=>Input::old('store'), 'placeholder' => 'Select recipient store !', 'tabindex' => '6')) !!}
                                {!! $errors->first('store', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="col-md-4 {!! $errors->has('products[]') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Product</label>
                                {!! Form::select('products[]', $products, $delivery->product_id, array('class' => 'form-control m-input','id'=>'selectProduct', 'multiple', 'value'=>Input::old('products'), 'tabindex' => '7')) !!}
                                {!! $errors->first('products[]', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8 {!! $errors->has('package_description') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Package Description</label>
                                {!! Form::textarea('package_description', $delivery->package_description, ['class' => 'form-control m-input','id'=>'package_description', 'rows' => "6", 'value'=>Input::old('package_description'), 'placeholder' => 'Enter recipient address !', 'tabindex' => '7']) !!}
                                {!! $errors->first('package_description', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4 {!! $errors->has('amount_to_be_collected') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Amount to be Collected</label>
                                {!! Form::number('amount_to_be_collected', $delivery->amount_to_be_collected, ['class' => 'form-control m-input','id'=>'amount_to_be_collected', 'value'=>Input::old('amount_to_be_collected'), 'placeholder' => 'Enter amount !', 'tabindex' => '8']) !!}
                                {!! $errors->first('amount_to_be_collected', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="col-md-4 {!! $errors->has('plan') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Plan<span class="text-danger">*</span></label>
                                {!! Form::select('plan', $plan, $delivery->plan_id, array('class' => 'form-control m-input','id'=>'selectPlan', 'value'=>Input::old('plan'), 'placeholder' => 'select plan !', 'tabindex' => '8')) !!}
                                {!! $errors->first('plan', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4 {!! $errors->has('delivery_date') ? 'has-error' : ''  !!}">
                            <div class="form-group">
                                <label>Deliver On</label>
                                <div class='input-group date' id='m_datetimepicker_5'>
                                    {!! Form::text('delivery_date', $delivery->delivery_date, ['class' => 'form-control m-input','id'=>'delivery_date','value'=>Input::old('delivery_date'), 'placeholder' => 'Enter delivery date', 'tabindex' => '1']) !!}
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
                                {!! Form::text('merchant_order_id', $delivery->merchant_order_id, ['class' => 'form-control m-input','id'=>'merchant_order_id','value'=>Input::old('merchant_order_id'), 'placeholder' => 'Enter Merchant Order ID', 'tabindex' => '1']) !!}
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
                                {!! Form::textarea('special_instruction', $delivery->special_instruction, ['class' => 'form-control m-input','id'=>'special_instruction', 'rows' => "6", 'value'=>Input::old('special_instruction'), 'placeholder' => 'Enter instruction !', 'tabindex' => '11']) !!}
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
    <script src="{!! asset('backend/ezzyr_assets/jquery_validator/jquery.validate.js') !!}" type="text/javascript"></script>
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
                    placeholder: "Select merchant"
                });
                $('#selectProduct').select2({
                    placeholder: "Select product"
                });
                $('#selectStore').select2({
                    placeholder: "Select store"
                });
                $('#selectPlan').select2({
                    placeholder: "Select plan"
                });
                // $('#flag_status_id').select2({
                //     placeholder: "Select Sorting"
                // });
                $('#assign_to').select2({
                    placeholder: "Select"
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
            $("#div_hub").hide();
            $("#div_assign").hide();
            $("#form-sorting").validate({
                rules: {
                    // simple rule, converted to {required:true}
                    flag_status_id: {
                        required: true,
                    },

                    is_hub: {
                        required: function(element){
                            return $("#flag_status_id").val()!="" && $("#flag_status_id").val() == 10;
                        }
                    },

                    assign_to: {
                        required: function(element){
                            return $("#flag_status_id").val()!="" && $("#is_hub").val()!="" && $("#flag_status_id").val() == 10;
                        }
                    },
                },
                errorElement : 'label',
                errorClass : 'error_txt_size',
            });
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
        $("#flag_status_id").change(function () {
           if ($(this).val() == 10){
                $("#div_hub").show();
           }else{
               $("#div_hub").hide();
           }
        });
        $("#is_hub").change(function () {
           if ($(this).val() == 2){
               $.ajax
               ({
                   type: "POST",
                   url: "{!! route('admin.delivery.riders') !!}",
                   data: {"_token": "{{ csrf_token() }}","is_hub": 2},
                   cache: false,
                   beforeSend: function(){
                       $('#loader1').show();
                   },
                   complete: function(){
                       $('#loader1').hide();
                   },
                   success: function(html)
                   {
                       $("#label_assign_to").text("Select Rider");
                       $("#assign_to").html(html);
                   }
               });
                $("#div_assign").show();
           }else if($(this).val() == 1){
               $.ajax
               ({
                   type: "POST",
                   url: "{!! route('admin.delivery.riders') !!}",
                   data: {"_token": "{{ csrf_token() }}","is_hub": 1},
                   cache: false,
                   beforeSend: function(){
                       $('#loader1').show();
                   },
                   complete: function(){
                       $('#loader1').hide();
                   },
                   success: function(html)
                   {
                       $("#label_assign_to").text("Select Hub");
                       $("#assign_to").html(html);
                   }
               });
               $("#div_assign").show();
           }else{
               $("#div_assign").hide();
           }
        });
    </script>
@stop