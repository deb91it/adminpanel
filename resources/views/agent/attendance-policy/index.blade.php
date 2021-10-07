@extends('agent.layout.master')
@section('title') Attendance Policy List @stop


@section('page_name')
    Attendance Policy Management
    <small>All Attendance Policy List</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('attendance.policy', 'Attendance Policy Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! route('attendance.policy.create') !!}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="la la-plus"></span>&nbsp; New Attendance Policy
                        </a>
                    </h3>
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
                    <table id="attendance-policy-head-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                        <tr>
                            <th>Sl.</th>
                            <th>Head Name</th>
                            <th>Effective From</th>
                            {{--<th>Is_Active</th>--}}
                            <th>Added at</th>
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
    <!--end::Portlet-->
    <!-- Metronic modal -->
    <!--begin::Modal-->
    <div class="modal fade" id="attendancePolicyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="m-portlet__body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <table id="attendance-policy" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                    <thead class="">
                                    <tr>
                                        <th>Day Name</th>
                                        <th>In Time</th>
                                        <th>Working Hours</th>
                                        <th>Delay Time</th>
                                        <th>Extream Delay Time</th>
                                        <th>Break Time</th>
                                        <th>Working Type</th>
                                    </tr>
                                    </thead>
                                    <tbody id="attendance-policy-body"></tbody>
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

    <script type="text/javascript">
        $(document).ready(function(){
            var table = $('#attendance-policy-head-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 25,
                lengthChange: true,
                searching: false,
                ordering: true,
                info: true,
                autoWidth: false,
                dom : 'l<"#date-filter">frtip',
                ajax: {
                    url: '{!! route('attendance.policy.data') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
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
                        searchable: true
                    },
                    {
                        data: 'effective_from',
                        name: 'effective_from',
                        searchable: true
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false
                    },
                    {
                        data: 'action_col',
                        name: 'action_col',
                        searchable: false,
                        orderable: false
                    },
                ],
            });
            $('.dataTables_wrapper').removeClass('form-inline');
            $('.form-control').removeClass('input-sm');
            $('.dataTables_processing').addClass('m-loader m-loader--brand');

            $('#attendance-policy-head-datatable_length').addClass('col-lg-6 col-md-6 col-sm-6');
            $('#attendance-policy-head-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air m_selectpicker');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#attendance-policy-head-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');

            var date_picker_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" id="m_daterangepicker">' +
                '<input type="text" class="form-control m-input" readonly  placeholder="Select date range" value=""/>' +
                '<span class="input-group-addon">' +
                '<i class="fa fa-calendar"></i>' +
                '</span>' +
                '</div>' +
                '</div>';

            // $('#date-filter').append(date_picker_html);
            // $('#attendance-policy-head-datatable_filter label:first-child input').attr('placeholder', 'Name, gorup, moto');


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
                var start_date = start.format('YYYY-MM-DD');
                var end_date = end.format('YYYY-MM-DD');
                table.columns(8).search(start_date + ' ~ ' + end_date).draw();


                // To view beside export buttons
                $('.date-show-container').show();
                $('.start-select-date').html(start.format('DD-MM-YYYY'));
                $('.start-select-date').attr('name', start.format('YYYY-MM-DD'));
                $('.end-select-date').html(end.format('DD-MM-YYYY'));
                $('.end-select-date').attr('name', end.format('YYYY-MM-DD'));
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

        function showHeadDetails(head_id) {
            $.ajax({
                type: "GET",
                url: "{{URL('attendance-policy/view-details')}}/"+head_id,
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    if (res.success === false) {
                        $("#employee-details")[0].reset();
                    } else {
                        var content ='';
                        $.each(res.data, function (i) {
                            content +='<tr>';
                            $.each(res.data[i], function (key, val) {
                                content += '<td>' + val + '</td>';
                            });
                            content +='</tr>';
                        });
                        content +='';
                        $('#attendance-policy-body').append(content);
                    }
                    $('#attendancePolicyModal').modal('show');
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
                url: "{!! route('admin.company.export') !!}",
                data: {"_token": "{{ csrf_token() }}", "export_type": exportType, "start_date": startDate, "end_date": endDate},
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    if (res.success === false) {
                        alert(res.msg);
                    } else {
                        location.href = base_url +'/'+ res.full;
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