@extends('admin.layout.master')
@section('title') Edit Email Configuration @stop


@section('page_name')
    Email Configuration Management
    <small>Add/Edit Email Configuration</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.mail.configure', 'Configuration Management') !!} </li>
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
                        Email Configuration
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => ['admin.mail.configure.update'], 'role' => 'form', 'method' => 'post', 'id' => "add-Email Configuration"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ !empty($conf) ? $conf->id : '' }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('mail_driver') ? 'has-error' : '' !!}">
                                <label>MAIL DRIVER <span class="text-danger">*</span></label>
                                <input type="text" name="mail_driver" class="form-control" value="{{!empty($conf) ? $conf->mail_driver : ''}}" placeholder="MAIL DRIVER">
                                {!! $errors->first('mail_driver', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('mail_host') ? 'has-error' : '' !!}">
                                <label>MAIL HOST <span class="text-danger">*</span></label>
                                <input type="text" name="mail_host" class="form-control" value="{{!empty($conf) ? $conf->mail_host : ''}}" placeholder="MAIL HOST">
                                {!! $errors->first('mail_host', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('mail_port') ? 'has-error' : '' !!}">
                                <label>MAIL PORT <span class="text-danger">*</span></label>
                                <input type="text" name="mail_port" class="form-control" value="{{!empty($conf) ? $conf->mail_port : ''}}" placeholder="MAIL PORT">
                                {!! $errors->first('mail_port', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('mail_username') ? 'has-error' : '' !!}">
                                <label>MAIL USERNAME <span class="text-danger">*</span></label>
                                <input type="text" name="mail_username" class="form-control" value="{{!empty($conf) ? $conf->mail_username : ''}}" placeholder="MAIL USERNAME">
                                {!! $errors->first('mail_username', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('mail_password') ? 'has-error' : '' !!}">
                                <label>MAIL PASSWORD <span class="text-danger">*</span></label>
                                <input type="password" name="mail_password" class="form-control" value="{{!empty($conf) ? $conf->mail_password : ''}}" placeholder="MAIL PASSWORD">
                                {!! $errors->first('mail_password', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('mail_encryption') ? 'has-error' : '' !!}">
                                <label>MAIL ENCRYPTION </label>
                                <input type="text" name="mail_encryption" class="form-control" value="{{!empty($conf) ? $conf->mail_encryption : ''}}" placeholder="MAIL ENCRYPTION">
                                {!! $errors->first('mail_encryption', '<label class="error_txt_size">:message</label>') !!}
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
                                <a href="{!! route('admin.dashboard') !!}" class="btn btn-default" tabindex="20">
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


@stop
