<div>
    <canvas id="lineChart" style="width: 100%; height: 400px;"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
            if (window.lineChartInstance) {
                window.lineChartInstance.destroy();
            }

            const ctx1 = document.getElementById('lineChart').getContext('2d');
            const chartData1 = @json($data);

            window.lineChartInstance = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: chartData1.labels,
                    datasets: chartData1.datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Pendapatan'
                            },
                            beginAtZero: true
                        }
                    }
                },
            });
    </script>
</div>