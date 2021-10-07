@extends('admin.layout.master')
@section('title') Change admin user password @stop


@section('page_name')
    &nbsp;
    {{--Create New User--}}
    <small></small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.admin-users', 'User Management') !!} </li>
    <li class="active"> {!! link_to_route('admin.change_password', 'Change password') !!} </li>
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
                        Change admin user password
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'admin.change_password', 'role' => 'form', 'method' => 'post', 'id' => "change_password"]) !!}

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
                        <div class="">
                            <div class="form-group m-form__group {!! $errors->has('current_password') ? 'has-error' : '' !!}">
                                <label>Current Password <span class="text-danger">*</span></label>
                                {!! Form::input('password', 'current_password', Input::old('current_password'), ['class' => 'form-control m-input', 'id'=>'password', 'placeholder' => 'Enter current password', 'tabindex' => '5', 'tabindex' => '5']) !!}
                                {!! $errors->first('current_password', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="form-group m-form__group {!! $errors->has('new_password') ? 'has-error' : '' !!}">
                                <label>New Password <span class="text-danger">*</span></label>
                                {!! Form::input('password', 'new_password', Input::old('new_password'), ['class' => 'form-control m-input', 'id'=>'password', 'placeholder' => 'Enter new password', 'tabindex' => '5', 'tabindex' => '5']) !!}
                                {!! $errors->first('new_password', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="form-group m-form__group {!! $errors->has('confirm_new_password') ? 'has-error' : '' !!}">
                                <label>Confirm New Password <span class="text-danger">*</span></label>
                                {!! Form::input('password', 'confirm_new_password', Input::old('confirm_new_password'), ['class' => 'form-control m-input', 'placeholder' => 'Re-type new password', 'tabindex' => '6']) !!}
                                {!! $errors->first('confirm_new_password', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions--solid">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-offser-4 col-md-offset-4 col-sm-offset-4">
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
                                <a href="{!! route('admin.user_profile') !!}" class="btn btn-default" tabindex="20">
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
                $(':input','#change_password').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
    </script>
@stop