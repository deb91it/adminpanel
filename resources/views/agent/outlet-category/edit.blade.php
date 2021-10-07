@extends('agent.layout.master')
@section('title') Create New Outlet Category @stop


@section('page_name')
    Outlet Category Management
    <small>Edit Outlet Category</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('outlet.category', 'Outlet Category Management') !!} </li>
    <li class="active"> {!! link_to_route('outlet.category.create', 'New Outlet Category') !!} </li>
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
                        Edit Outlet Category
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'outlet.category.update', 'role' => 'form', 'method' => 'post', 'id' => "edit-outlet","enctype"=>"multipart/form-data"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="rowid" value="{{!empty($formData->id) ? $formData->id : ''}}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('name') ? 'has-error' : '' !!}">
                                <label>Category Name <span class="text-danger">*</span></label>
                                {!! Form::text('name', !empty($formData->name) ? $formData->name :"", ['class' => 'form-control m-input','id'=>'name','value'=>Input::old('name'), 'placeholder' => 'Enter Category Name', 'tabindex' => '1']) !!}
                                {!! $errors->first('name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            {{--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('status') ? 'has-error' : '' !!}">--}}
                                {{--<label>is_active <span class="text-danger">*</span></label>--}}
                                {{--{!! Form::select('status', [1=>"active",0=>"inactive"],!empty($formData->status) ? $formData->status :"", ['class' => 'form-control m-input', 'id'=>'status','value'=>Input::old('status'), 'placeholder' => 'Select status', 'tabindex' => '2']) !!}--}}
                                {{--{!! $errors->first('status', '<label class="error_txt_size">:message</label>') !!}--}}
                            {{--</div>--}}
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
                                <a href="{!! route('outlet.category') !!}" class="btn btn-default" tabindex="20">
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
                $(':input','#edit-outlet').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });

    </script>

@stop