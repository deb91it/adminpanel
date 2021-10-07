@extends('agent.layout.master')
@section('title') Holiday Settings Management @stop

@section('page_name')
    Holiday Settings Management
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('agent.holiday_settings', 'Holiday Settings') !!} </li>
@stop

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! route('agent.holiday_settings.create') !!}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="la la-plus"></span>&nbsp; New Holidays
                        </a>
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <table id="holiday-settings-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                        <tr>
                            <th>Sl.</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>From date</th>
                            <th>To date</th>
                            <th>Total days</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!--end::Section-->
        </div>
        <div class="m-portlet__foot clearfix">
            <div class="pull-right">
            </div>
        </div>
    </div>

    <!--end::Portlet-->
    <script type="text/javascript">
        $(document).ready(function(){
            var table = $('#holiday-settings-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 25,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                //dom : 'l<"#date-filter"><"#category-filter">frtip',
                ajax: {
                    url: '{!! route('agent.datatable.holiday_settings') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    {
                        data: 'hs_id',
                        name: 'hs_id',
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'title',
                        name: 'hs.title',
                        searchable: true
                    },{
                        data: 'description',
                        name: 'hs.description',
                        searchable: true
                    },{
                        data: 'from_date',
                        name: 'from_date',
                        searchable: false
                    },{
                        data: 'to_date',
                        name: 'to_date',
                        searchable: false
                    },{
                        data: 'total_days',
                        name: 'total_days',
                        searchable: false
                    },{
                        data: 'action_col',
                        name: 'action_col',
                        searchable: false,
                        orderable: false
                    },
                ]
            });

            $('.dataTables_wrapper').removeClass('form-inline');
            $('.form-control').removeClass('input-sm');
            $('.dataTables_processing').addClass('m-loader m-loader--brand');

            $('#outlet-datatable_length').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#outlet-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air m_selectpicker');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#category-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#outlet-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');

            {{--var date_picker_html =--}}
                {{--'<div class="form-group m-form__group clearfix">' +--}}
                {{--'<div class="input-group pull-right" id="m_daterangepicker">' +--}}
                {{--'<input type="text" class="form-control m-input" readonly  placeholder="Select date range" value=""/>' +--}}
                {{--'<span class="input-group-addon">' +--}}
                {{--'<i class="fa fa-calendar"></i>' +--}}
                {{--'</span>' +--}}
                {{--'</div>' +--}}
                {{--'</div>';--}}

            {{--$('#date-filter').append(date_picker_html);--}}
            {{--$('#outlet-datatable_filter label:first-child input').attr('placeholder', 'name, address, owner');--}}

            {{--var outlet_category_list =--}}
                {{--'<div class="form-group m-form__group row">' +--}}
                {{--'<div class="col-lg-12 col-md-12 col-sm-12">' +--}}
                {{--'<select class="form-control m-bootstrap-select m_selectpicker selected-category" title="Select category">' +--}}
                    {{--@foreach($categories as $category)--}}

                        {{--'<option value="{{ $category->id }}">{{ $category->name }}</option>' +--}}

                    {{--@endforeach--}}
                        {{--'</select>' +--}}
                {{--'</div>' +--}}
                {{--'</div>';--}}

            $('#category-filter').append(outlet_category_list);


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
                table.columns(6).search(start_date + ' ~ ' + end_date).draw();


                /*// To view beside export buttons
                $('.date-show-container').show();
                $('.start-select-date').html(start.format('DD-MM-YYYY'));
                $('.start-select-date').attr('name', start.format('YYYY-MM-DD'));
                $('.end-select-date').html(end.format('DD-MM-YYYY'));
                $('.end-select-date').attr('name', end.format('YYYY-MM-DD'));*/
            });

            /*//For clear the input field
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
                table.columns(6).search('').draw();
            });*/

            //For category select
            $('.selected-category').on('change', function() {
                var category_id = $(this).val();
                if(category_id != ""){
                    table.columns(3).search(category_id).draw();
                }
            })
        });


        var BootstrapSelect = function () {

            //== Private functions
            var demos = function () {
                // minimum setup
                $('.filter-select').selectpicker();
            }

            return {
                // public functions
                init: function() {
                    demos();
                }
            };
        }();

        jQuery(document).ready(function() {
            BootstrapSelect.init();
        });

    </script>
@stop