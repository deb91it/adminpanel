//== Class definition
var amChartsStockChartsDemo = function() {

var demo7 = function() {
        var chartData = generateChartData();

        function generateChartData() {
            var chartData = [];
            var firstDate = new Date(2012, 0, 1);
            firstDate.setDate(firstDate.getDate() - 1000);
            firstDate.setHours(0, 0, 0, 0);

            for (var i = 0; i < 1000; i++) {
                var newDate = new Date(firstDate);
                newDate.setHours(0, i, 0, 0);

                var a = Math.round(Math.random() * (40 + i)) + 100 + i;
                var b = Math.round(Math.random() * 100000000);

                chartData.push({
                    "date": newDate,
                    "value": a,
                    "volume": b
                });
            }
            return chartData;
        }

        var chart = AmCharts.makeChart("m_amcharts_7", {
            "type": "stock",
            "theme": "light",
            "categoryAxesSettings": {
                "minPeriod": "mm"
            },

            "dataSets": [{
                "color": "#b0de09",
                "fieldMappings": [{
                    "fromField": "value",
                    "toField": "value"
                }, {
                    "fromField": "volume",
                    "toField": "volume"
                }],

                "dataProvider": chartData,
                "categoryField": "date"
            }],

            "panels": [{
                "showCategoryAxis": false,
                "title": "Value",
                "percentHeight": 70,

                "stockGraphs": [{
                    "id": "g1",
                    "valueField": "value",
                    "type": "smoothedLine",
                    "lineThickness": 2,
                    "bullet": "round"
                }],


                "stockLegend": {
                    "valueTextRegular": " ",
                    "markerType": "none"
                }
            }, {
                "title": "Volume",
                "percentHeight": 30,
                "stockGraphs": [{
                    "valueField": "volume",
                    "type": "column",
                    "cornerRadiusTop": 2,
                    "fillAlphas": 1
                }],

                "stockLegend": {
                    "valueTextRegular": " ",
                    "markerType": "none"
                }
            }],

            "chartScrollbarSettings": {
                "graph": "g1",
                "usePeriod": "10mm",
                "position": "top"
            },

            "chartCursorSettings": {
                "valueBalloonsEnabled": true
            },

            "periodSelector": {
                "position": "top",
                "dateFormat": "YYYY-MM-DD JJ:NN",
                "inputFieldWidth": 150,
                "periods": [{
                    "period": "hh",
                    "count": 1,
                    "label": "1 hour",
                    "selected": true
                }, {
                    "period": "hh",
                    "count": 2,
                    "label": "2 hours"
                }, {
                    "period": "hh",
                    "count": 5,
                    "label": "5 hour"
                }, {
                    "period": "hh",
                    "count": 12,
                    "label": "12 hours"
                }, {
                    "period": "MAX",
                    "label": "MAX"
                }]
            },

            "panelsSettings": {
                "usePrefixes": true
            },

            "export": {
                "enabled": true,
                "position": "bottom-right"
            }
        });
    }

     return {
        // public functions
        init: function() {

            demo7();

        }
    };
}();

jQuery(document).ready(function() {
    amChartsStockChartsDemo.init();
});




//== SINGLE WIDGET
var Widgets = function() {
    //== Support Tickets Chart.
    //** Based on Morris plugin - http://morrisjs.github.io/morris.js/
    var promo_history = function() {
        if ($('#promo_history').length == 0) {
            return;
        }

        Morris.Donut({
            element: 'promo_history',
            data: [
                {label: "EZZYR2018", value: 260},
                {label: "EZZYRTILL", value: 320},
                {label: "EZZYR14", value: 1130},
                {label: "EZZYRTRIP", value: 830},
                {label: "EZZYRCHECK", value: 730}  
            ],
            labelColor: '#a7a7c2',
            colors: [
                mUtil.getColor('accent'),
                mUtil.getColor('brand'),
                mUtil.getColor('danger'),
                mUtil.getColor('brand'),
                mUtil.getColor('accent'),
                mUtil.getColor('danger')
            ]
            //formatter: function (x) { return x + "%"}
        });
    }

    //== Sales Stats2.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var trip_summary = function() {
        if ($('#trip_summary').length == 0) {
            return;
        }

        var config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December",
                    "January", "February", "March", "April"
                ],
                datasets: [{
                    label: "Trip Completed",
                    borderColor: mUtil.getColor('brand'),
                    borderWidth: 2,
                    pointBackgroundColor: mUtil.getColor('brand'),

                    backgroundColor: mUtil.getColor('accent'),

                    pointHoverBackgroundColor: mUtil.getColor('danger'),
                    pointHoverBorderColor: Chart.helpers.color(mUtil.getColor('danger')).alpha(0.2).rgbString(),
                    data: [
                        10, 20, 16,
                        18, 12, 40,
                        35, 30, 33,
                        34, 45, 40,
                        60, 55, 70,
                        65, 75, 62
                    ]
                }]
            },
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
                    display: false,
                    labels: {
                        usePointStyle: false
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                hover: {
                    mode: 'index'
                },
                scales: {
                    xAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                },

                elements: {
                    point: {
                        radius: 3,
                        borderWidth: 0,

                        hoverRadius: 8,
                        hoverBorderWidth: 2
                    }
                }
            }
        };

        var chart = new Chart($('#trip_summary'), config);
    }

    return {
        //== Init demos
        init: function() {
            // init charts
           
            promo_history();
            trip_summary();
           
        }
    };
}();

//== Class initialization on page load
jQuery(document).ready(function() {
    Widgets.init();
});

//For Filter select
var BootstrapFilterSelect = function () {
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
    BootstrapFilterSelect.init();
});



//== Class definition

var Autosize = function () {

    //== Private functions
    var demos = function () {
        // basic demo
        var demo1 = $('#m_autosize_1');

        autosize(demo1);
    }

    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();

jQuery(document).ready(function() {
    Autosize.init();
});



