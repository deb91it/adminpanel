@extends('admin.layout.master')
@section('title') Income List @stop


@section('page_name')
    Income Management
    <small>All Incomes</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.incomes', 'Income Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
{{--                    <h3 class="m-portlet__head-text">--}}
{{--                        <a href="{!! route('admin.income.new') !!}" class="btn btn-sm btn-brand m-btn--pill">--}}
{{--                            <span class="la la-plus"></span>&nbsp; New income--}}
{{--                        </a>--}}
{{--                    </h3>--}}
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
                <div class="m-section__content table-responsive">
                    <table id="income-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                        <tr>
                            <th>Sl.</th>
                            <th>Date</th>
                            <th>Merchant Name</th>
                            <th>Consignment ID</th>
                            <th>Consignment Status</th>
                            <th>Amount to be collected</th>
                            <th>Receive Amount</th>
                            <th>Paid Amount</th>
                            <th>Unpaid Amount</th>
                            <th>Merchant Payment Status</th>
                            <th>Charge</th>
                            <th>COD</th>
                            <th>Total Income</th>
                        </tr>
                        </thead>
                        <tfoot>
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
            var table = $('#income-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 200,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                dom : 'l<"#sorting-filter"><"#date-filter">frtip',
                ajax: {
                    url: '{!! route('admin.datatable.income') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                        d.income_date = "{{ isset($_GET['income_date']) ? $_GET['income_date'] : date('Y-m-d') }}";
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
                        data: 'income_date',
                        name: 'income_date',
                        searchable: false
                    },
                    {
                        data: 'merchant_full_name',
                        name: 'merchant_full_name',
                        searchable: true
                    },
                    {
                        data: 'consignment_id',
                        name: 'consignment_id',
                        searchable: true
                    },
                    {
                        data: 'flag_text',
                        name: 'flag_text',
                        searchable: false,
                        render: function ( data, type, row ) {
                            var showText = row.flag_text;
                            return '<b style="color: '+row.color_code+';">'+showText+'</b>';
                        }

                    },
                    {
                        data: 'amount_to_be_collected',
                        name: 'amount_to_be_collected',
                        searchable: false
                    },
                    {
                        data: 'receive_amount',
                        name: 'receive_amount',
                        searchable: false,
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount',
                        searchable: false,
                    },
                    {
                        data: 'unpaid_amount',
                        name: 'unpaid_amount',
                        searchable: false,
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        searchable: false,
                        orderable: false,
                        render: function ( data, type, row ) {
                            if(row.payment_status==1){
                                return '<span class="label label-success">Paid</span>';
                            }else{
                                return '<span class="label label-danger">Unpaid</span>';
                            }
                        }
                    },
                    {
                        data: 'charge',
                        name: 'charge',
                        searchable: false
                    },
                    {
                        data: 'cod_charge',
                        name: 'cod_charge',
                        searchable: false,
                    },
                    {
                        data: 'total_income',
                        name: 'total_income',
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

                    pageTotalPaidAmount = api
                        .column( 6, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    pageTotalUnPaidAmount = api
                        .column( 7, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    pageTotalCharge = api
                        .column( 9, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );


                    pageTotalCod = api
                        .column( 10, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    pageTotalIncome = api
                        .column( 11, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    var total_charge = api.ajax.json().totalCharge;
                    var total_cod = api.ajax.json().totalCod;
                    var total_income = api.ajax.json().totalIncome;
                    var total_paid_amount = api.ajax.json().totalPaidMerchant;
                    var total_unpaid_amount = api.ajax.json().totalUnPaidMerchant;

                    // Update footer
                    $( api.column( 6 ).footer() ).html(
                        'Page Total: ' + Math.round(pageTotalPaidAmount) +' ( Total: '+ total_paid_amount + ' )'
                    );

                    $( api.column( 7 ).footer() ).html(
                        'Page Total: ' + Math.round(pageTotalUnPaidAmount) +' ( Total: '+ total_unpaid_amount + ' )'
                    );

                    $( api.column( 9 ).footer() ).html(
                        'Page Total: ' + Math.round(pageTotalCharge) +' ( Total: '+ total_charge + ' )'
                    );

                    $( api.column( 10 ).footer() ).html(
                        'Page Total: ' + Math.round(pageTotalCod) +' ( Total: '+ total_cod + ' )'
                    );

                    $( api.column( 11).footer() ).html(
                        'Page Total: ' + Math.round(pageTotalIncome) +' ( Total: '+ total_income + ' )'
                    );
                }
            });

            $('.dataTables_wrapper').removeClass('form-inline');
            $('.form-control').removeClass('input-sm');
            $('.dataTables_processing').addClass('m-loader m-loader--brand');

            $('#income-datatable_length').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#income-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air filter-select');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#sorting-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#income-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');


            var date_picker_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" id="m_daterangepicker">' +
                '<input type="text" class="form-control m-input datepicker-input" readonly  placeholder="Select date range" value=""/>' +
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
                '<select id="sorting_role" class="form-control m_selectpicker1"><option value="">Filter by status</option><?php
                    if(!empty($filterStatus)){
                        foreach ($filterStatus as $status){
                            echo '<option value="'.$status->id.'">'.$status->flag_text.'</option>';
                        }
                    }?></select>' +
                '</div>' +
                '</div>';

            $('#sorting-filter').append(select_html);
            $('#sorting_role').change(function() {
                var filter_status = $(this).val();
                table.columns(3).search(filter_status).draw();
            });
            $('#income-datatable_filter label:first-child input').attr('placeholder', 'by income category, merchant name');


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
                table.columns(5).search(start_date + ' ~ ' + end_date).draw();


                // To view beside export buttons
                $('.date-show-container').show();
                $('.start-select-date').html(start.format('DD-MM-YYYY'));
                $('.start-select-date').attr('name', start.format('YYYY-MM-DD'));
                $('.end-select-date').html(end.format('DD-MM-YYYY'));
                $('.end-select-date').attr('name', end.format('YYYY-MM-DD'));
                $('.datepicker-input').attr('value', start.format('DD-MM-YYYY') +' to '+ end.format('YYYY-MM-DD'));
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
                url: "{!! route('admin.incomes.export') !!}",
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