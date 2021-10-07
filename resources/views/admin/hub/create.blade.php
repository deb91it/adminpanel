@extends('admin.layout.master')
@section('title') Create New Hub @stop

@section('page_name')
    Hub  Management
    <small>All Hub </small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.hubs', ' Hub Management') !!} </li>
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
                        Hub creation area
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::open(['route' => 'admin.hub.store', 'role' => 'form', 'method' => 'post', 'id' => "add-Hub", 'enctype' => 'multipart/form-data']) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-portlet__body">
            <div class="m-form m-form--fit m-form--label-align-right ">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                            <div class="form-group m-form__group row">
                                <div class="col-md-6 {!! $errors->has('hub_name') ? 'has-error' : ''  !!}">
                                    <div class="form-group">
                                        <label>Hub Name<span class="text-danger">*</span></label>
                                        {!! Form::text('hub_name', null, ['class' => 'form-control m-input','id'=>'hub_name','value'=>Input::old('hub_name'), 'placeholder' => 'Enter Hub name', 'tabindex' => '1']) !!}
                                        {!! $errors->first('hub_name', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                </div>

                                <div class="col-md-6 {!! $errors->has('address') ? 'has-error' : ''  !!}">
                                    <div class="form-group">
                                        <label>Address<span class="text-danger">*</span></label>
                                        {!! Form::text('address', null, ['class' => 'form-control m-input','id'=>'address','value'=>Input::old('address'), 'placeholder' => 'Enter address', 'tabindex' => '2']) !!}
                                        {!! $errors->first('address', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group m-form__group row">
                                <div class="col-md-6 {!! $errors->has('hub_image') ? 'has-error' : ''  !!}">
                                    <div class="form-group">
                                        <label>Hub Image</label>
                                        {!! Form::File('hub_image', null, ['class' => 'form-control m-input','id'=>'hub_image','value'=>Input::old('hub_image'), 'placeholder' => 'Enter recipient email !', 'tabindex' => '3']) !!}
                                        {!! $errors->first('hub_image', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
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
                                    <a href="{!! route('admin.hubs') !!}" class="btn btn-default" tabindex="20">
                                        {{-- <span class="glyphicon glyphicon-remove text-danger"></span>&nbsp; <span class="text-danger"> Cancel </span>--}}
                                        <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <!--end::Section-->
    </div>
    <!--end::Portlet-->



@stop