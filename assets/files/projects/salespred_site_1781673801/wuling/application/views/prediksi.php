<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediksi Penjualan - Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        .chart-container {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
    </style>

    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>
</head>
<body>

    <!-- Navbar -->
    <?php $this->load->view('templates/navbar'); ?>

    <!-- Layout -->
    <div class="wl-layout">
        
        <!-- Sidebar -->
        <?php $this->load->view('templates/sidebar'); ?>

        <!-- Main Content -->
        <main class="wl-main">
            <!-- Page Header -->
            <div class="wl-page-header wl-fade-up-2">
                <h1 class="wl-page-title">Prediksi Penjualan</h1>
                <p class="wl-page-subtitle">Analisis dan Prediksi Menggunakan Metode Moving Average</p>
            </div>

            <!-- Controls -->
            <div class="wl-card wl-fade-up-3" style="margin-bottom: 2rem;">
                <div class="wl-form-row">
                    <div class="wl-form-group">
                        <label class="wl-label" for="periodSelect">Periode Moving Average:</label>
                        <select id="periodSelect" class="wl-select">
                            <option value="3">3 Periode</option>
                            <option value="4">4 Periode</option>
                            <option value="5" selected>5 Periode</option>
                            <option value="6">6 Periode</option>
                        </select>
                    </div>
                    <div class="wl-form-group">
                        <label class="wl-label" for="modelSelect">Filter Model:</label>
                        <select id="modelSelect" class="wl-select">
                            <option value="all">Semua Model</option>
                            <option value="FORMO">FORMO</option>
                            <option value="CORTEZ">CORTEZ</option>
                            <option value="ALMAZ">ALMAZ</option>
                            <option value="CONFERO">CONFERO</option>
                        </select>
                    </div>
                    <div class="wl-form-group" style="display: flex; align-items: flex-end;">
                        <button class="wl-btn wl-btn-primary" onclick="calculatePrediction()">
                            <i class="fas fa-calculator"></i> Hitung Prediksi
                        </button>
                    </div>
                </div>
            </div>

            <div id="errorMessage" class="wl-alert wl-alert-danger" style="display: none;"></div>

            <!-- Stats -->
            <div class="wl-stat-grid wl-fade-up-4">
                <div class="wl-stat-card">
                    <div class="wl-stat-icon blue"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <div class="wl-stat-value" id="totalTransactions">-</div>
                        <div class="wl-stat-label">Total Transaksi</div>
                    </div>
                </div>
                <div class="wl-stat-card">
                    <div class="wl-stat-icon green"><i class="fas fa-car"></i></div>
                    <div>
                        <div class="wl-stat-value" id="averageSales">-</div>
                        <div class="wl-stat-label">Rata-rata Penjualan/Bulan</div>
                    </div>
                </div>
                <div class="wl-stat-card">
                    <div class="wl-stat-icon orange"><i class="fas fa-money-bill-wave"></i></div>
                    <div>
                        <div class="wl-stat-value" id="totalRevenue">-</div>
                        <div class="wl-stat-label">Total Revenue (M)</div>
                    </div>
                </div>
                <div class="wl-stat-card">
                    <div class="wl-stat-icon purple"><i class="fas fa-percentage"></i></div>
                    <div>
                        <div class="wl-stat-value" id="mapeValue">-</div>
                        <div class="wl-stat-label">MAPE (%)</div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="wl-fade-up-5 chart-container">
                <div style="font-size: var(--fs-lg); font-weight: 600; margin-bottom: 1rem; color: var(--text-primary); text-align: center;"><i class="fas fa-chart-area"></i> Grafik Penjualan dan Moving Average</div>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Table -->
            <div class="wl-card wl-fade-up-6">
                <div class="wl-card-header">
                    <h2 class="wl-card-title"><i class="fas fa-table"></i> Tabel Hasil Analisis Moving Average</h2>
                </div>
                <div class="wl-table-wrapper">
                    <table class="wl-table">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Penjualan Aktual</th>
                                <th>Moving Average</th>
                                <th>Prediksi Berikutnya</th>
                                <th>Error (%)</th>
                            </tr>
                        </thead>
                        <tbody id="predictionTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Data transaksi dari SQL
        const transactionsData = [
            {id: 1, sl_date: 2025, tahun: 2021, bulan: 'January', hari: 6, model: 'FORMO', price_net: 174900000.00},
            {id: 2, sl_date: 44223, tahun: 2021, bulan: 'January', hari: 27, model: 'CORTEZ', price_net: 190500000.00},
            {id: 3, sl_date: 44223, tahun: 2021, bulan: 'January', hari: 27, model: 'CONFERO', price_net: 190500000.00},
            {id: 4, sl_date: 44227, tahun: 2021, bulan: 'January', hari: 31, model: 'ALMAZ', price_net: 195500000.00},
            {id: 5, sl_date: 44228, tahun: 2021, bulan: 'February', hari: 1, model: 'FORMO', price_net: 162900000.00},
            {id: 6, sl_date: 44238, tahun: 2021, bulan: 'February', hari: 11, model: 'ALMAZ', price_net: 162900000.00},
            {id: 7, sl_date: 44240, tahun: 2021, bulan: 'February', hari: 13, model: 'FORMO', price_net: 183500000.00},
            {id: 8, sl_date: 44253, tahun: 2021, bulan: 'February', hari: 26, model: 'FORMO', price_net: 162900000.00},
            {id: 9, sl_date: 44259, tahun: 2021, bulan: 'March', hari: 4, model: 'CORTEZ', price_net: 174900000.00},
            {id: 10, sl_date: 44267, tahun: 2021, bulan: 'March', hari: 12, model: 'CORTEZ', price_net: 169900000.00},
            {id: 11, sl_date: 44273, tahun: 2021, bulan: 'March', hari: 18, model: 'ALMAZ', price_net: 144900000.00},
            {id: 25, sl_date: 44273, tahun: 2021, bulan: 'March', hari: 18, model: 'ALMAZ', price_net: 139900000.00},
            {id: 26, sl_date: 44275, tahun: 2021, bulan: 'March', hari: 20, model: 'CONFERO', price_net: 190500000.00},
            {id: 27, sl_date: 44277, tahun: 2021, bulan: 'March', hari: 22, model: 'CONFERO', price_net: 195500000.00},
            {id: 28, sl_date: 44281, tahun: 2021, bulan: 'March', hari: 26, model: 'CONFERO', price_net: 144900000.00},
            {id: 29, sl_date: 44283, tahun: 2021, bulan: 'March', hari: 28, model: 'CORTEZ', price_net: 190500000.00},
            {id: 30, sl_date: 44289, tahun: 2021, bulan: 'April', hari: 3, model: 'ALMAZ', price_net: 162900000.00},
            {id: 31, sl_date: 44291, tahun: 2021, bulan: 'April', hari: 5, model: 'CORTEZ', price_net: 190500000.00},
            {id: 32, sl_date: 44302, tahun: 2021, bulan: 'April', hari: 16, model: 'CORTEZ', price_net: 183500000.00}
        ];

        let salesChart;

        function processData(modelFilter = 'all') {
            // Filter data berdasarkan model
            let filteredData = transactionsData;
            if (modelFilter !== 'all') {
                filteredData = transactionsData.filter(item => item.model === modelFilter);
            }

            // Kelompokkan data berdasarkan bulan
            const monthlyData = {};
            filteredData.forEach(item => {
                const key = `${item.tahun}-${item.bulan}`;
                if (!monthlyData[key]) {
                    monthlyData[key] = {
                        period: key,
                        count: 0,
                        revenue: 0,
                        month: item.bulan,
                        year: item.tahun
                    };
                }
                monthlyData[key].count += 1;
                monthlyData[key].revenue += item.price_net;
            });

            // Konversi ke array dan urutkan
            const monthOrder = ['January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December'];
            
            return Object.values(monthlyData).sort((a, b) => {
                if (a.year !== b.year) return a.year - b.year;
                return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
            });
        }

        function calculateMovingAverage(data, period) {
            const movingAverages = [];
            const predictions = [];
            const errors = [];

            for (let i = 0; i < data.length; i++) {
                if (i >= period - 1) {
                    // Hitung moving average
                    let sum = 0;
                    for (let j = i - period + 1; j <= i; j++) {
                        sum += data[j].count;
                    }
                    const ma = sum / period;
                    movingAverages.push(ma);

                    // Prediksi untuk periode berikutnya
                    if (i < data.length - 1) {
                        const actualNext = data[i + 1].count;
                        const error = Math.abs((actualNext - ma) / actualNext) * 100;
                        errors.push(error);
                        predictions.push({
                            period: data[i + 1].period,
                            actual: actualNext,
                            predicted: ma,
                            error: error
                        });
                    }
                } else {
                    movingAverages.push(null);
                }
            }

            return { movingAverages, predictions, errors };
        }

        function calculateFuturePredictions(data, period, futurePeriods = 0) {
            return []; // Tidak ada prediksi masa depan
        }

        function calculateLastMovingAverage(data, period) {
            if (data.length < period) return 0;
            
            let sum = 0;
            for (let i = data.length - period; i < data.length; i++) {
                sum += data[i].count;
            }
            return sum / period;
        }

        function calculateMAPE(errors) {
            if (errors.length === 0) return 0;
            const sum = errors.reduce((acc, error) => acc + error, 0);
            return (sum / errors.length).toFixed(2);
        }

        function updateStats(data, errors) {
            const totalTransactions = data.reduce((sum, item) => sum + item.count, 0);
            const averageSales = data.length > 0 ? (totalTransactions / data.length).toFixed(1) : 0;
            const totalRevenue = (data.reduce((sum, item) => sum + item.revenue, 0) / 1000000).toFixed(1);
            const mape = calculateMAPE(errors);

            document.getElementById('totalTransactions').textContent = totalTransactions;
            document.getElementById('averageSales').textContent = averageSales;
            document.getElementById('totalRevenue').textContent = totalRevenue;
            document.getElementById('mapeValue').textContent = mape;
        }

        function createChart(data, movingAverages, futurePredictions) {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            if (salesChart) {
                salesChart.destroy();
            }

            const labels = data.map(item => item.period);
            const actualData = data.map(item => item.count);
            const maData = movingAverages;

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Penjualan Aktual',
                            data: actualData,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Moving Average',
                            data: maData,
                            borderColor: '#f093fb',
                            backgroundColor: 'rgba(240, 147, 251, 0.1)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Periode'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Jumlah Penjualan'
                            },
                            beginAtZero: true
                        }
                    },
                    elements: {
                        point: {
                            radius: 4,
                            hoverRadius: 8
                        }
                    }
                }
            });
        }

        function updateTable(data, predictions, futurePredictions) {
            const tbody = document.getElementById('predictionTableBody');
            tbody.innerHTML = '';

            // Tambahkan data historis saja
            data.forEach((item, index) => {
                const row = tbody.insertRow();
                const prediction = predictions.find(p => p.period === item.period);
                
                row.insertCell(0).textContent = item.period;
                row.insertCell(1).textContent = item.count;
                row.insertCell(2).textContent = index >= parseInt(document.getElementById('periodSelect').value) - 1 ? 
                    Math.round(calculateMovingAverage(data.slice(0, index + 1), parseInt(document.getElementById('periodSelect').value)).movingAverages[index] || 0) : '-';
                row.insertCell(3).textContent = prediction ? Math.round(prediction.predicted) : '-';
                row.insertCell(4).textContent = prediction ? prediction.error.toFixed(2) + '%' : '-';
            });
        }

        function calculatePrediction() {
            const period = parseInt(document.getElementById('periodSelect').value);
            const modelFilter = document.getElementById('modelSelect').value;
            
            document.getElementById('errorMessage').style.display = 'none';

            try {
                const data = processData(modelFilter);
                
                if (data.length < period) {
                    throw new Error(`Data tidak cukup. Diperlukan minimal ${period} periode data untuk Moving Average ${period}.`);
                }

                const { movingAverages, predictions, errors } = calculateMovingAverage(data, period);

                updateStats(data, errors);
                createChart(data, movingAverages, []);
                updateTable(data, predictions, []);

            } catch (error) {
                document.getElementById('errorMessage').textContent = error.message;
                document.getElementById('errorMessage').style.display = 'block';
            }
        }

        // Inisialisasi halaman
        document.addEventListener('DOMContentLoaded', function() {
            calculatePrediction();
        });
    </script>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>