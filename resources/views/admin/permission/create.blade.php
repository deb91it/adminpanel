@extends('admin.layout.master')
@section('title') Create New Permission @stop
@section('page_name')
    Permission Management
    <small>New Permission</small>
@stop
@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li> <a href="{{ url('/admin/permission') }}"> Permission </a> </li>
    <li class="active"> <a href="{{ url('/admin/permission/create') }}"> Create Permission </a> </li>
@stop

@section('content')
    <div class="m-portlet m-portlet--tab">
        <!--begin::Form-->
        {!! Form::open(['route' => 'admin.permission.store', 'role' => 'form', 'method' => 'post', 'class'=>'m-form m-form--fit m-form--label-align-right', 'id' => "add-permission"]) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Add New Permission
                    </h3>
                </div>
            </div>
        </div>
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group m-form__group {{{ $errors->has('permission_slug') ? 'has-error' : '' }}}">
                            <label>Permission Slug</label>
                            {!! Form::text('permission_slug', null, ['class' => 'form-control m-input','id'=>'permission_slug','value'=>Input::old('permission_slug'), 'placeholder' => 'Enter Permission Slug']) !!}
                            {!! $errors->first('permission_slug', '<label class="error_txt_size">:message</label>') !!}
                        </div>
                        <div class="form-group m-form__group {{{ $errors->has('display_name') ? 'has-error' : '' }}}">
                            <label>Display Name</label>
                            {!! Form::text('display_name', null, ['class' => 'form-control m-input','id'=>'fast_name','value'=>Input::old('display_name'), 'placeholder' => 'Enter Display Name']) !!}
                            {!! $errors->first('display_name', '<label class="error_txt_size">:message</label>') !!}
                        </div>
                        <div class="form-group m-form__group  {{{ $errors->has('permission_group') ? 'has-error' : '' }}}">
                            <label>Permission Group</label>
                            {!! Form::select('permission_group', $groups, null, array('class' => 'form-control m-input', 'id' => 'permission', 'value'=>Input::old('permission_group'))) !!}
                            {!! $errors->first('permission_group', '<label class="error_txt_size">:message</label>') !!}
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
                                <a href="{!! url('admin/permission') !!}" class="btn btn-default" tabindex="20">
                                    <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!--end::Form-->
        {!! Form::close() !!}
    </div>
    <!--end::Portlet-->

    <script>
         //== Bootstrap select 2
        var Select2 = function() {
            //== Private functions
            var demos = function() {
                // basic
                $('#permission').select2();
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
                $(':input','#add-permission').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
    </script>
@stop