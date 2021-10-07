@extends('agent.layout.master')
@section('title') Orders @stop


@section('page_name')
    Orders Management
    <small>All Orders</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('order.list', 'Orders Management') !!} </li>
@stop

@section('content')
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    {{--<h3 class="m-portlet__head-text">--}}
                        {{--<a href="{!! route('order.list.create') !!}" class="btn btn-sm btn-brand m-btn--pill">--}}
                            {{--<span class="la la-plus"></span>&nbsp; New Orders--}}
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
                    <table id="order-lists-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                        <tr>
                            <th>Sl.</th>
                            {{--<th>Employee ID</th>--}}
                            <th>Outlet Details</th>
                            <th>Sales Representative</th>
                            <th>Has Meeting</th>
                            <th>Remarks</th>
                            <th>Order Weight</th>
                            <th>Order Amount</th>
                            <th>Order Time</th>
{{--                            <th><center> Action </center></th>--}}
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
    <div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
                <input type="hidden" name="row_id" id="row_id">
                <input type="hidden" name="user_id" id="user_id">
                <div class="modal-content">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--font-brand">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                       View Remarks
                                    </h3>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -40px;">
                                    <span aria-hidden="true" >x</span>
                                </button>
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
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <h3>Remarks </h3>
                                                    <div style="border: 1px solid #E9E8E8; padding: 15px;"><p style="align:justify" id="remarks_val"></p></div>
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
    <div class="modal fade" id="currentMonthorderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Orders Details</h5>
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
                                                    <button class="btn btn-brand m-btn m-btn--icon m-2" onclick="searchorderDetails()"><i class="fa fa-search">&nbsp;&nbsp;Search</i></button>
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
                                <table id="order-details" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                    <thead class="">
                                    <tr>
                                        <th>Date</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody id="order-details-body"></tbody>
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

            var table = $('#order-lists-datatable').DataTable({
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
                    url: '{!! route('order.list.data') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                        d.from_date = "{{ !empty($from) ? $from:'' }}";
                        d.to_date = "{{ !empty($to) ? $to:'' }}";
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
                    {
                        data: 'name',
                        name: 'name',
                        defaultContent:"-",
                        searchable: true,
                        render: function ( data, type, row ) {
                            return row.name+'<br><small style="color: #716aca;">'+row.address+'</small>';
                        }
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        defaultContent:"-",
                        searchable: true,
                        render: function ( data, type, row ) {
                            return row.full_name+'<br><small style="color: #716aca;">'+row.unique_id+'</small>';
                        }
                    },
                    {
                        data: 'has_meeting',
                        name: 'has_meeting',
                        defaultContent:"-",
                        searchable: false,
                        render: function ( data, type, row ) {
                            var value='-';
                            if(row.has_meeting == 1){
                                value = '<label class="label label-success" style="padding: 4px 10px 4px 10px;">Yes</label>'+'<br><small style="color: #716aca;">'+row.meeting_datetime+'</small>';
                            }
                            return value;
                        }
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        defaultContent:"-",
                        searchable: false,
                        render: function ( data, type, row ) {
                            var value='-';
                            if(row.remarks != ''){
                                value = '<i class="fa fa-eye fa-2x" style="color: #00A000;cursor: pointer" onclick="viewRemarks('+row.id+')" title="View Details"></i>';
                            }
                            return value;
                        }
                    },
                    {
                        data: 'ordered_weight',
                        name: 'ordered_weight',
                        defaultContent:"-",
                        searchable: false
                    },
                    {
                        data: 'ordered_amount',
                        name: 'ordered_amount',
                        defaultContent:"-",
                        searchable: false,
                    },
                    {
                        data: 'order_time',
                        name: 'order_time',
                        defaultContent:"-",
                        searchable: false,
                    },
                    // {
                    //     data: 'action_col',
                    //     name: 'action_col',
                    //     defaultContent:"-",
                    //     searchable: false,
                    //     orderable: false
                    // },
                ],
            });

            $('.dataTables_wrapper').removeClass('form-inline');
            $('.form-control').removeClass('input-sm');
            $('.dataTables_processing').addClass('m-loader m-loader--brand');

            $('#order-lists-datatable_length').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#order-lists-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air m_selectpicker');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#role-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#order-lists-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');

            $('#checkedin_time,#checkedout_time').timepicker();
            $(".reset-form").click(function() {
                $(':input','#order-list').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            });


            var date_picker_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" >' +
                '<input type="text" class="form-control daterangepick_value" id="m_daterangepicker"  placeholder="Select date range" value=""/>' +
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
                '<select id="company_role" class="form-control m_selectpicker1"><option value="">Filter </option>' +
                '<option value="has_meeting" {{ !empty($type) && $type == 'has_meeting' ? 'selected' : '' }}>Has Meeting</option>' +
                '<option value="remarks" {{ !empty($type) && $type == 'remarks' ? 'selected' : '' }}>Remarks</option></select>' +
                '</div>' +
                '</div>';

            $('#role-filter').append(select_html);
            $('#order-lists-datatable_filter label:first-child input').attr({'placeholder':'By employee id, name, Outlet Name'});

            $('#m_daterangepicker').daterangepicker({
                buttonClasses: 'm-btn btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                format: 'YYYY-MM-DD'

            }, function(start, end, label) {
                $('#m_daterangepicker .form-control').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                $(".daterangepick_value").text('ssss');
                var start_date = start.format('YYYY-MM-DD');
                var end_date = end.format('YYYY-MM-DD');
                table.columns(7).search(start_date + ' ~ ' + end_date).draw();


                // To view beside export buttons
                $('.date-show-container').show();
                $('.start-select-date').html(start.format('DD-MM-YYYY'));
                $('.start-select-date').attr('name', start.format('YYYY-MM-DD'));
                $('.end-select-date').html(end.format('DD-MM-YYYY'));
                $('.end-select-date').attr('name', end.format('YYYY-MM-DD'));
            });

            $('#company_role').change(function() {
                var role = $(this).val();
                table.columns(0).search(role).draw();
            });
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
        function viewRemarks(order_id) {
            $.ajax({
                type: "post",
                url: "{!! route('order.list.remarks') !!}",
                data: {"_token": "{{ csrf_token() }}", "order_id": order_id},
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    if (res.success === false) {
                        $("#order-list")[0].reset();
                    } else {
                        $("#remarks_val").text(res.remarks);
                    }
                    $('#remarksModal').modal('show');
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
        {{--$(document).on('click','.export-file',function(e)--}}
        {{--{--}}
        {{--    e.preventDefault();--}}
        {{--    var base_url = "{{ url('/') }}";--}}
        {{--    var exportType = $(this).attr('name');--}}
        {{--    var startDate = $('.start-select-date').attr('name');--}}
        {{--    var endDate = $('.end-select-date').attr('name');--}}
        {{--    $.ajax({--}}
        {{--        type: "POST",--}}
        {{--        url: "{!! route('order.list.export') !!}",--}}
        {{--        data: {"_token": "{{ csrf_token() }}", "export_type": exportType, "start_date": startDate, "end_date": endDate},--}}
        {{--        cache: false,--}}
        {{--        beforeSend: function(){--}}
        {{--            $('#loading-indicator').show();--}}
        {{--        },--}}
        {{--        complete: function(){--}}
        {{--            $('#loading-indicator').hide();--}}
        {{--        },--}}
        {{--        success: function (res) {--}}
        {{--            console.log(res);--}}
        {{--            if (res.success === false) {--}}
        {{--                alert(res.msg);--}}
        {{--            } else {--}}
        {{--              //  location.href = base_url +'/'+ res.full;--}}
        {{--            }--}}
        {{--        }--}}
        {{--    });--}}

        {{--});--}}

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