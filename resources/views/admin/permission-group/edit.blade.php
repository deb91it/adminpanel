@extends('admin.layout.master')
@section('title') Permission Group @stop


@section('page_name')
    Permission Group
	<small></small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li> <a href="{{ url('/admin/permission-group') }}"> Permission Group </a> </li>
    <li class="active"> <a href="{{ url('/admin/permission-group/'.$group->id.'/edit/') }}"> Edit Permission Group </a> </li>
@stop

@section('content')
    <div class="m-portlet m-portlet--tab">
        <!--begin::Form-->
        {!! Form::open(['route' => ['admin.permission-group.update',$group->id], 'role' => 'form', 'method' => 'post', 'class'=>'m-form m-form--fit m-form--label-align-right', 'id' => "edit_permission_group"]) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Edit Permission Group
                    </h3>
                </div>
            </div>
        </div>
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group {{{ $errors->has('group_name') ? 'has-error' : '' }}}">
                            <label>Permission Group Name</label>
                            {!! Form::text('group_name', $group->group_name, ['class' => 'form-control m-input m-input--square','id'=>'fast_name','value'=>Input::old('group_name'), 'placeholder' => 'Enter Group Name']) !!}
                            {!! $errors->first('group_name', '<label class="error_txt_size">:message</label>') !!}
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
                                    <span class="text-success"> Save Changes</span>
                                </button>
                                &nbsp;
                                <span class="btn btn-default reset-form" tabindex="20">
                                    <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                                    <span class="text-success"> Reset </span>
                                </span>
                                &nbsp;
                                <a href="{!! url('admin/permission-group') !!}" class="btn btn-default" tabindex="20">
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
    <script type="text/javascript">
        $(document).ready(function(){
            $(".reset-form").click(function() {
                $(':input','#edit_permission_group').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
    </script>
@stop