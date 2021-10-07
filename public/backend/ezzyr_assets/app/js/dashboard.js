function loadHeighChart(type, data) {
    var title = '';
    var textColor = '';
    var barColor = '';
    var sideTitle = '';
    var tooltipTitle = '';
    var  format = "{point.y:.0f}";

    if(type == 'order_amount'){
        title = 'order amount ';
        sideTitle = 'Order amount (tk)';
        tooltipTitle = 'Order amount';
        textColor = '#ffffff';
        barColor = '#45ccb1';
        var  format = "{point.y:,.1f}";
    } else if(type == 'order_quantity') {
        title = 'order quantity ';
        tooltipTitle = sideTitle = 'Order quantity';
        textColor = '#ffffff';
        barColor = '#716aca';
    } else if(type == 'visited') {
        title = 'visited outlet ';
        tooltipTitle = sideTitle = 'Visited outlet';
        textColor = '#ffffff';
        barColor = '#36a3f7';
    }

    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });

    Highcharts.chart('chart', {
        colors: [barColor],
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: 'Showing ' + title + ' graph of last 15 days !'
        },
        xAxis: {
            gridLineWidth: 1,
            max: 14,
            min: 0,
            tickInterval: 1,
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: sideTitle
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: tooltipTitle + ': <b>'+ format +'</b>'
        },
        series: [{
            name: 'Population',
            data: data,
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: textColor,
                align: 'right',
                format: format, // one decimal
                x: 0,
                y: 8, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
}