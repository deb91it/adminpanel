@extends('agent.layout.master')
@section('title') Create New Attendance Policy Head @stop


@section('page_name')
    Attendance Policy Head Management
    <small>New Attendance Policy Head</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('attendance.policy.head', 'Attendance Policy Head Management') !!} </li>
    <li class="active"> {!! link_to_route('attendance.policy.head.create', 'New Attendance Policy Head') !!} </li>
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
                        Add New Attendance Policy Head
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'attendance.policy.head.store', 'role' => 'form', 'method' => 'post', 'id' => "add-attendancePolicyHead","enctype"=>"multipart/form-data"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('name') ? 'has-error' : '' !!}">
                                <label>Policy Head Name <span class="text-danger">*</span></label>
                                {!! Form::text('name', null, ['class' => 'form-control m-input','id'=>'name','value'=>Input::old('name'), 'placeholder' => 'Enter policy head name', 'tabindex' => '1']) !!}
                                {!! $errors->first('name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('effective_from') ? 'has-error' : '' !!}">
                                <label>Effective from <span class="text-danger">*</span></label>
                                {!! Form::text('effective_from', null, ['class' => 'form-control m-input form_datetime','id'=>'effective_from','value'=>Input::old('name'), 'placeholder' => 'Enter effective date & time','readonly', 'tabindex' => '1']) !!}
                                {!! $errors->first('effective_from', '<label class="error_txt_size">:message</label>') !!}
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
                                <a href="{!! route('attendance.policy.head') !!}" class="btn btn-default" tabindex="20">
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
            $(".form_datetime").datetimepicker({
                format: "dd MM yyyy - hh:ii"
            });
            $(".reset-form").click(function() {
                $(':input','#add-attendancePolicyHead').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });

    </script>
@stop