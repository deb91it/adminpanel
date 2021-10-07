@extends('admin.layout.master')
@section('title') Create Edit Merchant @stop


@section('page_name')
    Merchant Management
    <small>Edit Merchant</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.merchants', 'Merchant Management') !!} </li>
    <li class="active"> {!! link_to_route('admin.merchant.new', 'New Merchant') !!} </li>
@stop

@section('content')
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
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Edit Merchant
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => ['admin.merchant.update',$merchant->member_id], 'role' => 'form', 'method' => 'post', 'id' => "add-Merchant",'enctype'=>"multipart/form-data"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header_label" style="">
                                <span>Business Details</span>
                                <span class="pull-right">
                                    <i id="business_chevron_up" onclick="chevron_activity('up','business_chevron_down','business_chevron_up','business_section')" class="fa fa-chevron-circle-up"></i>
                                    <i id="business_chevron_down" onclick="chevron_activity('down','business_chevron_up','business_chevron_down','business_section')" style="display: none" class="fa fa-chevron-circle-down"></i>
                                </span>
                            </div>
                            <div id="business_section">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('business_name') ? 'has-error' : '' !!}">
                                    <label>Name of Your Business <span class="text-danger">*</span></label>
                                    {!! Form::text('business_name', $merchant->business_name, ['class' => 'form-control m-input','id'=>'business_name','value'=>Input::old('business_name'), 'placeholder' => 'Enter Business Name', 'tabindex' => '1']) !!}
                                    {!! $errors->first('business_name', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('media_link') ? 'has-error' : '' !!}">
                                    <label>Media Link </label>
                                    {!! Form::text('media_link', $merchant->media_link, ['class' => 'form-control m-input', 'id'=>'media_link','value'=>Input::old('media_link'), 'placeholder' => 'Enter Media Link', 'tabindex' => '2']) !!}
                                    {!! $errors->first('media_link', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-gap {!! $errors->has('address') ? 'has-error' : '' !!}">
                                    <label>Address <span class="text-danger">*</span></label>
                                    {!! Form::textArea('address', $merchant->address, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'address','value'=>Input::old('address'), 'placeholder' => 'Enter Address', 'tabindex' => '3']) !!}
                                    {!! $errors->first('address', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header_label" style="">
                                <span>Owner Details</span>
                                <span class="pull-right">
                                    <i id="owner_chevron_up" onclick="chevron_activity('up','owner_chevron_down','owner_chevron_up','owner_details')" class="fa fa-chevron-circle-up"></i>
                                    <i id="owner_chevron_down" onclick="chevron_activity('down','owner_chevron_up','owner_chevron_down','owner_details')" style="display: none" class="fa fa-chevron-circle-down"></i>
                                </span>
                            </div>
                            <div id="owner_details">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('first_name') ? 'has-error' : '' !!}">
                                    <label>Business Owner First Name <span class="text-danger">*</span></label>
                                    {!! Form::text('first_name', $merchant->first_name, ['class' => 'form-control m-input','id'=>'first_name','value'=>Input::old('first_name'), 'placeholder' => 'Enter First Name', 'tabindex' => '4']) !!}
                                    {!! $errors->first('first_name', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('owner_name') ? 'has-error' : '' !!}">
                                    <label>Business Owner Last Name <span class="text-danger">*</span></label>
                                    {!! Form::text('last_name', $merchant->last_name, ['class' => 'form-control m-input','id'=>'last_name','value'=>Input::old('last_name'), 'placeholder' => 'Enter Last Name', 'tabindex' => '5']) !!}
                                    {!! $errors->first('last_name', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('mobile_no') ? 'has-error' : '' !!}">
                                    <label>Business Owner Number <span class="text-danger">*</span> </label>
                                    {!! Form::text('mobile_no', $merchant->mobile_no, ['class' => 'form-control m-input', 'id'=>'mobile_no','value'=>Input::old('mobile_no'), 'placeholder' => 'Enter Last Name', 'tabindex' => '6']) !!}
                                    {!! $errors->first('mobile_no', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('email') ? 'has-error' : '' !!}">
                                    <label>Business Owner Email <span class="text-danger">*</span></label>
                                    {!! Form::text('email', $merchant->email, ['class' => 'form-control m-input','id'=>'email','value'=>Input::old('email'), 'placeholder' => 'Enter First Name', 'tabindex' => '7']) !!}
                                    {!! $errors->first('email', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('profile_pic') ? 'has-error' : '' !!}">
                                    <label>Upload Image </label>
                                    {!! Form::file('profile_pic', null, ['class' => 'form-control m-input', 'id'=>'profile_pic','value'=>Input::old('profile_pic'), 'placeholder' => 'Enter Last Name', 'tabindex' => '8']) !!}
                                    {!! $errors->first('profile_pic', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('hub_id') ? 'has-error' : '' !!}">
                                    <label>Select Hub </label>
                                    {!! Form::select('hub_id',$hubs, $merchant->hub_id, ['class' => 'form-control m-input', 'id'=>'hub_id','value'=>Input::old('hub_id'), 'placeholder' => 'Select Hub', 'tabindex' => '9']) !!}
                                    {!! $errors->first('hub_id', '<label class="error_txt_size">:message</label>') !!}
                                </div>


                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-gap {!! $errors->has('last_name') ? 'has-error' : '' !!}">
                                    <label>ID Type <span class="text-danger">*</span> </label>
                                    <div class="row form-gap">
                                        <div class="col-md-3">
                                            <label class="m-radio m-radio--solid m-radio--brand">
                                                <input type="radio" {{$merchant->id_type == 'nid' ? 'checked' : ''}} name="id_type" onclick="changeIDType('nid')" value="nid">
                                                NID
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="m-radio m-radio--solid m-radio--brand">
                                                <input type="radio" name="id_type" onclick="changeIDType('passport')" value="passport" {{$merchant->id_type == 'passport' ? 'checked' : ''}}>
                                                Passport
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="m-radio m-radio--solid m-radio--brand">
                                                <input type="radio" name="id_type" onclick="changeIDType('birth_certificate')" value="birth_certificate" {{$merchant->id_type == 'birth_certificate' ? 'checked' : ''}}>
                                                Birth Certificate
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="m-radio m-radio--solid m-radio--brand">
                                                <input type="radio" name="id_type" onclick="changeIDType('driving_licence')" value="driving_licence" {{$merchant->id_type == 'driving_licence' ? 'checked' : ''}}>
                                                Driving Licence
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    {!! $errors->first('last_name', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('id_number') ? 'has-error' : '' !!}">
                                    <label id="label_id_number">NID Number <span class="text-danger">*</span> </label>
                                    {!! Form::text('id_number', $merchant->id_number, ['class' => 'form-control m-input', 'id'=>'id_number','value'=>Input::old('id_number'), 'placeholder' => 'Enter NID Number', 'tabindex' => '10']) !!}
                                    {!! $errors->first('id_number', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('front_image') ? 'has-error' : '' !!}">
                                    <label>Front Image <span class="text-danger">*</span> </label>
                                    {!! Form::file('front_image', null, ['class' => 'form-control m-input custom-file-input', 'id'=>'front_image','value'=>Input::old('front_image'), 'placeholder' => 'Enter Last Name', 'tabindex' => '11']) !!}
                                    {!! $errors->first('front_image', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header_label" style="">
                                <span>Operator Details</span>
                                <span class="pull-right">
                                    <i id="operator_chevron_up" onclick="chevron_activity('up','operator_chevron_down','operator_chevron_up','operator_details')" class="fa fa-chevron-circle-up"></i>
                                    <i id="operator_chevron_down" onclick="chevron_activity('down','operator_chevron_up','operator_chevron_down','operator_details')" style="display: none" class="fa fa-chevron-circle-down"></i>
                                </span>
                            </div>
                            <div id="operator_details">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('operator_name') ? 'has-error' : '' !!}">
                                    <label>Operator Name</label>
                                    {!! Form::text('operator_name', $merchant->operator_name, ['class' => 'form-control m-input','id'=>'operator_name','value'=>Input::old('operator_name'), 'placeholder' => 'Enter Operator Name', 'tabindex' => '12']) !!}
                                    {!! $errors->first('operator_name', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-gap {!! $errors->has('operator_number') ? 'has-error' : '' !!}">
                                    <label>Operator Number </label>
                                    {!! Form::text('operator_number', $merchant->operator_number, ['class' => 'form-control m-input', 'id'=>'operator_number','value'=>Input::old('operator_number'), 'placeholder' => 'Enter Operator Number', 'tabindex' => '13']) !!}
                                    {!! $errors->first('operator_number', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('operator_email') ? 'has-error' : '' !!}">
                                    <label>Operator Email</label>
                                    {!! Form::text('operator_email', $merchant->operator_email, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'operator_email','value'=>Input::old('operator_email'), 'placeholder' => 'Enter Operator email', 'tabindex' => '14']) !!}
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
                                                <input type="radio" name="payment_type" {{$merchant->payment_type == 1 ? 'checked' : ''}}  id="payment_type" onclick="changePaymentType(1)" value="1">
                                                Mobile
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="m-radio m-radio--solid m-radio--brand">
                                                <input type="radio" name="payment_type" id="payment_type" {{$merchant->payment_type == 2 ? 'checked' : ''}} onclick="changePaymentType(2)" value="2">
                                                Bank
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="m-radio m-radio--solid m-radio--brand">
                                                <input type="radio" name="payment_type" id="payment_type" {{$merchant->payment_type == 3 ? 'checked' : ''}} onclick="changePaymentType(3)" value="3">
                                                Both
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="m-radio m-radio--solid m-radio--brand">
                                                <input type="radio" name="payment_type" id="payment_type" {{$merchant->payment_type == 4 ? 'checked' : ''}} onclick="changePaymentType(4)" value="4" tabindex="">
                                                Cash
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="mobile_account_details" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('wallet_provider_mobile') ? 'has-error' : '' !!}" style="margin-top: 14px;">
                                        <label>Select an Wallet Provider </label>
                                        {!! Form::select('wallet_provider_mobile',$mobile, $merchant->payment_type == 1 || $merchant->payment_type == 3 ? $merchant->payment_details[0]->wallet_provider : null , ['class' => 'form-control m-input', 'id'=>'wallet_provider_mobile','value'=>Input::old('wallet_provider_mobile'), 'placeholder' => 'Select wallet provider', 'tabindex' => '15']) !!}
                                        {!! $errors->first('wallet_provider_mobile', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('account_holder_name_mobile') ? 'has-error' : '' !!}">
                                        <label>Mobile Account Name <span class="text-danger">*</span></label>
                                        {!! Form::text('account_holder_name_mobile', $merchant->payment_type == 1 || $merchant->payment_type == 3 ? $merchant->payment_details[0]->account_holder_name : null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'account_holder_name_mobile','value'=>Input::old('account_holder_name_mobile'), 'placeholder' => 'Enter Mobile Account Holder Name', 'tabindex' => '16']) !!}
                                        {!! $errors->first('account_holder_name_mobile', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('account_number_mobile') ? 'has-error' : '' !!}">
                                        <label>Mobile Account Number <span class="text-danger">*</span></label>
                                        {!! Form::text('account_number_mobile', $merchant->payment_type == 1 || $merchant->payment_type == 3 ? $merchant->payment_details[0]->account_number : null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'account_number_mobile','value'=>Input::old('account_number_mobile'), 'placeholder' => 'Enter Account Number', 'tabindex' => '17']) !!}
                                        {!! $errors->first('account_number_mobile', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                </div>
                                <?php
                                    $indexKey = 0;
                                    if ($merchant->payment_type == 3)
                                    {
                                        $indexKey = 1;
                                    }
                                ?>
                                <div id="bank_account_details" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('wallet_provider_bank') ? 'has-error' : '' !!}" style="margin-top: 14px;">
                                        <label>Select an Wallet Provider </label>
                                        {!! Form::select('wallet_provider_bank', $bankInfo,$merchant->payment_type == 2 || $merchant->payment_type == 3 && array_key_exists($indexKey, $merchant->payment_details) ? $merchant->payment_details[$indexKey]->wallet_provider : null, ['class' => 'form-control m-input', 'id'=>'wallet_provider_bank','value'=>Input::old('wallet_provider_bank'), 'placeholder' => 'Select wallet provider', 'tabindex' => '18']) !!}
                                        {!! $errors->first('wallet_provider_bank', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('account_holder_name_bank') ? 'has-error' : '' !!}">
                                        <label>Bank Account Holder Name <span class="text-danger">*</span></label>
                                        {!! Form::text('account_holder_name_bank', $merchant->payment_type == 2 || $merchant->payment_type == 3 && array_key_exists($indexKey, $merchant->payment_details) ? $merchant->payment_details[$indexKey]->account_holder_name : null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'account_holder_name_bank','value'=>Input::old('account_holder_name_bank'), 'placeholder' => 'Enter Bank Account Holder Name', 'tabindex' => '19']) !!}
                                        {!! $errors->first('account_holder_name_bank', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('account_number_bank') ? 'has-error' : '' !!}">
                                        <label>Bank Wallet Number <span class="text-danger">*</span></label>
                                        {!! Form::text('account_number_bank', $merchant->payment_type == 2 || $merchant->payment_type == 3 && array_key_exists($indexKey, $merchant->payment_details) ? $merchant->payment_details[$indexKey]->account_number : null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'account_number_bank','value'=>Input::old('account_number'), 'placeholder' => 'Enter Account Number', 'tabindex' => '20']) !!}
                                        {!! $errors->first('account_number_bank', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('bank_account_type') ? 'has-error' : '' !!}">
                                        <label>Bank Account Type <span class="text-danger">*</span></label>
                                        {!! Form::select('bank_account_type',['current'=>'Current','savings'=>'Savings'], $merchant->payment_type == 2 || $merchant->payment_type == 3 && array_key_exists($indexKey, $merchant->payment_details) ? $merchant->payment_details[$indexKey]->bank_account_type : null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'last_name','value'=>Input::old('bank_account_type'), 'placeholder' => 'Select Account Type', 'tabindex' => '21']) !!}
                                        {!! $errors->first('bank_account_type', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('bank_brunch_name') ? 'has-error' : '' !!}">
                                        <label>Bank Brunch Name <span class="text-danger">*</span></label>
                                        {!! Form::text('bank_brunch_name', $merchant->payment_type == 2 || $merchant->payment_type == 3 && array_key_exists($indexKey, $merchant->payment_details) ? $merchant->payment_details[$indexKey]->bank_brunch_name : null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'bank_brunch_name','value'=>Input::old('bank_brunch_name'), 'placeholder' => 'Enter Bank Brunch Name', 'tabindex' => '22']) !!}
                                        {!! $errors->first('bank_brunch_name', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('bank_routing_number') ? 'has-error' : '' !!}">
                                        <label>Bank Routing No. <span class="text-danger">*</span></label>
                                        {!! Form::text('bank_routing_number', $merchant->payment_type == 2 || $merchant->payment_type == 3 && array_key_exists($indexKey, $merchant->payment_details) ? $merchant->payment_details[$indexKey]->bank_routing_number : null, ['class' => 'form-control m-input', 'col'=>'2','rows'=>'4', 'id'=>'bank_routing_number','value'=>Input::old('bank_routing_number'), 'placeholder' => 'Enter bank Routing Number', 'tabindex' => '23']) !!}
                                        {!! $errors->first('bank_routing_number', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                </div>
                                {{--                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-gap {!! $errors->has('last_name') ? 'has-error' : '' !!}">--}}
                                {{--                                <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">--}}
                                {{--                                    <input type="checkbox">--}}
                                {{--                                    I agree with this <a href="">Terms & Conditions</a>--}}
                                {{--                                    <span></span>--}}
                                {{--                                </label>--}}
                                {{--                                {!! $errors->first('last_name', '<label class="error_txt_size">:message</label>') !!}--}}
                                {{--                            </div>--}}
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
                                <a href="{!! route('admin.merchants') !!}" class="btn btn-default" tabindex="20">
                                    {{-- <span class="glyphicon glyphicon-remove text-danger"></span>&nbsp; <span class="text-danger"> Cancel </span>--}}
                                    <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
    <!--end::Form-->
    </div>
    <!--end::Portlet-->
    <script>
        <?php
        if ($merchant->payment_type == 4) { ?>
        $("#bank_account_details").hide();
        $("#mobile_account_details").hide();
        <?php } elseif ($merchant->payment_type == 2){ ?>
        $("#bank_account_details").show();
        $("#mobile_account_details").hide();
        <?php }elseif ($merchant->payment_type == 3){?>
        $("#bank_account_details").show();
        $("#mobile_account_details").show();
        <?php }else{ ?>
        $("#bank_account_details").hide();
        $("#mobile_account_details").show();
        <?php } ?>

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

@stop