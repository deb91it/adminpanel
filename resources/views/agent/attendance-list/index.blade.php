@extends('agent.layout.master')
@section('title') Attendance Lists @stop


@section('page_name')
    Attendance Lists Management
    <small>All Attendance Lists</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('attendance.list', 'Attendance Lists Management') !!} </li>
@stop

@section('content')
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    {{--<h3 class="m-portlet__head-text">--}}
                        {{--<a href="{!! route('attendance.list.create') !!}" class="btn btn-sm btn-brand m-btn--pill">--}}
                            {{--<span class="la la-plus"></span>&nbsp; New Attendance Lists--}}
                        {{--</a>--}}
                    {{--</h3>--}}
                </div>
            </div>
            {{--<div class="m-portlet__head-tools">--}}
                {{--<div class="m-btn-group m-btn-group--pill btn-group-sm btn-group date-show-container export-date-show-container" role="group" aria-label="First group" style="display:none; margin-left: 5px; margin-right: 5px">--}}
                    {{--<span class="m--font-brand"><b>Export from :</b></span> <span class="start-select-date" name=""> </span> <span class="m--font-brand"><b>To</b></span> <span class="end-select-date" name=""> </span>--}}
                {{--</div>--}}

                {{--<div class="m-btn-group m-btn-group--pill btn-group-sm btn-group" role="group" aria-label="First group">--}}
                    {{--<span class="m-btn btn btn-light m-loader m-loader--brand m-loader--right m-loader--lg" id="loading-indicator" style="display: none" ></span>--}}
                {{--</div>--}}

                {{--<div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="First group">--}}
                    {{--<button type="button" class="m-btn btn btn-primary export-file" title="Print" name="xlsx">--}}
                        {{--<i class="fa fa-file-excel-o"></i>--}}
                    {{--</button>--}}
                    {{--<button type="button" class="m-btn btn btn-success export-file" name="csv" title="Csv">--}}
                        {{--<i class="fa fa-file-o"></i>--}}
                    {{--</button>--}}
                    {{--<button type="button" class="m-btn btn btn-brand export-file" title="Pdf" name="pdf">--}}
                        {{--<i class="fa fa-file-pdf-o"></i>--}}
                    {{--</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <table id="attendance-lists-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                        <tr>
                            <th>Sl.</th>
                            {{--<th>Employee ID</th>--}}
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Attendance Date</th>
                            <th>CheckedIn Time</th>
                            <th>CheckedOut Time</th>
                            <th>Current Status</th>
                            <th><center> Action </center></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot clearfix">
            <div class="pull-right">
            </div>
        </div>
        <!--end::Section-->
    </div>

    <!-- Metronic modal -->
    <!--begin::Modal-->
    <div class="modal fade" id="attendanceListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="post" action="{{route('attendance.list.update')}}" id="attendance-list">
                {{csrf_field()}}
                <input type="hidden" name="row_id" id="row_id">
                <input type="hidden" name="user_id" id="user_id">
                <div class="modal-content">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--font-brand">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        Edit Employee Details
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <span class="description">
                        <div class="msqSuccess" style="color: green"></div>
                        <div class="msqError" style="color: red"></div>
                    </span>
                        <div class="m-form m-form--fit m-form--label-align-right ">
                            <div class="modal-body driver_modal">
                                <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group m-form__group row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('checkedin_time') ? 'has-error' : '' !!}">
                                                    <label>Checkedin Time </label>
                                                    {!! Form::text('checkedin_time', null, ['class' => 'form-control m-input','id'=>'checkedin_time','value'=>Input::old('checkedin_time'), 'placeholder' => 'Enter Check In Time', 'tabindex' => '1']) !!}
                                                    {!! $errors->first('checkedin_time', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('checkedout_time') ? 'has-error' : '' !!}">
                                                    <label>Checkedout Time </label>
                                                    {!! Form::text('checkedout_time', null, ['class' => 'form-control m-input','id'=>'checkedout_time','value'=>Input::old('checkedout_time'), 'placeholder' => 'Enter Check Out Time', 'tabindex' => '1']) !!}
                                                    {!! $errors->first('checkedout_time', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('flag') ? 'has-error' : '' !!}">
                                                    <label>Status </label>
                                                    {!! Form::select('flag',$flags, null, ['class' => 'form-control','id'=>'flag','value'=>Input::old('flag'), 'placeholder' => 'select status', 'tabindex' => '1']) !!}
                                                    {!! $errors->first('flag', '<label class="error_txt_size">:message</label>') !!}
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
                                                    <button class="btn btn-default" type="submit" tabindex="20">
                                                        <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                                                        <span class="text-success"> Save </span>
                                                    </button>
                                                    &nbsp;
                                                    <span class="btn btn-default reset-form" tabindex="20">
                                            <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                                            <span class="text-success"> Reset </span>
                                        </span>
                                                    &nbsp;
                                                    <a href="" data-dismiss="modal"  class="btn btn-default" tabindex="20">
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
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end::Modal-->
    <!--/ Metronic modal -->

    <!-- Metronic modal -->
    <!--begin::Modal-->
    <div class="modal fade" id="currentMonthAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance List Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <div class="m-section">
                                    <div class="m-section__content" style="border: 1px solid #E6E6E6;padding: 10px;">
                                        <!--begin::Form-->
                                            <input type="hidden" id="search_user_id">
                                            <div class="form-group m-form__group row">
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 {!! $errors->has('gender') ? 'has-error' : ''  !!} ">
                                                    <label> Select Month </label>
                                                    <select class="form-control m_selectpicker" id="month_select" name="month">
                                                        <option value="01"> January </option>
                                                        <option value="02"> February </option>
                                                        <option value="03"> March </option>
                                                        <option value="04"> April </option>
                                                        <option value="05"> May </option>
                                                        <option value="06"> June </option>
                                                        <option value="07"> July </option>
                                                        <option value="08"> August </option>
                                                        <option value="09"> September </option>
                                                        <option value="10"> October </option>
                                                        <option value="11"> November </option>
                                                        <option value="12"> December </option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 {!! $errors->has('year') ? 'has-error' : ''  !!} ">
                                                    <label>Year<span class="text-danger">*</span></label>
                                                    {!! Form::text('year',date("Y"),array('class' => 'form-control m-input m-bootstrap-select m_selectpicker','readonly', 'value'=>Input::old('year'), 'tabindex' => '6')) !!}
                                                    {!! $errors->first('year', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 {!! $errors->has('year') ? 'has-error' : ''  !!} ">
                                                    <label> </label>
                                                    <button class="btn btn-brand m-btn m-btn--icon m-2" onclick="searchAttendanceDetails()"><i class="fa fa-search">&nbsp;&nbsp;Search</i></button>
                                                </div>
                                            </div>
                                    <!--end::Form-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <table id="attendance-details" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                    <thead class="">
                                    <tr>
                                        <th>Date</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody id="attendance-details-body"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot clearfix">
                    <div class="pull-right">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--/ Metronic modal -->

    <!--end::Portlet-->
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            var table = $('#attendance-lists-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 25,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                dom : 'l<"#date-filter"><"#role-filter">frtip',
                ajax: {
                    url: '{!! route('attendance.list.data') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                        d.from_date = "{{ date("Y-m-d") }}";
                        d.to_date = "{{ date("Y-m-d") }}";
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }

                    },
                    // {
                    //     data: 'm_employee_id',
                    //     name: 'm_employee_id',
                    //     defaultContent:"-",
                    //     searchable: false
                    // },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        defaultContent:"-",
                        searchable: true,
                        render: function ( data, type, row ) {
                                return row.full_name+'<br><small style="color: #716aca;">'+row.m_employee_id+'</small>';
                        }
                    },
                    {
                        data: 'designation',
                        name: 'designation',
                        defaultContent:"-",
                        searchable: true
                    },
                    {
                        data: 'attendance_date',
                        name: 'attendance_date',
                        defaultContent:"-",
                        searchable: false
                    },
                    {
                        data: 'c_time',
                        name: 'c_time',
                        defaultContent:"-",
                        searchable: false,
                    },
                    {
                        data: 'o_time',
                        name: 'o_time',
                        defaultContent:"-",
                        searchable: false,
                    },
                    {
                        data: 'current_status',
                        name: 'current_status',
                        defaultContent:"absent",
                        searchable: false,
                    },
                    {
                        data: 'action_col',
                        name: 'action_col',
                        defaultContent:"-",
                        searchable: false,
                        orderable: false
                    },
                ],
            });

            $('.dataTables_wrapper').removeClass('form-inline');
            $('.form-control').removeClass('input-sm');
            $('.dataTables_processing').addClass('m-loader m-loader--brand');

            $('#attendance-lists-datatable_length').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#attendance-lists-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air m_selectpicker');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#role-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#attendance-lists-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');

            $('#checkedin_time,#checkedout_time').timepicker();
            $(".reset-form").click(function() {
                $(':input','#attendance-list').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            });


            var date_picker_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" >' +
                '<input type="text" class="form-control date_class" id="m_date"  placeholder="Select date range" value=""/>' +
                '<span class="input-group-addon">' +
                '<i class="fa fa-calendar"></i>' +
                '</span>' +
                '</div>' +
                '</div>';

            $('#date-filter').append(date_picker_html);

            var select_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" >' +
                // '<label>select role</label>' +
                '<select id="company_role" class="form-control m_selectpicker1"><option value="">Filter by user role</option><?php
                    if(!empty($company_roles)){
                    foreach ($company_roles as $role){
                        echo '<option value="'.$role->id.'">'.$role->role_name.'</option>';
                    }
                }?></select>' +
                '</div>' +
                '</div>';

            $('#role-filter').append(select_html);
            $('#attendance-lists-datatable_filter label:first-child input').attr({'placeholder':'By employee id, name, designation'});


            $('#m_date').change(function() {
                var selected_date = $(this).val();
                table.columns(4).search(selected_date + ' ~ ' + selected_date).draw();
            });
            $('#company_role').change(function() {
                var role = $(this).val();
                table.columns(0).search(role).draw();
            });
            // $('#attendance_list_search').change(function() {
            //     var keyword = $(this).val();
            //     table.columns(1).search(keyword).draw();
            // });
            $('.date_class').datetimepicker({
                format: 'yyyy-mm-dd',
                minView: "month",
            });

            //For clear the input field
            function tog(v){
                return v?'addClass':'removeClass';
            }
            $(document).on('input', '.clearable', function(){
                $(this)[tog(this.value)]('x');
            }).on('change', '.clearable', function( e ){
                $(this)[tog(this.value)]('x');
            }).on('mousemove', '.x', function( e ){
                $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
            }).on('touchstart click', '.onX', function( ev ){
                ev.preventDefault();
                $(this).removeClass('x onX').val('').change();
                table.columns(9).search('').draw();
            });

        });
        function editAttendance(user_id) {
            console.log(user_id)
            $.ajax({
                type: "post",
                url: "{!! route('attendance.list.edit') !!}",
                data: {"_token": "{{ csrf_token() }}", "user_id": user_id},
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    console.log(res);
                    if (res.success === false) {
                        $("#attendance-list")[0].reset();
                    } else {
                        $("#checkedin_time").val(res.response.checkedin_time);
                        $("#checkedout_time").val(res.response.checkedout_time);
                        $("#flag").val(res.response.flag);
                        $("#row_id").val(res.response.id);
                    }
                    $("#user_id").val(res.response.user_id);
                    $('#attendanceListModal').modal('show');
                }
            });
        }

        function viewAttendanceList(user_id) {
            $.ajax({
                type: "get",
                url: "{!! url('attendance-list/view-details') !!}/"+user_id,
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    console.log(res);
                    if (res.success === false) {
                        $("#attendance-list")[0].reset();
                    } else {
                        $("#attendance-details-body tr").remove();
                        var content ='';
                        var status ='';
                        $.each(res.result, function (key, val) {
                            if(val['out_time']==null){outTime='-'}else{outTime=val['out_time']}
                            if(val['status']==null){status='-'}else{status=val['status']}
                            content +='<tr>';
                            content += '<td>' + val['date'] + '</td>';
                            content += '<td>' + val['in_time'] + '</td>';
                            content += '<td>' + outTime + '</td>';
                            content += '<td>' + status + '</td>';
                            content +='</tr>';
                        });
                        content +='';
                        $('#attendance-details-body').append(content);
                        $('#search_user_id').val(res.user_id);
                    }
                    $('#currentMonthAttendanceModal').modal('show');
                }
            });
        }

        function searchAttendanceDetails() {
            $.ajax({
                type: "get",
                url: "{!! route('attendance.list.search') !!}",
                data: {"month": $("#month_select").val(), "user_id": $('#search_user_id').val()},
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    if (res.success === false) {
                        $("#attendance-list")[0].reset();
                    } else {
                        $("#attendance-details-body tr").remove();
                        var content ='';
                        var outTime ='';
                        var status ='';
                        $.each(res.result, function (key, val) {
                            if(val['out_time']==null){outTime='-'}else{outTime=val['out_time']}
                            if(val['status']==null){status='-'}else{status=val['status']}
                            content +='<tr>';
                            content += '<td>' + val['date'] + '</td>';
                            content += '<td>' + val['in_time'] + '</td>';
                            content += '<td>' + outTime + '</td>';
                            content += '<td>' + status + '</td>';
                            content +='</tr>';
                        });
                        content +='';
                        $('#attendance-details-body').append(content);
                        $('#search_user_id').val(res.user_id);
                    }
                    $('#currentMonthAttendanceModal').modal('show');
                }
            });
        }

    </script>


    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            border:0px!important;
            padding:0px!important;
        }
        .dataTables_length{
            margin-bottom:10px;
        }
        .dataTables_length .form-control {
            margin-left: 5px;
            margin-right: 5px;
        }
        table.dataTable.no-footer {
            border-bottom:1px solid #f4f4f4!important;
        }
        .bs-caret{
            display: none;
        }
    </style>


    <script type="text/javascript">
        // For export data
        $(document).on('click','.export-file',function(e)
        {
            e.preventDefault();
            var base_url = "{{ url('/') }}";
            var exportType = $(this).attr('name');
            var startDate = $('.start-select-date').attr('name');
            var endDate = $('.end-select-date').attr('name');
            $.ajax({
                type: "POST",
                url: "{!! route('attendance.list.export') !!}",
                data: {"_token": "{{ csrf_token() }}", "export_type": exportType, "start_date": startDate, "end_date": endDate},
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    console.log(res);
                    if (res.success === false) {
                        alert(res.msg);
                    } else {
                      //  location.href = base_url +'/'+ res.full;
                    }
                }
            });

        });

        function confirmDelete(){
            var agree = confirm("Are you sure you want to delete this company ?");
            if (agree) {
                return true;
            } else {
                return false;
            }
        }
    </script>
@stop