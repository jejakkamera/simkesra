<div>
    <canvas id="doughnutChart" style="width: 100px; height: 100px;"></canvas>
Peserta Didik Aktif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
            const ctx = document.getElementById('doughnutChart').getContext('2d');
            const chartData = @json($data);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `${tooltipItem.label}: ${tooltipItem.raw}`;
                                }
                            }
                        }
                    }
                },
            });
    </script>
</div>