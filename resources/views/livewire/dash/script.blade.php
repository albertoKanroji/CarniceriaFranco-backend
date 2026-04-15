<script>
    document.addEventListener('livewire:load', function() {
        const salesByMonthData = @json($salesByMonthData ?? []);
        const top5Labels = @json($top5Labels ?? []);
        const top5Data = @json($top5Data ?? []);
        const weekSalesData = @json($weekSalesData ?? []);

        function money(value) {
            return '$' + Number(value || 0).toFixed(2);
        }

        const totalYear = salesByMonthData.reduce((acc, item) => acc + Number(item || 0), 0);

        const optionsMonth = {
            series: [{
                name: 'Ventas del mes',
                data: salesByMonthData
            }],
            chart: {
                height: 350,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '45%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return money(val);
                },
                offsetY: -20,
                style: {
                    fontSize: '11px'
                }
            },
            xaxis: {
                categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return money(val);
                    }
                }
            },
            title: {
                text: 'Total anual: ' + money(totalYear),
                align: 'center',
                style: {
                    fontSize: '14px'
                }
            }
        };

        const chartMonth = new ApexCharts(document.querySelector('#chartMonth'), optionsMonth);
        chartMonth.render();

        const optionsTop = {
            series: top5Data,
            chart: {
                height: 392,
                type: 'donut'
            },
            labels: top5Labels,
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                formatter: function(val) {
                    return val.toFixed(1) + '%';
                }
            },
            responsive: [{
                breakpoint: 576,
                options: {
                    chart: {
                        height: 300
                    }
                }
            }]
        };

        const chartTop = new ApexCharts(document.querySelector('#chartTop5'), optionsTop);
        chartTop.render();

        const optionsWeek = {
            chart: {
                height: 380,
                type: 'area',
                stacked: false,
                toolbar: {
                    show: false
                }
            },
            stroke: {
                curve: 'smooth'
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return money(val);
                }
            },
            series: [{
                name: 'Ventas',
                data: weekSalesData
            }],
            xaxis: {
                categories: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return money(val);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return money(val);
                    }
                }
            }
        };

        const chartWeek = new ApexCharts(document.querySelector('#areaChart'), optionsWeek);
        chartWeek.render();
    });
</script>
