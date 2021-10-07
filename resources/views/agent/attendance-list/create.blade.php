@extends('agent.layout.master')
@section('title') Create New Attendance Policy @stop


@section('page_name')
    Attendance Policy Management
    <small>New Attendance Policy</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('attendance.policy', 'Attendance Policy Management') !!} </li>
    <li class="active"> {!! link_to_route('attendance.policy.create', 'New Attendance Policy') !!} </li>
@stop

@section('content')
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Add New Attendance Policy
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'attendance.policy.store', 'role' => 'form', 'method' => 'post', 'id' => "add-attendancePolicyHead","enctype"=>"multipart/form-data"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('day_name') ? 'has-error' : '' !!}">
                                <label>Day Name <span class="text-danger">*</span></label>
                                {!! Form::text('day_name', null, ['class' => 'form-control m-input','id'=>'day_name','value'=>Input::old('day_name'), 'placeholder' => 'Enter day name', 'tabindex' => '1']) !!}
                                {!! $errors->first('day_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('in_time') ? 'has-error' : '' !!}">
                                <label>In Time <span class="text-danger">*</span></label>
                                {!! Form::text('in_time', null, ['class' => 'form-control m-input','id'=>'in_time','value'=>Input::old('in_time'), 'placeholder' => 'Enter in time','readonly', 'tabindex' => '1']) !!}
                                {!! $errors->first('in_time', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('working_hours') ? 'has-error' : '' !!}">
                                <label>Working Hours <span class="text-danger">*</span></label>
                                {!! Form::text('working_hours', null, ['class' => 'form-control m-input input_starttime','id'=>'working_hours','value'=>Input::old('working_hours'),'readonly', 'placeholder' => 'Enter working hours', 'tabindex' => '1']) !!}
                                {!! $errors->first('working_hours', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('delay_time') ? 'has-error' : '' !!}">
                                <label>Delay Time <span class="text-danger">*</span></label>
                                {!! Form::text('delay_time', null, ['class' => 'form-control m-input ','id'=>'delay_time','value'=>Input::old('delay_time'), 'placeholder' => 'Enter delay time', 'tabindex' => '1']) !!}
                                {!! $errors->first('delay_time', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('extream_delay_time') ? 'has-error' : '' !!}">
                                <label>Extream Delay Time <span class="text-danger">*</span></label>
                                {!! Form::text('extream_delay_time', null, ['class' => 'form-control m-input','id'=>'extream_delay_time','value'=>Input::old('extream_delay_time'), 'placeholder' => 'Enter extream delay time', 'tabindex' => '1']) !!}
                                {!! $errors->first('extream_delay_time', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('break_time') ? 'has-error' : '' !!}">
                                <label>Break Time <span class="text-danger">*</span></label>
                                {!! Form::text('break_time', null, ['class' => 'form-control m-input ','id'=>'break_time','value'=>Input::old('break_time'), 'placeholder' => 'Enter break time', 'tabindex' => '1']) !!}
                                {!! $errors->first('break_time', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('working_type') ? 'has-error' : '' !!}">
                                <label>Working Type <span class="text-danger">*</span></label>
                                {!! Form::select('working_type',['1' => 'Full Day','2'=>'Half Day','0' => 'Week End'], null, ['class' => 'form-control m-input','id'=>'working_type','value'=>Input::old('working_type'), 'placeholder' => 'Select working type', 'tabindex' => '1']) !!}
                                {!! $errors->first('working_type', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('attendence_head_id') ? 'has-error' : '' !!}">
                                <label>Attendance Head <span class="text-danger">*</span></label>
                                {!! Form::select('attendence_head_id',$heads, null, ['class' => 'form-control m-input','id'=>'attendence_head_id','value'=>Input::old('attendence_head_id'), 'placeholder' => 'Select attendance head', 'tabindex' => '1']) !!}
                                {!! $errors->first('attendence_head_id', '<label class="error_txt_size">:message</label>') !!}
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
                                <a href="{!! route('attendance.policy') !!}" class="btn btn-default" tabindex="20">
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

    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".form_datetime").datetimepicker({
                format: "dd MM yyyy - hh:ii"
            });
            $('#in_time,#working_hours').timepicker();
            $(".reset-form").click(function() {
                $(':input','#add-attendancePolicyHead').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });

    </script>
@stop