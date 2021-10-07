@php $company = get_company_details(); @endphp
@extends('agent.layout.master')
@section('title') @php if(isset($company->name)) echo $company->name; @endphp Dashboard @stop

@section('page_name')
    Welcome to @php if(isset($company->name)) echo $company->name; @endphp dashboard !
@stop

@section ('breadcrumbs')
    <li class="active"> 
        {{--<div>
           <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
        </div>--}}
    </li>
@stop

@section('content')
    <!--begin:: Widgets/Stats-->
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::Total Profit-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">
                                Total outlet
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                No. of outlet
                            </span>
                            <span class="m-widget24__stats m--font-brand">
                                <a href="{{ url('outlets') }}" > {{ $total->outlet }} </a>
                            </span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm my-4">
                                <div class="progress-bar m--bg-brand" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!--end::Total Profit-->
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Feedbacks-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">
                                Total order amount
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                Sum of all order amount
                            </span>
                            <span class="m-widget24__stats m--font-info">
                                <a href="{{ url('orders') }}" > {{ $total->order_amount }}tk </a>
                            </span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm my-4">
                                <div class="progress-bar m--bg-info" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!--end::New Feedbacks-->
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Orders-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">
                                Total employee
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                 No. of employee (TSE & SR)
                            </span>
                            <span class="m-widget24__stats m--font-danger">
                                <a href="{{ url('employee') }}" > {{ $total->employee }} </a>
                            </span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm my-4">
                                <div class="progress-bar m--bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!--end::New Orders-->
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Users-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">
                                Total outlet contact
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                No. of outlet contact
                            </span>
                            <span class="m-widget24__stats m--font-success">
                                <a href="{{ url('outlets') }}" > {{ $total->outlet_contact }} </a>
                            </span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm my-4">
                                <div class="progress-bar m--bg-success" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!--end::New Users-->
                </div>
            </div>
        </div>
    </div>
    <!--end:: Widgets/Stats-->

    <!--begin:: Widgets/Stats-->
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::Total Profit-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">
                                Today visited outlet
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                No. of visited outlet (today)
                            </span>
                            <span class="m-widget24__stats m--font-danger">
                                <a href="{{ url('orders') }}" > {{ $today->visited }} </a>
                            </span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm my-4">
                                <div class="progress-bar m--bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!--end::Total Profit-->
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Feedbacks-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">
                                Today order amount
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                Sum of today's order amount
                            </span>
                            <span class="m-widget24__stats m--font-success">
                                 <a href="{{ url('orders') }}" >  {{ $today->order_amount }}tk </a>
                            </span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm my-4">
                                <div class="progress-bar m--bg-success" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!--end::New Feedbacks-->
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Orders-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">
                                Today absent
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                No. of today's absent
                            </span>
                            <span class="m-widget24__stats m--font-brand">
                                <a href="{{ url('employee') }}" >  {{ $total->employee - $today->present }} </a>
                            </span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm my-4">
                                <div class="progress-bar m--bg-brand" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!--end::New Orders-->
                </div>
                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Users-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">
                                Today meeting
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                Count of today's meeting
                            </span>
                            <span class="m-widget24__stats m--font-info">
                                <a href="{{ url('orders?search_for=has_meeting&from='.date('Y-m-d').'&to='.date('Y-m-d')) }}" > {{ $today->meeting }} </a>
                            </span>
                            <div class="m--space-10"></div>
                            <div class="progress m-progress--sm my-4">
                                <div class="progress-bar m--bg-info" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!--end::New Users-->
                </div>
            </div>
        </div>
    </div>
    <!--end:: Widgets/Stats-->

    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-6">
            <div class="m-portlet  m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Daily Sales Reports (Today)
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body">
                    <div class="m-widget11">
                        <div class="table-responsive m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="450">
                            <!--begin::Table-->
                            <table class="table">
                                <thead>
                                <tr>
                                    <td class="m-widget11__app">
                                        TERRITORY SALES EXECUTIVE (TSE)
                                    </td>
                                    <td class="m-widget11__sales">
                                        ORDER AMOUNT
                                    </td>
                                    <td class="m-widget11__total">
                                        VISITED COUNT
                                    </td>
                                    <td class="m-widget11__total">
                                        SR COUNT
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tsr_reports as $tsr_report)
                                    <tr>
                                        <td class="clearfix" style="display: flex;">
                                            <div class="tdclass" style="margin-right:8px;">
                                                @if($tsr_report->profile_pic_url != '')
                                                    <img src="{{ $tsr_report->profile_pic_url }}" alt="" style="width:50px; border-radius:50%;">
                                                @else
                                                    <img src="{!! asset('backend/ezzyr_assets/app/media/img/users/avatar.png') !!}" alt="" style="width:50px; border-radius:50%;">
                                                @endif
                                            </div>
                                            <div class="tdclass">
                                                    <span class="m-widget11__title">
                                                        {{ $tsr_report->full_name }}
                                                    </span>
                                                <span class="m-widget11__sub">
                                                        {{ $tsr_report->designation }}
                                                    </span>
                                            </div>
                                        </td>
                                        <td class="m--font-success">
                                            {{ $tsr_report->order_amount }} tk
                                        </td>
                                        <td class="m--font-brand">
                                            {{ $tsr_report->visited_count }}
                                        </td>
                                        <td class="m--font-info">
                                            {{ $tsr_report->sr_count }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Best Sellers-->
        </div>
        <div class="col-xl-6">
            <div class="m-portlet  m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Daily Sales Reports (Today)
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body">
                    <div class="m-widget11">
                        <div class="table-responsive m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="450">
                            <!--begin::Table-->
                            <table class="table">
                                <thead>
                                <tr>
                                    <td class="m-widget11__app">
                                        SALES REPRESENTATIVE (SR)
                                    </td>
                                    <td class="m-widget11__sales">
                                        ORDER AMOUNT
                                    </td>
                                    <td class="m-widget11__total">
                                        VISITED COUNT
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($sr_reports as $sr_report)
                                        <tr>
                                            <td class="clearfix" style="display: flex;">
                                                <div class="tdclass" style="margin-right:8px;">
                                                    @if($sr_report->profile_pic_url != '')
                                                        <img src="{{ $sr_report->profile_pic_url }}" alt="" style="width:50px; border-radius:50%;">
                                                    @else
                                                        <img src="{!! asset('backend/ezzyr_assets/app/media/img/users/avatar.png') !!}" alt="" style="width:50px; border-radius:50%;">
                                                    @endif
                                                </div>
                                                <div class="tdclass">
                                                    <span class="m-widget11__title">
                                                        {{ $sr_report->full_name }}
                                                    </span>
                                                    <span class="m-widget11__sub">
                                                        {{ $sr_report->designation }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="m--font-success">
                                                {{ $sr_report->order_amount }} tk
                                            </td>
                                            <td class="m--font-brand">
                                                {{ $sr_report->visited_count }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Best Sellers-->
        </div>
    </div> <!--End Main Portlet -->

    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet  m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                {{--Remarks--}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="float-right">
                            <select name="bar_graph_type" class="bar_graph_type m_selectpicker">
                                <option value="order_amount"> Order amount </option>
                                <option value="order_quantity">  Order quantity </option>
                                <option value="visited">  Visited outlet </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div id="chart" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
                </div>
            </div>
            <!--end:: Widgets/Best Sellers-->
        </div>
    </div>

    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-6">
            <div class="m-portlet  m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Remarks
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body pb-0">
                    <div class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="450">
                        <ul class="dashboard-remark">
                            @if(!empty($remarks))
                                @foreach($remarks as $remark)
                                <li>
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="outlet-img">
                                                    @if($remark->outlet_image !='')
                                                        @if(file_exists(public_path().'/resources/outlet/'.$remark->outlet_image))
                                                            <img src="{{ url('/resources/outlet') }}/{{ $remark->outlet_image }}" width="30px" data-toggle="m-tooltip" title="{{ $remark->outlet_name }}">
                                                        @else
                                                            <img src="{!! asset('backend/ezzyr_assets/app/media/img/no-image.png') !!}" width="30px" data-toggle="m-tooltip" title="{{ $remark->outlet_name }}">
                                                        @endif
                                                    @else
                                                        <img src="{!! asset('backend/ezzyr_assets/app/media/img/no-image.png') !!}" width="30px" data-toggle="m-tooltip" title="{{ $remark->outlet_name }}">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> {{ $remark->remarks }}
                                                    <span class="label label-sm m-badge m-badge--success">
                                                        {{ $remark->sr_name }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> {{ $remark->date_time }} </div>
                                    </div>
                                </li>
                                @endforeach
                            @else
                                Data Not Found
                            @endif
                        </ul>
                    </div>
                    <a href="{{ url('orders?search_for=remarks') }}" class="btn btn-success btn-sm float-right mt-3"> View all </a>
                </div>
            </div>
            <!--end:: Widgets/Best Sellers-->
        </div>
        
        <div class="col-xl-6">
            <div class="m-portlet  m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Last one month order amount !
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body pb-0">
                    <div class="m-widget14">
                        {{--<div class="m-widget14__header m--margin-bottom-30">--}}
                        {{--<h3 class="m-widget14__title">--}}
                        {{--Daily Sales--}}
                        {{--</h3>--}}
                        {{--<span class="m-widget14__desc">--}}
                        {{--Check out each collumn for more details--}}
                        {{--</span>--}}
                        {{--</div>--}}
                        <div class="m-widget14__chart" style="height:120px;">
                            <canvas  id="order_chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Best Sellers-->
        </div>
    </div> <!--End Main Portlet -->

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var searchFor = $('.bar_graph_type').children("option:selected").val();
            $('.bar_graph_type').change(function(){
                searchFor = $(this).children("option:selected").val();
                loadBarGraphData(searchFor);
            });
            loadBarGraphData(searchFor);
        });

        function loadBarGraphData(searchFor) {
            var result = [];
            $.ajax({
                type: "GET",
                url: "{!! route('agent.dashboard.barGraph') !!}",
                data: {"_token": "{{ csrf_token() }}", "search_for": searchFor},
                cache: false,
                contentType: 'application/json',
                dataType: 'json',
                success: function (res) {
                    result = [];
                    if(res.success){
                        $.each(res.data, function( index, item ) {
                            result.push([item.date, item.val]);
                        });
                        loadHeighChart(searchFor, result);
                    } else {
                        loadHeighChart(searchFor, []);
                    }
                },
                error: function (response, status, error) {

                }
            });
        }
    </script>


    <script>
        //== Class definition
        var OrderChart = function() {
            //== Daily Sales chart.
            //** Based on Chartjs plugin - http://www.chartjs.org/
            var dailySales = function() {
                var chartData = {
                    labels: [
                        @foreach($bar_graphs as $bar_graph)
                            "{{ $bar_graph['date'] }}" {{ "," }}
                        @endforeach
                    ],
                    datasets: [{
                        backgroundColor: mUtil.getColor('success'),
                        data: [
                            @foreach($bar_graphs as $bar_graph)
                                "{{ $bar_graph['order'] }}" {{ "," }}
                            @endforeach
                        ]
                    }, {
                        backgroundColor: '#f3f3fb',
                        data: [
                            @foreach($bar_graphs as $bar_graph)
                                "{{ $bar_graph['order'] }}" {{ "," }}
                            @endforeach
                        ]
                    }]
                };

                var chartContainer = $('#order_chart');

                if (chartContainer.length == 0) {
                    return;
                }

                var chart = new Chart(chartContainer, {
                    type: 'bar',
                    data: chartData,
                    options: {
                        title: {
                            display: false,
                        },
                        tooltips: {
                            intersect: false,
                            mode: 'nearest',
                            xPadding: 10,
                            yPadding: 10,
                            caretPadding: 10
                        },
                        legend: {
                            display: false
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        barRadius: 4,
                        scales: {
                            xAxes: [{
                                display: false,
                                gridLines: false,
                                stacked: true
                            }],
                            yAxes: [{
                                display: false,
                                stacked: true,
                                gridLines: false
                            }]
                        },
                        layout: {
                            padding: {
                                left: 0,
                                right: 0,
                                top: 0,
                                bottom: 0
                            }
                        }
                    }
                });
            }

            return {
                //== Init demos
                init: function() {
                    // init charts
                    dailySales();
                }
            };
        }();

        //== Class initialization on page load
        jQuery(document).ready(function() {
            OrderChart.init();
        });
    </script>
@stop