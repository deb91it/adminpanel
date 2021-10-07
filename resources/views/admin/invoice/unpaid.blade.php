@extends('admin.layout.master')
@section('title') Invoice List @stop


@section('page_name')
    Invoice  Management
    <small>All Invoice </small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.invoices', ' Invoice Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
            </div>
                        <div class="m-portlet__head-tools">
                            <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group date-show-container export-date-show-container" role="group" aria-label="First group" style="display:none; margin-left: 5px; margin-right: 5px">
                                <span class="m--font-brand"><b>Date from :</b></span> <span class="start-select-date" name=""> </span> <span class="m--font-brand"><b>To</b></span> <span class="end-select-date" name=""> </span>
                            </div>

                            <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group" role="group" aria-label="First group">
                                <span class="m-btn btn btn-light m-loader m-loader--brand m-loader--right m-loader--lg" id="loading-indicator" style="display: none" ></span>
                            </div>

{{--                            <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="First group">--}}
{{--                                <button type="button" class="m-btn btn btn-primary export-file" title="Print" name="xlsx">--}}
{{--                                    <i class="fa fa-file-excel-o"></i>--}}
{{--                                </button>--}}
{{--                                <button type="button" class="m-btn btn btn-success export-file" name="csv" title="Csv">--}}
{{--                                    <i class="fa fa-file-o"></i>--}}
{{--                                </button>--}}
{{--                                <button type="button" class="m-btn btn btn-brand export-file" title="Pdf" name="pdf">--}}
{{--                                    <i class="fa fa-file-pdf-o"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>
        </div>
        <form method="post" action="{{route('admin.invoice.multiple.store')}}">
            {{csrf_field()}}
            <div class="m-portlet__body">
                <div class="panel panel-default" style="width: 30%;">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7">
                                <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">
                                    <input type="checkbox" id="check-all">

                                    <span></span>
                                </label>
                                <label style="">Select ALL <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="" class="btn btn-primary">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-section">
                    <div class="m-section__content table-responsive">
                        <table id="invoices-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                            <thead class="">
                            <tr>
                                <th>Sl</th>
                                <th>Merchant Name</th>
                                <th>Invoice Date</th>
                                <th>Collected</th>
                                <th>Total Charges (Plan + COD)</th>
                                <th>Service Charge</th>
                                <th>COD Charge</th>
                                <th>Delivered</th>
                                <th>Returned</th>
                                <th>Hand Over</th>
                                <th>Paid</th>
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
        </form>
        <!--end::Section-->
    </div>

    <!--end::Portlet-->

    <!-- Metronic modal -->
    <!--begin::Modal-->
    <div class="modal fade" id="product-assign-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3  id="header-label-modal">Product Details</h3>
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
                                <div class="table-responsive">
                                    <table id="product-details" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                        <thead class="">
                                        <tr>
                                            <th>Consignment ID</th>
                                            <th>Merchant Order ID</th>
                                            <th>Package Description</th>
                                            <th>Created At</th>
                                            <th>Recipient</th>
                                            <th>Status</th>
                                            <th>Collected Amount</th>
                                            <th>COD</th>
                                            <th>Delivery Charge</th>
                                            <th>Total Charge</th>
                                            <th>Paid Out</th>
                                        </tr>
                                        </thead>
                                        <tbody id="product-details-body"></tbody>
                                    </table>
                                </div>
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
            var table = $('#invoices-datatable').DataTable({
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
                    url: '{!! route('admin.datatable.unpaid.invoice') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                        d.invoice_date = "{{ date('Y-m-d') }}";
                    }
                },
                columns: [
                    {
                        data: 'deli_id',
                        name: 'deli_id',
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return '<label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">\n' +
                                '           <input type="checkbox" name="merchant_id[]" id="merchant_id" value="'+row.merchant_id+'">\n' +
                                '           <input type="hidden" name="invoices_date['+row.merchant_id+']" id="invoices_date" value="'+row.invoice_date+'">\n' +
                                '           <input type="hidden" name="collected['+row.merchant_id+']" id="collected" value="'+row.collected+'">\n' +
                                '           <span></span>\n' +
                                '   </label>';
                        }

                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        searchable: true,
                        render: function (data, type, row, meta) {
                            return row.business_name+'<br><small style="color: #716ACA">'+row.full_name+'</small>';
                        }

                    },
                    {
                        data: 'invoice_date',
                        name: 'invoice_date',
                        searchable: false

                    },
                    {
                        data: 'collected',
                        name: 'collected',
                        searchable: false
                    },
                    {
                        data: 'fees',
                        name: 'fees',
                        searchable: false

                    },
                    {
                        data: 'plan_charges',
                        name: 'plan_charges',
                        searchable: false,

                    },
                    {
                        data: 'cod',
                        name: 'cod',
                        searchable: false

                    },
                    {
                        data: 'delivered',
                        name: 'delivered',
                        searchable: false

                    },
                    {
                        data: 'returned',
                        name: 'returned',
                        searchable: false

                    },
                    {
                        data: 'handover',
                        name: 'handover',
                        searchable: false

                    },
                    {
                        data: 'paid',
                        name: 'paid',
                        searchable: false

                    },
                    {
                        data: 'action_col',
                        name: 'action_col',
                        searchable: false

                    },
                ],
            });

            $('.dataTables_wrapper').removeClass('form-inline');
            $('.form-control').removeClass('input-sm');
            $('.dataTables_processing').addClass('m-loader m-loader--brand');

            $('#invoices-datatable_length').addClass('col-lg-6 col-md-6 col-sm-6');
            $('#invoices-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air filter-select');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#invoices-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');


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
            $('#invoices-datatable_filter label:first-child input').attr('placeholder', 'by merchant name');


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
                startDate: moment(),
                endDate: moment(),
                format: 'YYYY-MM-DD'

            }, function(start, end, label) {
                $('#m_daterangepicker .form-control').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                var start_date = start.format('YYYY-MM-DD');
                var end_date = end.format('YYYY-MM-DD');
                table.columns(2).search(start_date + ' ~ ' + end_date).draw();


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
        $("#check-all").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
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
                url: "{!! route('admin.invoices.export') !!}",
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
        function InvoicesDetails(id,date,invoice_id,invoice_type) {
            $.ajax({
                type: "POST",
                url: "{!! route('admin.invoice.details.view') !!}",
                data: {"_token": "{{ csrf_token() }}", "merchant_id": id, "invoice_date": date, "invoice_id": invoice_id, "invoice_type": invoice_type},
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
                        $("#product-details-body tr").remove();
                        var sl =1;
                        var content ='';
                        var status ='';
                        $.each(res.result, function (key, val) {
                            content +='<tr>';
                            content += '<td>' + val['consignment_id'] + '</td>';
                            content += '<td>' + val['merchant_order_id'] + '</td>';
                            content += '<td>' + val['package_description'] + '</td>';
                            content += '<td>' + val['created_at'] + '</td>';
                            content += '<td>' + val['recipient_name'] + '</td>';
                            content += '<td></td>';
                            content += '<td>' + val['amount_to_be_collected'] + '</td>';
                            content += '<td></td>';
                            content += '<td>' + val['charge'] + '</td>';
                            content += '<td></td>';
                            content += '<td> </td>';
                            content +='</tr>';
                        });
                        content +='';
                        $('#product-details-body').append(content);
                        $('#header-label-modal').text("Invoice "+date);
                        $("#product-assign-Modal").modal('show');
                    }
                }
            });
        }
    </script>
@stop