@extends('agent.layout.master')
@section('title') Holiday Settings Management @stop

@section('page_name')
    Holiday Settings Management
    <small></small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('agent.holiday_settings', 'Holiday Settings') !!} </li>
    <li class="active"> Edit Holidays  </li>
@stop

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Edit Holidays
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => ['agent.holiday-settings.update',$holidays->id], 'role' => 'form', 'method' => 'post', 'id' => "edit-holidays"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('title') ? 'has-error' : '' !!}">
                                <label>Title <span class="text-danger">*</span></label>
                                {!! Form::text('title', $holidays->title, ['class' => 'form-control m-input','id'=>'title','value'=>Input::old('title'), 'placeholder' => 'Enter title', 'tabindex' => '1']) !!}
                                {!! $errors->first('title', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('description') ? 'has-error' : '' !!}">
                                <label>Description</label>
                                {!! Form::text('description', $holidays->description, ['class' => 'form-control m-input', 'id'=>'description','value'=>Input::old('description'), 'placeholder' => 'Enter description', 'tabindex' => '2']) !!}
                                {!! $errors->first('description', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('from_date') ? 'has-error' : '' !!}">
                                <label>From date <span class="text-danger">*</span></label>
                                <div class='input-group date' id='m_datepicker1'>
                                    {!! Form::text('from_date', $holidays->from_date, ['class' => 'form-control m-input', 'id'=>'from_date','value'=>Input::old('from_date'), 'readonly', 'placeholder' => 'Select date', 'tabindex' => '2']) !!}
                                    <span class="input-group-addon">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                                <lavel>
                                    {!! $errors->first('from_date', '<label class="error_txt_size">:message</label>') !!}
                                </lavel>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 {!! $errors->has('to_date') ? 'has-error' : '' !!}">
                                <label>To date <span class="text-danger">*</span></label>
                                <div class='input-group date' id='m_datepicker2'>
                                    {!! Form::text('to_date', $holidays->to_date, ['class' => 'form-control m-input', 'id'=>'to_date','value'=>Input::old('to_date'), 'readonly', 'placeholder' => 'Select date', 'tabindex' => '2']) !!}
                                    <span class="input-group-addon">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                                <lavel>
                                    {!! $errors->first('to_date', '<label class="error_txt_size">:message</label>') !!}
                                </lavel>
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
                                    <span class="text-success"> Save Changes</span>
                                </button>
                                &nbsp;
                                <span class="btn btn-default reset-form" tabindex="20">
                                    <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                                    <span class="text-success"> Reset </span>
                                </span>
                                &nbsp;
                                <a href="{!! route('agent.holiday_settings') !!}" class="btn btn-default" tabindex="20">
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
    <style>
        .input-group-addon {
            padding: 10px 24px 10px 15px!important;
            color: #575962;
            background-color: #f4f5f8;
            border: 1px solid;
            border-color: #ebedf2;
        }
    </style>

    <script>
        //== Class definition
        $(document).ready(function () {
            var BootstrapDatepickertest = function () {

                //== Private functions
                var demos = function () {

                    // input group layout
                    $('#m_datepicker1, #m_datepicker2').datepicker({
                        todayHighlight: true,
                        orientation: "bottom left",
                        format: 'yyyy-mm-dd',
                        templates: {
                            leftArrow: '<i class="la la-angle-left"></i>',
                            rightArrow: '<i class="la la-angle-right"></i>'
                        }
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
                BootstrapDatepickertest.init();
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".reset-form").click(function() {
                $(':input','#edit-holidays').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
    </script>
@stop