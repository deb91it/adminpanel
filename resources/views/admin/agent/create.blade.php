@extends('admin.layout.master')
@section('title') Create New Rider @stop


@section('page_name')
    Rider Management
    <small>New Rider </small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.agents', 'Rider Management') !!} </li>
    <li class="active"> {!! link_to_route('admin.agent.new', 'New Rider ') !!} </li>
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
                        Add Rider 
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'admin.agent.store', 'role' => 'form', 'method' => 'post', 'id' => "add-agent"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('first_name') ? 'has-error' : '' !!}">
                                <label>First Name <span class="text-danger">*</span></label>
                                {!! Form::text('first_name', null, ['class' => 'form-control m-input','id'=>'fast_name','value'=>Input::old('fast_name'), 'placeholder' => 'Enter First Name', 'tabindex' => '1']) !!}
                                {!! $errors->first('first_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('last_name') ? 'has-error' : '' !!}">
                                <label>Last Name <span class="text-danger">*</span></label>
                                {!! Form::text('last_name', null, ['class' => 'form-control m-input', 'id'=>'last_name','value'=>Input::old('last_name'), 'placeholder' => 'Enter Last Name', 'tabindex' => '2']) !!}
                                {!! $errors->first('last_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('email') ? 'has-error' : '' !!}">
                                <label>Email <span class="text-danger">*</span></label>
                                {!! Form::text('email', null, ['class' => 'form-control m-input', 'id'=>'email','value'=>Input::old('email'), 'placeholder' => 'Enter Email', 'tabindex' => '3']) !!}
                                {!! $errors->first('email', '<label class="error_txt_size">:message</label>') !!}
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('mobile_no') ? 'has-error' : '' !!}">
                                <label>Mobile <span class="text-danger">*</span></label>
                                {!! Form::text('mobile_no', null, ['class' => 'form-control m-input', 'id'=>'mobile_no','value'=>Input::old('mobile_no'), 'placeholder' => 'Enter Mobile', 'tabindex' => '4']) !!}
                                {!! $errors->first('mobile_no', '<label class="error_txt_size">:message</label>') !!}
                            </div> 
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('password') ? 'has-error' : '' !!}">
                                <label>Password <span class="text-danger">*</span></label>
                                {!! Form::password('password', ['class' => 'form-control m-input', 'id'=>'password', 'placeholder' => 'Enter Password', 'tabindex' => '5']) !!}
                                {!! $errors->first('password', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('confirm_pass') ? 'has-error' : '' !!}">
                                <label>Confirm Password <span class="text-danger">*</span></label>
                                {!! Form::password('password_confirmation', ['class' => 'form-control m-input', 'placeholder' => 'Re-type Password', 'tabindex' => '6']) !!}
                                {!! $errors->first('password_confirmation', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('is_active') ? 'has-error' : ''  !!} ">
                                <label>Is active <span class="text-danger">*</span></label>
                                {!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'),null,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker1', 'value'=>Input::old('is_active'), 'tabindex' => '7')) !!}
                                {!! $errors->first('is_active', '<label class="error_txt_size">:message</label>') !!}
                            </div> 
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('can_login') ? 'has-error' : ''  !!} ">
                                <label>Can login <span class="text-danger">*</span></label>
                                {!! Form::select('can_login', array('1' => 'Yes', '0' => 'No'),null,array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker2', 'value'=>Input::old('can_login'), 'tabindex' => '8')) !!}
                                {!! $errors->first('can_login', '<label class="error_txt_size">:message</label>') !!}
                            </div>                     
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('hub_id') ? 'has-error' : '' !!}">
                                <label>Select Hub <span class="text-danger">*</span></label>
                                {!! Form::select('hub_id', $hubs, null, array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker3 ', 'value'=>Input::old('hub_id'), 'tabindex' => '9', 'placeholder' => 'select Hub')) !!}
                                {!! $errors->first('hub_id', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('nid') ? 'has-error' : '' !!}">
                                <label>NID <span class="text-danger">*</span></label>
                                {!! Form::text('nid', null, ['class' => 'form-control m-input', 'id'=>'nid','value'=>Input::old('nid'), 'placeholder' => 'Enter NID', 'tabindex' => '10']) !!}
                                {!! $errors->first('nid', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('zone_id') ? 'has-error' : '' !!}">
                                <label>Select Zone <span class="text-danger">*</span></label>
                                {!! Form::select('zone_id', $cZones, null, array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker3 ', 'value'=>Input::old('zone_id'), 'tabindex' => '11','placeholder' => 'select zone')) !!}
                                {!! $errors->first('zone_id', '<label class="error_txt_size">:message</label>') !!}
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
                                <a href="{!! route('admin.agents') !!}" class="btn btn-default" tabindex="20">
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
                $(':input','#add-agent').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
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