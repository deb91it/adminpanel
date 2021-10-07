@extends('admin.layout.master')
@section('title') Admin Dashboard @stop

@section('page_name')
	Dashboard
@stop

@section ('breadcrumbs')
    <li class="active">
        <div>
           <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
        </div>
    </li>
@stop

@section('content')

        <!--Begin::Main Portlet-->
<div class="m-portlet">
    <div class="m-portlet__body m-portlet__body--no-padding">
        <div class="row m-row--no-padding m-row--col-separator-xl">
            <div class="col-md-12 col-lg-12 col-xl-4">
                <!--begin:: Widgets/Stats2-1 -->
                <div class="m-widget1">
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">
                                    Total Stores
                                </h3>

                            </div>
                            <div class="col m--align-right">
                                <span>
                                    <a href="{{route('admin.stores')}}"  class="m-widget1__number m--font-brand">
                                        {{$statistics->number_of_stores}}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">
                                    Total Merchants
                                </h3>
                            </div>
                            <div class="col m--align-right">
                                <span>
                                    <a href="{{route('admin.merchants')}}" class="m-widget1__number m--font-warning">
                                        {{$statistics->number_of_merchant}}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">
                                    Total Products
                                </h3>
                            </div>
                            <div class="col m--align-right">
                                <span>
                                    <a href="{{route('admin.products')}}" class="m-widget1__number m--font-danger">
                                        {{$statistics->number_of_products}}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">
                                    Total HUB
                                </h3>
                            </div>
                            <div class="col m--align-right">
                                <a href="{{route('admin.hubs')}}" class="m-widget1__number m--font-success">
                                    {{$statistics->number_of_hub}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Stats2-1 -->
                {{--<div class="overlay oload1" style="display: block;position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: 50;background: rgba(255,255,255,0.7);">
                    <div class="m-loader m-loader--brand" style="width: 30px; display: inline-block;position: absolute;top: 50%;left: 50%;margin-left: -15px;margin-top: -15px;"></div>
                </div>--}}
            </div>
            <div class="col-md-12 col-lg-12 col-xl-4">
                <!--begin:: Widgets/Profit Share-->
                <div class="m-widget14">
                    <div class="m-widget14__header">
                        <h3 class="m-widget14__title">
                            Merchant Payment
                        </h3>
                        <span class="m-widget14__desc">
                            Payment transaction with merchants
                        </span>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">
                            <div id="m_chart_profit_share" class="m-widget14__chart" style="height: 160px">
                                <div class="m-widget14__stat">
                                    {{round($statistics->payment_of_merchant_of_this_month)}}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="m-widget14__legends">
                                <div class="m-widget14__legend">
                                    <span class="m-widget14__legend-bullet m--bg-brand"></span>
                                    <span class="m-widget14__legend-text">
                                        This Year <a class="m--font-brand" href="{{route('admin.invoices')}}?query_string={{date('Y-01-01')."~".date("Y-m-d")}}">{{round($statistics->payment_of_merchant_of_this_year)}}</a> BDT
                                        <input type="hidden" class="todays_completion_ratio" value="{{0}}">
                                    </span>
                                </div>
                                <div class="m-widget14__legend">
                                    <span class="m-widget14__legend-bullet m--bg-accent"></span>
                                    <span class="m-widget14__legend-text">
                                        This Month <a class="m--font-accent" href="{{route('admin.invoices')}}?query_string={{date('Y-m-01')."~".date("Y-m-d")}}">{{round($statistics->payment_of_merchant_of_this_month)}}</a> BDT
                                        <input type="hidden" class="todays_acceptance_ratio" value="{{0}}">
                                    </span>
                                </div>
                                <div class="m-widget14__legend">
                                    <span class="m-widget14__legend-bullet m--bg-warning"></span>
                                    <span class="m-widget14__legend-text">
                                        Today <a class="m--font-warning" href="{{route('admin.invoices')}}?query_string={{date('Y-m-d')."~".date("Y-m-d")}}">{{round($statistics->payment_of_merchant_of_this_day)}}</a> BDT
                                        <input type="hidden" class="todays_cancelled_ratio" value="{{0}}">
                                    </span>
                                </div>
                                <div class="m-widget14__legend">
                                    <span class="m-widget14__legend-bullet m--bg-primary"></span>
                                    <span class="m-widget14__legend-text">
                                        Yesterday <a class="m--font-primary" href="{{route('admin.invoices')}}?query_string={{date('Y-m-d', strtotime("-1 day"))."~".date('Y-m-d', strtotime("-1 day"))}}">{{round($statistics->payment_of_merchant_of_yesterday)}}</a> BDT
                                        <input type="hidden" class="todays_cancelled_ratio" value="{{0}}">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Profit Share-->

                {{--<div class="overlay oload1" style="display: block;position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: 50;background: rgba(255,255,255,0.7);">
                    <div class="m-loader m-loader--brand" style="width: 30px; display: inline-block;position: absolute;top: 50%;left: 50%;margin-left: -15px;margin-top: -15px;"></div>
                </div>--}}
            </div>
            <div class="col-md-12 col-lg-12 col-xl-4">
                <!--begin:: Widgets/Stats2-3 -->
                <div class="m-widget1">
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">
                                    Data Entry
                                </h3>
                                <span class="m-widget1__desc">
                                    This Year
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-success">
                                    <a class="m--font-success" href="{{route('admin.deliverys')}}?query_string={{date('Y-01-01')."~".date("Y-m-d")}}">{{$statistics->number_of_parcel_of_this_year}}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">
                                    Data Entry
                                </h3>
                                <span class="m-widget1__desc">
                                    This Month
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-danger">
                                    <a class="m--font-danger" href="{{route('admin.deliverys')}}?query_string={{date('Y-m-01')."~".date("Y-m-d")}}">{{$statistics->number_of_parcel_of_this_month}}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">
                                    Data Entry
                                </h3>
                                <span class="m-widget1__desc">
                                    Today
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-primary">
                                    <a class="m--font-primary" href="{{route('admin.deliverys')}}?query_string={{date('Y-m-d')."~".date("Y-m-d")}}">{{$statistics->number_of_parcel_of_this_day}}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">
                                    Data Entry
                                </h3>
                                <span class="m-widget1__desc">
                                    Yesterday
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-primary">
                                    <a class="m--font-primary" href="{{route('admin.deliverys')}}?query_string={{date('Y-m-d', strtotime("-1 day"))."~".date('Y-m-d', strtotime("-1 day"))}}">{{$statistics->number_of_parcel_of_yesterday}}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--begin:: Widgets/Stats2-3 -->
                {{--<div class="overlay oload1" style="display: block;position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: 50;background: rgba(255,255,255,0.7);">
                    <div class="m-loader m-loader--brand" style="width: 30px; display: inline-block;position: absolute;top: 50%;left: 50%;margin-left: -15px;margin-top: -15px;"></div>
                </div>--}}
            </div>
        </div>
    </div>
</div>
<!--End::Main Portlet-->

        <div class="row">
            <div class="col-xl-7 col-md-7 col-xs-7">
                <!--begin:: Widgets/Tasks -->
                <div class="m-portlet m-portlet--full-height">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Provati Operational Statistics
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                                <li class="nav-item m-tabs__item">
                                    <a onclick="OperationStatistics('yesterday')" class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab0_content" role="tab" aria-expanded="false">
                                        Yesterday
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a onclick="OperationStatistics('today')" class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab1_content" role="tab" aria-expanded="false">
                                        Today
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a onclick="OperationStatistics('month')" class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget2_tab2_content1" role="tab" aria-expanded="false">
                                        Month
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a onclick="OperationStatistics('all')" class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab3_content1" role="tab" aria-expanded="true">
                                        Year
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="m_widget2_tab1_content" aria-expanded="true">
                                <div id="chartContainer" style="height: 400px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Tasks -->
            </div>

            <div class="col-xl-5 col-md-5 col-xs-5">
                <!--begin:: Widgets/Tasks -->
                <div class="m-portlet m-portlet--full-height">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Provati Collection Statistics
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                                <li class="nav-item m-tabs__item">
                                    <a onclick="CollectionStatistics('yesterday')" class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab1_content" role="tab" aria-expanded="false">
                                        Yesterday
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a onclick="CollectionStatistics('today')" class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab1_content" role="tab" aria-expanded="false">
                                        Today
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a onclick="CollectionStatistics('month')" class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget2_tab2_content1" role="tab" aria-expanded="false">
                                        Month
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a onclick="CollectionStatistics('all')" class="nav-link m-tabs__link " data-toggle="tab" href="#m_widget2_tab3_content1" role="tab" aria-expanded="true">
                                       Year
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="m_widget2_tab1_content" aria-expanded="true">
                                <div id="collectionContainer" style="height: 500px; width: 100%;"></div>
                                <span id="textCollection"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Tasks -->
            </div>
        </div>

        <div class="row m-row--no-padding m-row--col-separator-xl">

        </div>

        <div class="row m-row--no-padding m-row--col-separator-xl">
            <div class="col-xl-12 col-md-12 col-xs-12">
                <!--begin:: Widgets/Tasks -->
                <div class="m-portlet m-portlet--full-height">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Provati Financial Statistics
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                                <li class="nav-item m-tabs__item">
                                    <a onclick="FinancialStatistics('monthly')" class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget2_tab2_content1" role="tab" aria-expanded="false">
                                        Month
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a onclick="FinancialStatistics('yearly')" class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab3_content1" role="tab" aria-expanded="true">
                                        Year
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="m_widget2_tab1_content" aria-expanded="true">
                                <div id="FinanceContainer" style="height: 450px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Tasks -->
            </div>
        </div>

<script src="{{asset('backend/ezzyr_assets/js/canvasjs.min.js')}}"></script>
<script>
    window.onload = function () {
        OperationStatistics('month');
        FinancialStatistics('monthly');
        CollectionStatistics('month');
    }

    function OperationStatistics(param) {

        $.ajax({
            type: "POST",
            url: "{!! route('admin.dashboard.data.operations') !!}",
            data: {"_token": "{{ csrf_token() }}", "param": param, },
            cache: false,
            beforeSend: function(){
                $('#loading-indicator').show();
            },
            complete: function(){
                $('#loading-indicator').hide();
            },
            success: function (res) {
                if (res.success === false) {
                    // alert(res.msg);
                } else {

                }
                rawLink = onClickGenerateLink(param);
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    exportEnabled: true,
                    theme: "light2", // "light1", "light2", "dark1", "dark2"
                    title:{
                        text: "Operational Statistics"
                    },
                    axisY: {
                        title: "Operations(DDHRT)"
                    },
                    data: [{
                        type: "column",
                        click: onClick,
                        showInLegend: true,
                        legendMarkerColor: "grey",
                        legendText: "DDHRT = "+res.data.number_in_word+" parcels",
                        dataPoints: [
                            { y: res.data.number_of_parcel, label: "Number of Parcel ("+res.data.number_of_parcel+")", link:onClickGenerateLink(param, 0) },
                            { y: res.data.number_of_parcel_delivered,  label: "Delivered ("+res.data.number_of_parcel_delivered+")", link:onClickGenerateLink(param, 6) },
                            { y: res.data.number_of_parcel_hold,  label: "Hold ("+res.data.number_of_parcel_hold+")", link:onClickGenerateLink(param, 9)},
                            { y: res.data.number_of_parcel_returned,  label: "Returned ("+res.data.number_of_parcel_returned+")", link:onClickGenerateLink(param, 8)},
                            { y: res.data.number_of_parcel_transit,  label: "Transit ("+res.data.number_of_parcel_transit+")", link:onClickGenerateLink(param, 5)},
                        ]
                    }]
                });
                chart.render();
                function onClick(e){
                    window.open(e.dataPoint.link,'_blank');
                };
            }
        });
    }

    function onClickGenerateLink(param, status) {
        var query = "?query_string={{date("Y-01-01")."~".date("Y-m-d")}}&status="+status;
        var link = "";
        if (param == 'month')
        {
            query = "?query_string={{date("Y-m-01")."~".date("Y-m-d")}}&status="+status;
        }else if(param == 'today')
        {
            query = "?query_string={{date("Y-m-d")."~".date("Y-m-d")}}&status="+status;
        }
        link = window.location.href+"/delivery"+query;
        return link;
    }

    function FinancialStatistics(param) {
        var income = [];
        var expense = [];
        var revenue = [];
        $.ajax({
            type: "POST",
            url: "{!! route('admin.dashboard.data.finance') !!}",
            data: {"_token": "{{ csrf_token() }}", "param": param, },
            cache: false,
            beforeSend: function(){
                $('#loading-indicator').show();
            },
            complete: function(){
                $('#loading-indicator').hide();
            },
            success: function (res) {
                if (res.success === false) {
                    //alert(res.msg);
                } else {
                }
                if(res.data.type === 'Monthly')
                {
                    $.each( res.data.labels, function( key, value ) {
                        income.push({ label: value, y: res.data.income[key].income, link:onClickGenerateFinancialLink(param, res.data.income[key].date)});
                        expense.push({ label: value, y: res.data.expense[key].expense, link:onClickGenerateFinancialLink(param, res.data.income[key].date) });
                        revenue.push({ label: value, y: res.data.revenue[key].revenue, link:onClickGenerateFinancialLink(param, res.data.income[key].date) });
                    });
                }else{
                    $.each( res.data.labels, function( key, value ) {
                        income.push({ label: value, y: res.data.income[key], link:onClickGenerateFinancialLink(param, res.data.dates[key])});
                        expense.push({ label: value, y: res.data.expense[key], link:onClickGenerateFinancialLink(param, res.data.dates[key]) });
                        revenue.push({ label: value, y: res.data.revenue[key], link:onClickGenerateFinancialLink(param, res.data.dates[key]) });
                    });
                }
                var chart = new CanvasJS.Chart("FinanceContainer", {
                    animationEnabled: true,
                    exportEnabled: true,
                    theme: "light2",
                    title: {
                        text: res.data.type+" Sales Data"
                    },
                    axisX: {
                        valueFormatString: "MMM"
                    },
                    axisY: {
                        prefix: "৳",
                        labelFormatter: addSymbols
                    },
                    toolTip: {
                        shared: true
                    },
                    legend: {
                        cursor: "pointer",
                        itemclick: toggleDataSeries
                    },
                    data: [
                        {
                            type: "column",
                            click: onClick,
                            name: "Income",
                            showInLegend: true,
                            xValueFormatString: "MMMM YYYY",
                            yValueFormatString: "৳#,##0",
                            dataPoints: income,
                        },
                        {
                            type: "area",
                            click: onClick,
                            name: "Expense",
                            markerBorderColor: "white",
                            markerBorderThickness: 2,
                            color: "#DF7970",
                            showInLegend: true,
                            yValueFormatString: "৳#,##0",
                            dataPoints: expense,
                        },
                        {
                            type: "line",
                            click: onClick,
                            name: "Revenue",
                            showInLegend: true,
                            color: "#88CBBF",
                            yValueFormatString: "৳#,##0",
                            dataPoints: revenue,
                        },
                        ]
                });
                chart.render();
                function onClick(e){
                    window.open(e.dataPoint.link,'_blank');
                };

                function addSymbols(e) {
                    var suffixes = ["", "K", "M", "B"];
                    var order = Math.max(Math.floor(Math.log(e.value) / Math.log(1000)), 0);

                    if(order > suffixes.length - 1)
                        order = suffixes.length - 1;

                    var suffix = suffixes[order];
                    return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
                }

                function toggleDataSeries(e) {
                    if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                        e.dataSeries.visible = false;
                    } else {
                        e.dataSeries.visible = true;
                    }
                    e.chart.render();
                }
            }
        });
    }

    function onClickGenerateFinancialLink(param, startDate) {
        var query = "?quert_string=";
        var link = "";
        if (param == 'monthly')
        {
            query = "?query_string="+startDate+"~"+startDate;
        }else if(param == 'yearly')
        {
            query = "?query_string="+startDate;
        }
        link = window.location.href+"/financial-statement"+query;
        return link;
    }

    function CollectionStatistics(param) {
        $.ajax({
            type: "POST",
            url: "{!! route('admin.dashboard.data.collection') !!}",
            data: {"_token": "{{ csrf_token() }}", "param": param, },
            cache: false,
            beforeSend: function(){
                $('#loading-indicator').show();
            },
            complete: function(){
                $('#loading-indicator').hide();
            },
            success: function (res) {
                if (res.success === false) {
                    $("#textCollection").html("<h4 class='text-center text-danger'>Couldn't found any data to display the chart.</h4>");
                    $("#collectionContainer").hide();
                    $("#textCollection").show();
                } else {
                    $("#collectionContainer").show();
                    $("#textCollection").hide();
                }
                var hub = [];
                hub.push({ y: res.data.total_amount[0].total_amount, name: "Total Collection", exploded: true });
                $.each( res.data.hub, function( key, value ) {
                    hub.push({ y: res.data.hub[key].hub_amount, name: res.data.hub[key].hub_name });
                });
                // console.log(hub);
                var chart = new CanvasJS.Chart("collectionContainer", {
                    exportEnabled: true,
                    animationEnabled: true,
                    title:{
                        // text: "State Operating Funds"
                    },
                    legend:{
                        cursor: "pointer",
                        itemclick: explodePie
                    },
                    data: [{
                        type: "pie",
                        showInLegend: true,
                        toolTipContent: "{name}: <strong>{y}৳</strong>",
                        indexLabel: "{name} - {y}৳",
                        dataPoints: hub
                    }]
                });
                chart.render();

                function explodePie (e) {
                    if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
                        e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
                    } else {
                        e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
                    }
                    e.chart.render();
                }
            }
        });

    }
</script>


@stop