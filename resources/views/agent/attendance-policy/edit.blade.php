@extends('agent.layout.master')
@section('title') Edit Attendance Policy @stop


@section('page_name')
    Attendance Policy Management
    <small>Edit Attendance Policy</small>
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
                        Edit New Attendance Policy
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'attendance.policy.update', 'role' => 'form', 'method' => 'post', 'id' => "add-attendancePolicyHead","enctype"=>"multipart/form-data"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="attendance_head_id" value="{{ !empty($head) ? $head->id : '' }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('attendence_head_name') ? 'has-error' : '' !!}">
                                <label>Policy Name <span class="text-danger">*</span></label>
                                {!! Form::text('attendence_head_name', !empty($head) ? $head->name : '', ['class' => 'form-control m-input','id'=>'attendence_head_name','value'=>Input::old('attendence_head_name'), 'placeholder' => 'Enter attendance head name', 'tabindex' => '1']) !!}
                                {!! $errors->first('attendence_head_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('effective_from') ? 'has-error' : '' !!}">
                                <label>Effective from <span class="text-danger">*</span></label>
                                {!! Form::text('effective_from', !empty($head) ? date('d F Y - H:i',strtotime($head->effective_from)) : '', ['class' => 'form-control m-input form_datetime','id'=>'effective_from','value'=>Input::old('name'), 'placeholder' => 'Enter effective date & time','readonly', 'tabindex' => '1']) !!}
                                {!! $errors->first('effective_from', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div style="padding: 10px">
                            <table class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                <thead class="">
                                <tr>
                                    <th style="width: 30%">Day Name</th>
                                    <th>In Time</th>
                                    <th>Working Hours</th>
                                    <th>Delay Time</th>
                                    <th>Extream Delay Time</th>
                                    <th>Break Time</th>
                                    <th>Working Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($formData as $key=>$data)
                                    <input type="hidden" name="row_id[{{$key}}]" value="{{ $data->id }}">
                                    <tr>
                                        <td style="width: 40%">
                                            <div class="{!! $errors->has('day_name') ? 'has-error' : '' !!}">
                                                <span>{{$data->day_name}}</span>
                                                {!! Form::hidden('day_name['.$key.']', $data->day_name, ['class' => 'form-control m-input','id'=>'day_name['.$key.']','value'=>Input::old('day_name['.$key.']'), 'placeholder' => 'Enter day name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('day_name', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="{!! $errors->has('in_time') ? 'has-error' : '' !!}">
                                                {!! Form::text('in_time['.$key.']', $data->in_time, ['class' => 'form-control m-input date_time','id'=>'in_time['.$key.']','value'=>Input::old('in_time['.$key.']'), 'placeholder' => 'Enter in time','readonly', 'tabindex' => '1']) !!}
                                                {!! $errors->first('in_time', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class=" {!! $errors->has('working_hours') ? 'has-error' : '' !!}">
                                                {!! Form::text('working_hours['.$key.']', $data->working_hours, ['class' => 'form-control m-input date_time','id'=>'working_hours['.$key.']','value'=>Input::old('working_hours['.$key.']'),'readonly', 'placeholder' => 'Enter working hours', 'tabindex' => '1']) !!}
                                                {!! $errors->first('working_hours', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </td>
                                        <td class="agent-table-parent">
                                            <div class=" {!! $errors->has('delay_time') ? 'has-error' : '' !!}">
                                                {!! Form::text('delay_time['.$key.']', $data->delay_time, ['class' => 'form-control m-input ','id'=>'delay_time['.$key.']','value'=>Input::old('delay_time['.$key.']'), 'placeholder' => 'Enter delay time', 'tabindex' => '1']) !!}
                                                {!! $errors->first('delay_time', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </td>
                                        <td class="">
                                            <div class="{!! $errors->has('extream_delay_time') ? 'has-error' : '' !!}">
                                                {!! Form::text('extream_delay_time['.$key.']', $data->extream_delay_time, ['class' => 'form-control m-input','id'=>'extream_delay_time['.$key.']','value'=>Input::old('extream_delay_time['.$key.']'), 'placeholder' => 'Enter extream delay time', 'tabindex' => '1']) !!}
                                                {!! $errors->first('extream_delay_time', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="{!! $errors->has('break_time') ? 'has-error' : '' !!}">
                                                {!! Form::text('break_time['.$key.']', $data->break_time, ['class' => 'form-control m-input ','id'=>'break_time['.$key.']','value'=>Input::old('break_time['.$key.']'), 'placeholder' => 'Enter break time', 'tabindex' => '1']) !!}
                                                {!! $errors->first('break_time', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class=" {!! $errors->has('working_type') ? 'has-error' : '' !!}">
                                                {!! Form::select('working_type['.$key.']',['1' => 'Full Day','2'=>'Half Day','0' => 'Week End'], $data->working_type, ['class' => 'form-control m-input','id'=>'working_type['.$key.']','value'=>Input::old('working_type['.$key.']'), 'placeholder' => 'Select working type', 'tabindex' => '1']) !!}
                                                {!! $errors->first('working_type', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-danger">{{ "No data found" }}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
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
            $('.date_time').timepicker();
            $(".reset-form").click(function() {
                $(':input','#add-attendancePolicyHead').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });

    </script>
@stop