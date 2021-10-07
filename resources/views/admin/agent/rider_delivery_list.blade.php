@extends('admin.layout.master')
@section('title') Delivery List @stop


@section('page_name')
    Delivery Management
    <small>All Delivery</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.agents', 'Delivery Management') !!} </li>
@stop

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{$rider->first_name." ".$rider->last_name}} delivery lists
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group date-show-container export-date-show-container" role="group" aria-label="First group" style="display:none; margin-left: 5px; margin-right: 5px">
                    <span class="m--font-brand" id=""><b>Date from :</b></span> <span class="start-select-date" name=""> </span> <span class="m--font-brand"><b>To</b></span> <span class="end-select-date" name=""> </span>
                </div>

                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group" role="group" aria-label="First group">
                    <span class="m-btn btn btn-light m-loader m-loader--brand m-loader--right m-loader--lg" id="loading-indicator" style="display: none" ></span>
                </div>
                <span class="btn btn-brand m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air" title="Print" onclick = "window.print()">
                    <i class="fa fa-print"></i>
                </span>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content table-responsive">
                    <table style="margin-top: 0px !important; padding: 0px!important" cellspacing="0" id="delivery-list-datatable" class="section-to-print table table-bordered table-sm m-table m-table--head-bg-brand table-responsive table-hover custom_table_responsive">
                        <thead class="">
                            <tr class="table-header-brand">
                                <th colspan="6" style="border: 0px!important;padding-bottom: 20px!important;">
                                    <h5 class="" >{{$rider->first_name." ".$rider->last_name}} delivery lists</h5>
                                    <h5 class="selected-date"></h5>
                                    <h5 class="current-date">{{"Date from: ".date("d.m.Y")." - To: ".date("d.m.Y")}}</h5>
                                </th>
                                <th colspan="6" style="text-align: right;border: 0px!important; padding-bottom: 20px!important;"><img src="{!! asset('backend/ezzyr_assets/app/media/img/logos/logo.png') !!}" width="250px"></th>
                            </tr>
                            <tr>
                                <th>Sl.</th>
                                <th>Consignment No.</th>
                                <th>Merchant Name</th>
                                <th>Merchant Address</th>
                                <th>Merchant Contact</th>
                                <th>Status</th>
                                <th>Recipient Name</th>
                                <th>Recipient Address</th>
                                <th>Recipient Contact</th>
                                <th>Plan Charge</th>
                                <th>Delivery Zone</th>
                                <th>Amount to be collected</th>
                                <th>Receive Amount</th>
                            </tr>
                        </thead>
                        <tfoot id="rider-footer">
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
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

    <script type="text/javascript">
        $(document).ready(function(){
            var table = $('#delivery-list-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 25,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                dom : 'l<"#date-filter">frtip',
                ajax: {
                    url: '{!! route('admin.datatable.rider.delivery.list') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                        d.from_date = "{{ date("Y-m-d") }}";
                        d.to_date = "{{ date("Y-m-d") }}";
                        d.to_date = "{{ date("Y-m-d") }}";
                        d.user_id = "{{ $rider->id }}";
                        d.is_hub = "{{ 0 }}";
                        d.flag_status_id = "{{ 10 }}";
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
                        data: 'consignment_id',
                        name: 'consignment_id',
                        searchable: true
                    },
                    {
                        data: 'merchant_name',
                        name: 'merchant_name',
                        searchable: true
                    },
                    {
                        data: 'merchant_address',
                        name: 'merchant_address',
                        searchable: false
                    },
                    {
                        data: 'merchant_contact',
                        name: 'merchant_contact',
                        searchable: false
                    },
                    {
                        data: 'status_text',
                        name: 'status_text',
                        searchable: false,
                        render: function ( data, type, row ) {
                            return '<b style="color:'+row.status_color+' ">'+row.status_text+'</b>';
                        }
                    },
                    {
                        data: 'recipient_name',
                        name: 'recipient_name',
                        searchable: false
                    },
                    {
                        data: 'recipient_address',
                        name: 'recipient_address',
                        searchable: false
                    },
                    {
                        data: 'recipient_number',
                        name: 'recipient_number',
                        searchable: false
                    },
                    {
                        data: 'plan_charge',
                        name: 'plan_charge',
                        searchable: false
                    },
                    {
                        data: 'recipient_zone_name',
                        name: 'recipient_zone_name',
                        searchable: false
                    },
                    {
                        data: 'amount_to_be_collected',
                        name: 'amount_to_be_collected',
                        searchable: false,

                    },
                    {
                        data: 'receive_amount',
                        name: 'receive_amount',
                        searchable: false,

                    },
                ],

                //Data table footer
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    pageTotalCollectedAmount = api
                        .column( 11, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    pageTotalReceivedAmount = api
                        .column( 12, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // var total_collected_amount = api.ajax.json().totalCollected;
                    // var total_received_amount = api.ajax.json().totalReceived;

                    // Update footer
                    $( api.column( 11 ).footer() ).html(
                        'Page Total: ' + Math.round(pageTotalCollectedAmount)
                    );

                    $( api.column( 12 ).footer() ).html(
                        'Page Total: ' + Math.round(pageTotalReceivedAmount)
                    );

                }
            });

            $('.dataTables_wrapper').removeClass('form-inline');
            $('.form-control').removeClass('input-sm');
            $('.dataTables_processing').addClass('m-loader m-loader--brand');

            $('#delivery-list-datatable_length').addClass('col-lg-6 col-md-6 col-sm-6');
            $('#delivery-list-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air filter-select');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#delivery-list-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');


            var date_picker_html =
                '<div class="form-group m-form__group clearfix">' +
                    '<div class="input-group pull-right" id="m_daterangepicker">' +
                        '<input type="text" class="form-control m-input" readonly  placeholder="Select date range" value=""/>' +
                        '<span class="input-group-addon">' +
                            '<i class="fa fa-calendar"></i>' +
                        '</span>' +
                    '</div>' +
                '</div>';

            $('#date-filter').append(date_picker_html);
            $('#delivery-list-datatable_filter label:first-child input').attr('placeholder', 'Name, mobile, email');


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
                table.columns(3).search(start_date + ' ~ ' + end_date).draw();


                // To view beside export buttons
                $('.date-show-container').show();
                $('.start-select-date').html(start.format('DD-MM-YYYY'));
                $('.start-select-date').attr('name', start.format('YYYY-MM-DD'));
                $('.end-select-date').html(end.format('DD-MM-YYYY'));
                $('.end-select-date').attr('name', end.format('YYYY-MM-DD'));
                $('.selected-date').html('Date from: '+start.format('DD.MM.YYYY') +' - To: '+ end.format('DD.MM.YYYY'));
                $(".current-date").hide();
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
        .table-header-brand{
            visibility: collapse;
        }

        /*Print css*/
        @media print {
            .table-header-brand{
                visibility: visible;
            }
            table.dataTable {
                clear: both;
                margin-top: 0px !important;
                margin-bottom: 0px !important;
                max-width: none !important;
            }
            .dataTables_length{
                margin-bottom: 0px!important;
            }
            *{
                margin: 0!important;
                padding: 0!important;
                box-sizing: inherit!important;
            }
            body{
                visibility: hidden;
                font-size: 8px!important;
            }
            .section-to-print, .section-to-print * {
                visibility: visible;
            }

            table.dataTable tbody th, table.dataTable tbody td {
                font-size: 10px!important;
                padding: 2px!important;
            }
            .m-table.m-table--head-bg-brand thead th {
                font-size: 10px!important;
                padding: 2px!important;
            }
            .m-table.m-table--head-bg-brand thead{

            }
            .m-table.m-table--head-bg-brand thead th:after {
                display: none!important;
            }
            .m-portlet__head-caption{
                visibility: hidden;
                margin: 0px!important;
            }
            table{
                 margin: 0!important;
                 padding: 0!important;
                 border-collapse: collapse!important;
                 border-spacing: 0px!important;
             }
            .table{
                border-collapse: collapse!important;
                border-spacing: 0px!important;
            }
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
                url: "{!! route('admin.agents.export') !!}",
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