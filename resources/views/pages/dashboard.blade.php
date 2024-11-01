@extends('index')

@section('content')
    <div class="container-fluid py-4">
        @if (auth()->user()->level != 'member')
            <div class="row">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Jumlah Barang</p>
                                        <h5 class="font-weight-bolder mb-0">{{ $jumlahBarang }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-blue shadow text-center border-radius-md">
                                        <i class="fas fa-box text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Kategori Barang</p>
                                        <h5 class="font-weight-bolder mb-0">{{ $jumlahKategori }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-blue shadow text-center border-radius-md">
                                        <i class="fas fa-tags text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Lokasi Barang</p>
                                        <h5 class="font-weight-bolder mb-0">{{ $jumlahLokasi }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-blue shadow text-center border-radius-md">
                                        <i class="fas fa-map-marker-alt text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Barang Di Pinjam</p>
                                        <h5 class="font-weight-bolder mb-0">{{ $jumlahPeminjam }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-blue shadow text-center border-radius-md">
                                        <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
<!-- Add Charts Section -->
<div class="row mt-4">
    <!-- Category Distribution Chart -->
    <div class="col-lg-6 mb-lg-0 mb-4">
        <div class="card z-index-2">
            <div class="card-header pb-0">
                <h6>Distribusi Barang per Kategori</h6>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="categoryChart" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Loans Chart -->
    <div class="col-lg-6">
        <div class="card z-index-2">
            <div class="card-header pb-0">
                <h6>Trend Peminjaman Barang</h6>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="loansChart" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
</div>

@push('scripts')
<script>
// Prepare data for category chart
var categoryData = @json($kategoriData);
var categoryLabels = categoryData.map(item => item.nama_kategori);
var categoryValues = categoryData.map(item => item.total);

// Create category distribution chart
var ctxCategory = document.getElementById("categoryChart").getContext("2d");
new Chart(ctxCategory, {
type: "doughnut",
data: {
    labels: categoryLabels,
    datasets: [{
        data: categoryValues,
        backgroundColor: [
            '#FF6384', 
            '#36A2EB', 
            '#FFCE56', 
            '#4BC0C0', 
            '#9966FF', 
            '#FF9F40', 
            '#76B041', 
            '#FF4500'   
            '#8A89A6',  
            '#FF4C6D', 
            '#1E90FF',  
            '#FFD700', 
            '#C71585',  
            '#87CEFA',  
            '#20B2AA',  
        ],
    }],
},
options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
        }
    }
}
});

// Prepare data for loans chart
var loansData = @json($monthlyLoans);
var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
var monthlyData = Array(12).fill(0);
loansData.forEach(item => {
monthlyData[item.month - 1] = item.total;
});

// Create monthly loans chart
var ctxLoans = document.getElementById("loansChart").getContext("2d");
new Chart(ctxLoans, {
type: "line",
data: {
    labels: months,
    datasets: [{
        label: "Peminjaman",
        tension: 0.4,
        borderWidth: 2,
        borderColor: "#5e72e4",
        backgroundColor: "rgba(94, 114, 228, 0.2)",
        fill: true,
        data: monthlyData,
    }],
},
options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        }
    },
    interaction: {
        intersect: false,
        mode: 'index',
    },
    scales: {
        y: {
            grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5]
            },
            ticks: {
                display: true,
                padding: 10,
                color: '#fbfbfb',
                font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                },
            }
        },
        x: {
            grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5]
            },
            ticks: {
                display: true,
                color: '#ccc',
                padding: 20,
                font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                },
            }
        },
    },
},
});
</script>
@endpush
@endsection
