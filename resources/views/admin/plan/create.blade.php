@extends('admin.layout.master')
@section('title') Create New Plan @stop


@section('page_name')
    Plan Management
    <small>New Plan</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.plans', 'Plan Management') !!} </li>
    <li class="active"> {!! link_to_route('admin.plan.new', 'New Plan') !!} </li>
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
                        Add Plan
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'admin.plan.store', 'role' => 'form', 'method' => 'post', 'id' => "add-Plan"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('plan_name') ? 'has-error' : '' !!}">
                                <label>Plan Name <span class="text-danger">*</span></label>
                                {!! Form::text('plan_name', null, ['class' => 'form-control m-input','id'=>'plan_name','value'=>Input::old('plan_name'), 'placeholder' => 'Enter Plan Name', 'tabindex' => '1']) !!}
                                {!! $errors->first('plan_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('plan_type') ? 'has-error' : '' !!}">
                                <label>Type<span class="text-danger">*</span></label>
                                {!! Form::select('plan_type', ["DELIVERED" => "DELIVERED", "RETURNED" => "RETURNED"],null, ['class' => 'form-control m-input','id'=>'charge','value'=>Input::old('plan_type'), 'placeholder' => 'Select Type', 'tabindex' => '1']) !!}
                                {!! $errors->first('plan_type', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('charge') ? 'has-error' : '' !!}">
                                <label>Charge Price (TK)<span class="text-danger">*</span></label>
                                {!! Form::text('charge', null, ['class' => 'form-control m-input','id'=>'charge','value'=>Input::old('charge'), 'placeholder' => 'Enter Amount', 'tabindex' => '1']) !!}
                                {!! $errors->first('charge', '<label class="error_txt_size">:message</label>') !!}
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
                                <a href="{!! route('admin.plans') !!}" class="btn btn-default" tabindex="20">
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

        //== Bootstrap select 2
        var Select2 = function() {
            //== Private functions
            var demos = function() {
                // basic
                $('#city_select').select2();
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
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".reset-form").click(function() {
                $(':input','#add-Plan').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
        $(".selectCountry").change(function()
        {
            var country_id = $(this).val();
            if (country_id == '')
                return false;
            $.ajax
            ({
                type: "POST",
                url: "{!! route('admin.passenger.zone') !!}",
                data: {"_token": "{{ csrf_token() }}", "country_id": country_id},
                cache: false,
                beforeSend: function(){
                    $('#loader1').show();
                },
                complete: function(){
                    $('#loader1').hide();
                },
                success: function(html)
                {
                    $("#city_select").html(html);
                }
            });
        });
    </script>
@stop