@extends('admin.template')

@section('title','Dashboard')

@section('body')
    
    <div class="container-fluid">
        
    </div>
@endsection

@section('script')
    {{--<script>
        const today = new Date();
        const dates = [];

        for (let i = 0; i < 10; i++) {
            const previousDate = new Date(today);
            previousDate.setDate(today.getDate() - i);

            const months = ['Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];

            // Formatage au format d-m
            const formattedDate = `${previousDate.getDate()} ${months[previousDate.getMonth()]}`;
            dates.push(formattedDate);
        }

        $(function () {
            var chart = {
            series: [
                { name: "Clients", data: {{$users}} },
                { name: "Montant Visites", data: {{$visits}} },
            ],

            chart: {
                type: "bar",
                height: 345,
                offsetX: -15,
                toolbar: { show: true },
                foreColor: "#adb0bb",
                fontFamily: 'inherit',
                sparkline: { enabled: false },
            },


            colors: ["#20c997", "#5D87FF"],


            plotOptions: {
                bar: {
                horizontal: false,
                columnWidth: "70%",
                borderRadius: [6],
                borderRadiusApplication: 'end',
                borderRadiusWhenStacked: 'all'
                },
            },
            markers: { size: 0 },

            dataLabels: {
                enabled: false,
            },


            legend: {
                show: false,
            },


            grid: {
                borderColor: "rgba(0,0,0,0.1)",
                strokeDashArray: 3,
                xaxis: {
                lines: {
                    show: false,
                },
                },
            },

            xaxis: {
                type: "date",
                categories: dates,
                labels: {
                style: { cssClass: "grey--text lighten-2--text fill-color" },
                },
            },


            yaxis: {
                show: true,
                min: 0,
                tickAmount: 4,
                labels: {
                style: {
                    cssClass: "grey--text lighten-2--text fill-color",
                },
                },
            },
            stroke: {
                show: true,
                width: 3,
                lineCap: "butt",
                colors: ["transparent"],
            },


            tooltip: { theme: "light" },

            responsive: [
                {
                breakpoint: 600,
                options: {
                    plotOptions: {
                    bar: {
                        borderRadius: 3,
                    }
                    },
                }
                }
            ]


            };

            var chart = new ApexCharts(document.querySelector("#chart"), chart);
            chart.render();

        })
    </script>--}}
@endsection