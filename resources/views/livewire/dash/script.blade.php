<script>
    document.addEventListener('livewire:load', function() {
        //-------------------------------------------------------------------------------------//
        //                        SALES BY MONTH
        // ------------------------------------------------------------------------------------//
        var options = {
            series: [{
                name: 'Ventas del mes',
                data: @this.salesByMonth_Data
            }],
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return '$' + val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },

            xaxis: {
                categories: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov",
                    "Dic"
                ],
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    formatter: function(val) {
                        return '$' + val;
                    }
                }

            },
            title: {
                text: totalYearSales(),
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartMonth"), options);
        chart.render();




        //-------------------------------------------------------------------------------------//
        //                        TOP 5 PRODUCTS
        // ------------------------------------------------------------------------------------//
        var optionsTop = {
            series: [
                parseFloat(@this.top5Data[0]['total']),
                parseFloat(@this.top5Data[1]['total']),
                parseFloat(@this.top5Data[2]['total']),
                parseFloat(@this.top5Data[3]['total']),
                parseFloat(@this.top5Data[4]['total'])
            ],
            chart: {
                height: 392,
                type: 'donut',
            },
            labels: [@this.top5Data[0]['product'],
                @this.top5Data[1]['product'],
                @this.top5Data[2]['product'],
                @this.top5Data[3]['product'],
                @this.top5Data[4]['product']
            ],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chartTop5"), optionsTop);
        chart.render();





        //-------------------------------------------------------------------------------------//
        //                                  WEEK SALES
        // ------------------------------------------------------------------------------------//
        var optionsArea = {
            chart: {
                height: 380,
                type: 'area',
                stacked: false,
            },
            stroke: {
                curve: 'straight'
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -5,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            series: [{
                name: "Day Sale",
                data: [
                    parseFloat(@this.weekSales_Data[0]),
                    parseFloat(@this.weekSales_Data[1]),
                    parseFloat(@this.weekSales_Data[2]),
                    parseFloat(@this.weekSales_Data[3]),
                    parseFloat(@this.weekSales_Data[4]),
                    parseFloat(@this.weekSales_Data[5]),
                    parseFloat(@this.weekSales_Data[6])
                ]
            }],
            xaxis: {
                categories: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
            },
            tooltip: {
                followCursor: true
            },
            fill: {
                opacity: 1,
            },

        }

        var chartArea = new ApexCharts(
            document.querySelector("#areaChart"),
            optionsArea
        );

        chartArea.render();

        var VisitasMeses = {
            chart: {
                height: 380,
                type: 'area',
                stacked: false,
            },
            stroke: {
                curve: 'straight'
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -5,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            series: [{
                name: "Visitas",
                data: [
                    parseFloat(@this.monthlyVisitsData[0]),
                    parseFloat(@this.monthlyVisitsData[1]),
                    parseFloat(@this.monthlyVisitsData[2]),
                    parseFloat(@this.monthlyVisitsData[3]),
                    parseFloat(@this.monthlyVisitsData[4]),
                    parseFloat(@this.monthlyVisitsData[5]),
                    parseFloat(@this.monthlyVisitsData[6]),
                    parseFloat(@this.monthlyVisitsData[7]),
                    parseFloat(@this.monthlyVisitsData[8]),
                    parseFloat(@this.monthlyVisitsData[9]),
                    parseFloat(@this.monthlyVisitsData[10]),
                    parseFloat(@this.monthlyVisitsData[11])
                ]
            }],
            xaxis: {
                categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov',
                    'Dic'
                ],
            }


        }

        var Meses = new ApexCharts(
            document.querySelector("#MesesId"),
            VisitasMeses
        );

        Meses.render();



        //---------------------------------------------------------------//
        // suma total de ventas durante el año actual
        //---------------------------------------------------------------//
        function totalYearSales() {
            var total = 0
            @this.salesByMonth_Data.forEach(item => {
                total += parseFloat(item)
            })

            return 'Total: $' + total.toFixed(2)
        }

    })
</script>

<div id="loginsTodayChart"></div>


<script>
    document.addEventListener('livewire:load', function() {
        const loginData = @json($loginsByHour);
        const loginLabels = @json($loginLabels);

        const total = loginData.reduce((acc, val) => acc + parseFloat(val), 0);

        var options = {
            series: [{
                name: 'Inicios de sesión',
                data: loginData
            }],
            chart: {
                height: 350,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top', // etiquetas arriba
                    },
                    columnWidth: '45%'
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: loginLabels,
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                tooltip: {
                    enabled: true
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false
                }
            },
            fill: {
                opacity: 1,
                colors: ['#4e73df']
            },
            title: {
                text: 'Total: ' + total,
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#444',
                    fontWeight: 600
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#loginsTodayChart"), options);
        chart.render();
    });
</script>
<script>
    document.addEventListener('livewire:load', function() {
        const loginMonthData = @json($loginsByMonth);
        const loginMonthLabels = @json($loginMonthLabels);

        const totalLogins = loginMonthData.reduce((acc, val) => acc + parseFloat(val), 0);

        var options = {
            series: [{
                name: 'Inicios de sesión',
                data: loginMonthData
            }],
            chart: {
                height: 350,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top',
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: loginMonthLabels,
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                tooltip: {
                    enabled: true
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false
                }
            },
            fill: {
                opacity: 1,
                colors: ['#198754']
            },
            title: {
                text: 'Total: ' + totalLogins,
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#444',
                    fontWeight: 600
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#loginsByMonthChart"), options);
        chart.render();
    });
</script>
