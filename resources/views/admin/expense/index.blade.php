@extends('admin.layout.master')
@section('title') Expense List @stop


@section('page_name')
    Expense Management
    <small>All Expenses</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.expenses', 'Expense Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! route('admin.expense.new') !!}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="la la-plus"></span>&nbsp; New expense
                        </a>
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group date-show-container export-date-show-container" role="group" aria-label="First group" style="display:none; margin-left: 5px; margin-right: 5px">
                    <span class="m--font-brand"><b>Export from :</b></span> <span class="start-select-date" name=""> </span> <span class="m--font-brand"><b>To</b></span> <span class="end-select-date" name=""> </span>
                </div>

                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group" role="group" aria-label="First group">
                    <span class="m-btn btn btn-light m-loader m-loader--brand m-loader--right m-loader--lg" id="loading-indicator" style="display: none" ></span>
                </div>

                <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="First group">
                    <button type="button" class="m-btn btn btn-primary export-file" title="Excel" name="xlsx">
                        <i class="fa fa-file-excel-o"></i>
                    </button>
                    <button type="button" class="m-btn btn btn-success export-file" name="csv" title="Csv">
                        <i class="fa fa-file-o"></i>
                    </button>
                    {{--<button type="button" class="m-btn btn btn-brand export-file" title="Pdf" name="pdf">
                        <i class="fa fa-file-pdf-o"></i>
                    </button>--}}
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content table-responsive">
                    <table id="expense-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                        <tr>
                            <th>Sl.</th>
                            <th>Expense Date</th>
                            <th>Expense Category</th>
                            <th>Payment Type</th>
                            <th>Paid Date</th>
                            <th>Amount</th>
                            <th>Attachment</th>
                            <th>Description</th>
                            <th>Status</th>
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

    <script type="text/javascript">
        $(document).ready(function(){
            var table = $('#expense-datatable').DataTable({
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
                    url: '{!! route('admin.datatable.expense') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                        d.expense_date = "{{ isset($_GET['expense_date']) ? $_GET['expense_date'] : date('Y-m-d') }}";
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
                        data: 'expense_date',
                        name: 'expense_date',
                        searchable: false
                    },
                    {
                        data: 'category_name',
                        name: 'category_name',
                        searchable: true
                    },
                    {
                        data: 'payment_type',
                        name: 'payment_type',
                        searchable: false
                    },
                    {
                        data: 'payment_date',
                        name: 'payment_date',
                        searchable: false
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        searchable: false
                    },
                    {
                        data: 'image_name',
                        name: 'image_name',
                        searchable: false,
                        render: function ( data, type, row ) {
                            return '<a href="'+row.image_url+'" target="_blank"><img src="'+row.image_url+'" style="height: 60px;width: 55px;" class="img-rounded"></a>';
                        }
                    },
                    {
                        data: 'description',
                        name: 'description',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                        orderable: false,
                        render: function ( data, type, row ) {
                            if(row.status==1){
                                return '<i class="fa fa-check m--font-success"></i>';
                            }else{
                                return '<i class="fa fa-remove m--font-danger"></i>';
                            }
                        }
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

            $('#expense-datatable_length').addClass('col-lg-6 col-md-6 col-sm-6');
            $('#expense-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air filter-select');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#expense-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');


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
            $('#expense-datatable_filter label:first-child input').attr('placeholder', 'by expense category');


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

            console.log('exportType: ', exportType);
            console.log('startDate: ', startDate);
            console.log('endDate: ', endDate);

            $.ajax({
                type: "POST",
                url: "{!! route('admin.expenses.export') !!}",
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