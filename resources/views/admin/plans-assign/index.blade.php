@extends('admin.layout.master')
@section('title') Plan Assign List @stop


@section('page_name')
    Plan Assign Management
    <small>All Plan Assigns</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.plan-assigns', 'Plan Assign Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! route('admin.plan-assign.new') !!}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="la la-plus"></span>&nbsp; New Plan Assign
                        </a>
                    </h3>
                </div>
            </div>
{{--            <div class="m-portlet__head-tools">--}}
{{--                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group date-show-container export-date-show-container" role="group" aria-label="First group" style="display:none; margin-left: 5px; margin-right: 5px">--}}
{{--                    <span class="m--font-brand"><b>Export from :</b></span> <span class="start-select-date" name=""> </span> <span class="m--font-brand"><b>To</b></span> <span class="end-select-date" name=""> </span>--}}
{{--                </div>--}}

{{--                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group" role="group" aria-label="First group">--}}
{{--                    <span class="m-btn btn btn-light m-loader m-loader--brand m-loader--right m-loader--lg" id="loading-indicator" style="display: none" ></span>--}}
{{--                </div>--}}

{{--                <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="First group">--}}
{{--                    <button type="button" class="m-btn btn btn-primary export-file" title="Print" name="xlsx">--}}
{{--                        <i class="fa fa-file-excel-o"></i>--}}
{{--                    </button>--}}
{{--                    <button type="button" class="m-btn btn btn-success export-file" name="csv" title="Csv">--}}
{{--                        <i class="fa fa-file-o"></i>--}}
{{--                    </button>--}}
{{--                    <button type="button" class="m-btn btn btn-brand export-file" title="Pdf" name="pdf">--}}
{{--                        <i class="fa fa-file-pdf-o"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <table id="plan-assign-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                        <tr>
                            <th>Sl.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Is active</th>
                            <th>Joining Date</th>
                            <th>Action</th>

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
    <div class="modal fade" id="plan-assign-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <table id="plan-details" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                    <thead class="">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Plan Name</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                    </thead>
                                    <tbody id="plan-details-body"></tbody>
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
            var table = $('#plan-assign-datatable').DataTable({
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
                    url: '{!! route('admin.datatable.plan-assign') !!}',
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
                        data: 'full_name',
                        name: 'full_name',
                        searchable: true,
                        render: function (data, type, row, meta) {
                            return '<a href="#" style="cursor: pointer" onclick="viewPlans('+row.merchant_id+')">'+row.full_name+'</a>';
                        }
                    },
                    {
                        data: 'm_email',
                        name: 'm_email',
                        searchable: true
                    },
                    {
                        data: 'm_mobile',
                        name: 'm_mobile',
                        searchable: true
                    },
                    {
                        data: 'merch_status',
                        name: 'merch_status',
                        searchable: false,
                        orderable: false,
                        render: function ( data, type, row ) {
                            if(row.merch_status==1){
                                return '<i class="fa fa-check m--font-success"></i>';
                            }else{
                                return '<i class="fa fa-remove m--font-danger"></i>';
                            }
                        }
                    },
                    {
                        data: 'joining_date',
                        name: 'joining_date',
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

            $('#plan-assign-datatable_length').addClass('col-lg-6 col-md-6 col-sm-6');
            $('#plan-assign-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air filter-select');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#plan-assign-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');


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
            $('#plan-assign-datatable_filter label:first-child input').attr('placeholder', 'Name, mobile, email');


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
                table.columns(9).search(start_date + ' ~ ' + end_date).draw();


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
        
        function viewPlans(id) {
            $.ajax({
                type: "POST",
                url: "{!! route('admin.plan-assign.view') !!}",
                data: {"_token": "{{ csrf_token() }}", "plan_assign_id": id},
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
                        $("#plan-details-body tr").remove();
                        var sl =1;
                        var content ='';
                        var status ='';
                        $.each(res.result, function (key, val) {
                            console.log(val)
                            if(val['out_time']==null){outTime='-'}else{outTime=val['out_time']}
                            if(val['status']==1){
                                status = '<i class="fa fa-check m--font-success"></i>';
                            }else{
                                status = '<i class="fa fa-remove m--font-danger"></i>';
                            }
                            content +='<tr>';
                            content += '<td>' + sl++ + '</td>';
                            content += '<td>' + val['plan_name'] + '</td>';
                            content += '<td>' + status + '</td>';
                            content += '<td>' + val['createTime'] + '</td>';
                            content +='</tr>';
                        });
                        content +='';
                        $('#plan-details-body').append(content);
                        $("#plan-assign-Modal").modal('show');
                    }
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
                url: "{!! route('admin.plan-assigns.export') !!}",
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
    </script>
@stop