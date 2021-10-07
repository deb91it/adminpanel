@extends('agent.layout.master')
@section('title') Create New Employee @stop


@section('page_name')
    Employee Management
    <small>New Employee</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('agents', 'Employee Management') !!} </li>
    <li class="active"> {!! link_to_route('agent.new', 'New Employee') !!} </li>
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
                        Add New Employee
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'agent.store', 'role' => 'form', 'method' => 'post', 'id' => "add-agent"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('first_name') ? 'has-error' : '' !!}">
                                <label>First Name <span class="text-danger">*</span></label>
                                {!! Form::text('first_name', null, ['class' => 'form-control m-input','id'=>'fast_name','value'=>Input::old('fast_name'), 'placeholder' => 'Enter First Name', 'tabindex' => '1']) !!}
                                {!! $errors->first('first_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('last_name') ? 'has-error' : '' !!}">
                                <label>Last Name <span class="text-danger">*</span></label>
                                {!! Form::text('last_name', null, ['class' => 'form-control m-input', 'id'=>'last_name','value'=>Input::old('last_name'), 'placeholder' => 'Enter Last Name', 'tabindex' => '2']) !!}
                                {!! $errors->first('last_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('designation') ? 'has-error' : '' !!}">
                                <label>Designation <span class="text-danger">*</span></label>
                                {!! Form::text('designation', null, ['class' => 'form-control m-input', 'id'=>'designation','value'=>Input::old('designation'), 'placeholder' => 'Enter Designation', 'tabindex' => '2']) !!}
                                {!! $errors->first('designation', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('unique_id') ? 'has-error' : '' !!}">
                                <label>Employee ID <span class="text-danger">*</span></label>
                                {!! Form::text('unique_id', null, ['class' => 'form-control m-input', 'id'=>'unique_id','value'=>Input::old('unique_id'), 'placeholder' => 'Enter Employee ID', 'tabindex' => '3']) !!}
                                {!! $errors->first('unique_id', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('email') ? 'has-error' : '' !!}">
                                <label>Email <span class="text-danger">*</span></label>
                                {!! Form::text('email', null, ['class' => 'form-control m-input', 'id'=>'email','value'=>Input::old('email'), 'placeholder' => 'Enter Email', 'tabindex' => '3']) !!}
                                {!! $errors->first('email', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('mobile_no') ? 'has-error' : '' !!}">
                                <label>Mobile <span class="text-danger">*</span></label>
                                {!! Form::text('mobile_no', null, ['class' => 'form-control m-input', 'id'=>'mobile_no','value'=>Input::old('mobile_no'), 'placeholder' => 'Enter Mobile', 'tabindex' => '4']) !!}
                                {!! $errors->first('mobile_no', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('parent') ? 'has-error' : '' !!}">
                                <label>Parent <span class="text-danger">*</span></label>
                                {!! Form::select('parent', $parents, null, array('class' => 'form-control m-input m-bootstrap-select m-bootstrap-select--air m_selectpicker', 'value'=>Input::old('parent'), 'tabindex' => '11')) !!}
                                {!! $errors->first('parent', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('role') ? 'has-error' : '' !!}">
                                <label>Role <span class="text-danger">*</span></label>
                                {!! Form::select('role', $roles, null, array('class' => 'form-control m-input m-bootstrap-select m-bootstrap-select--air m_selectpicker', 'value'=>Input::old('role'), 'tabindex' => '11')) !!}
                                {!! $errors->first('role', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('username') ? 'has-error' : '' !!}">
                                <label>Username</label>
                                {!! Form::text('username', null, ['class' => 'form-control m-input', 'id'=>'username','value'=>Input::old('username'), 'placeholder' => 'Enter Username', 'tabindex' => '5']) !!}
                                {!! $errors->first('username', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('password') ? 'has-error' : '' !!}">
                                <label>Password <span class="text-danger">*</span></label>
                                {!! Form::password('password', ['class' => 'form-control m-input', 'id'=>'password', 'placeholder' => 'Enter Password', 'tabindex' => '7']) !!}
                                {!! $errors->first('password', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('confirm_pass') ? 'has-error' : '' !!}">
                                <label>Confirm Password <span class="text-danger">*</span></label>
                                {!! Form::password('password_confirmation', ['class' => 'form-control m-input', 'placeholder' => 'Re-type Password', 'tabindex' => '8']) !!}
                                {!! $errors->first('password_confirmation', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('language') ? 'has-error' : '' !!}">
                                <label>Language <span class="text-danger">*</span></label>
                                {!! Form::select('language', $languages, null, array('class' => 'form-control m-input m-bootstrap-select m-bootstrap-select--air m_selectpicker', 'value'=>Input::old('language'), 'tabindex' => '13')) !!}
                                {!! $errors->first('language', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('is_active') ? 'has-error' : ''  !!} ">
                                <label>Is active <span class="text-danger">*</span></label>
                                {!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'),null,array('class' => 'form-control m-input m-bootstrap-select m-bootstrap-select--air m_selectpicker', 'value'=>Input::old('is_active'), 'tabindex' => '9')) !!}
                                {!! $errors->first('is_active', '<label class="error_txt_size">:message</label>') !!}
                            </div> 
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('can_login') ? 'has-error' : ''  !!} ">
                                <label>Can login <span class="text-danger">*</span></label>
                                {!! Form::select('can_login', array('1' => 'Yes', '0' => 'No'),null,array('class' => 'form-control m-input m-bootstrap-select m-bootstrap-select--air m_selectpicker', 'value'=>Input::old('can_login'), 'tabindex' => '10')) !!}
                                {!! $errors->first('can_login', '<label class="error_txt_size">:message</label>') !!}
                            </div>                     
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('country') ? 'has-error' : '' !!}">
                                <label>Country <span class="text-danger">*</span></label>
                                {!! Form::select('country', $countries, null, array('class' => 'form-control m-input m-bootstrap-select m-bootstrap-select--air m_selectpicker selectCountry', 'value'=>Input::old('country'), 'tabindex' => '11')) !!}
                                {!! $errors->first('country', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('city') ? 'has-error' : '' !!}">
                                <label>City <span class="text-danger">*</span></label>
                                {!! Form::select('city', ['First select country'], null, array('class' => 'form-control retrieveZones m-input', 'id' => 'city_select', 'value'=>Input::old('city'), 'tabindex' => '12')) !!}
                                {!! $errors->first('city', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div><!--END LEFT COL -->
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions--solid">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
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
                                <a href="{!! route('agents') !!}" class="btn btn-default" tabindex="20">
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

    <script type="text/javascript">
        $(document).ready(function(){
            $(".reset-form").click(function() {
                $(':input','#add-agent').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });

        //== Bootstrap Select2
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
        
        //City Select
        $(".selectCountry").change(function()
        {
            var country_id = $(this).val();
            if (country_id == '')
                return false;
            $.ajax
            ({
                type: "POST",
                url: "{!! route('agent.employee.zone') !!}",
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