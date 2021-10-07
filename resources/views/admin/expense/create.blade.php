@extends('admin.layout.master')
@section('title') Create New Expense @stop


@section('page_name')
    Expense Management
    <small>New Expense</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.expenses', 'Expense Management') !!} </li>
    <li class="active"> {!! link_to_route('admin.expense.new', 'New Expense') !!} </li>
@stop

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Add Expense
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'admin.expense.store', 'role' => 'form', 'method' => 'post', 'id' => "add-expense", "files" => true]) !!}
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('exp_category_id') ? 'has-error' : '' !!}">
                                <label>Expense Category <span class="text-danger">*</span></label>
                                {!! Form::select('exp_category_id',$cat_list, null, ['class' => 'form-control m-input m-bootstrap-select m_m_selectpicker1','id'=>'exp_category_id', 'placeholder' => 'Select Category', 'tabindex' => '1']) !!}
                                {!! $errors->first('exp_category_id', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('amount') ? 'has-error' : '' !!}">
                                <label>Amount <span class="text-danger">*</span></label>
                                {!! Form::text('amount', null, ['class' => 'form-control m-input','id'=>'amount','value'=>Input::old('amount'), 'placeholder' => 'Enter Amount', 'tabindex' => '2']) !!}
                                {!! $errors->first('amount', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('expense_date') ? 'has-error' : '' !!}">
                                <label>Expense Date <span class="text-danger">*</span></label>
                                {!! Form::text('expense_date', null, ['class' => 'form-control m-input ','id'=>'expense_date', 'placeholder' => 'Select Date', 'tabindex' => '1']) !!}
                                {!! $errors->first('expense_date', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('image_name') ? 'has-error' : '' !!}">
                                <label>Any attachment !!! Upload here .. </label>
                                {!! Form::File('image_name', null, ['class' => 'form-control m-input ','id'=>'image_name', 'placeholder' => 'Select Date', 'tabindex' => '1']) !!}
                                {!! $errors->first('image_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('payment_type') ? 'has-error' : '' !!}">
                                <label>Payment Type <span class="text-danger">*</span></label>
                                {!! Form::Select('payment_type',["cash" => "Cash", "bKash" => "bKash", "cheque" => "Cheque"],null, ['class' => 'form-control m-input','id'=>'payment_type','value'=>Input::old('payment_type'), 'placeholder' => 'Select Payment Type', 'tabindex' => '2']) !!}
                                {!! $errors->first('payment_type', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('payment_date') ? 'has-error' : '' !!}">
                                <label>Payment Date <span class="text-danger">*</span></label>
                                {!! Form::text('payment_date', date('Y-m-d'), ['class' => 'form-control m-input ','id'=>'payment_date', 'placeholder' => 'Select Date', 'tabindex' => '1']) !!}
                                {!! $errors->first('payment_date', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 {!! $errors->has('description') ? 'has-error' : '' !!}">
                                <label>Description</label>
                                {!! Form::textArea('description', null, ['class' => 'form-control m-input','id'=>'description','value'=>Input::old('amount'), 'placeholder' => 'Enter Amount', 'tabindex' => '3']) !!}
                                {!! $errors->first('description', '<label class="error_txt_size">:message</label>') !!}
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
                                    <span class="text-success"> Save </span>
                                </button>
                                &nbsp;
                                <span class="btn btn-default reset-form" tabindex="20">
                                    <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                                    <span class="text-success"> Reset </span>
                                </span>
                                &nbsp;
                                <a href="{!! route('admin.expenses') !!}" class="btn btn-default" tabindex="20">
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
        //== Bootstrap select 2
        var Select2 = function() {
            //== Private functions
            var demos = function() {
                // basic
                $('#exp_category_id').select2();
            }
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

        //== Class definition
        var BootstrapDatetimepicker = function () {

            //== Private functions
            var demos = function () {
                $('#expense_date, #payment_date').datetimepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose: true,
                    startView: 2,
                    minView: 2,
                    forceParse: 0,
                    pickerPosition: 'bottom-left'
                });
            };

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
@stop