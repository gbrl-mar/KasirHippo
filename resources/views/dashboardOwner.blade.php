<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F5F3ED;
            min-height: 100vh;
        }

        /* Gaya untuk sidebar pada desktop */
        .sidebar {
            background-color: #4B2E2B;
            color: white;
            min-height: 100vh;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
        }

        /* Gaya untuk drawer pada mobile */
        @media (max-width: 767.98px) {
            .sidebar-offcanvas {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar-offcanvas.show {
                transform: translateX(0);
            }
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            font-size: 1rem;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: white;
            font-weight: bold;
            background-color: #3a2220;
        }
        
        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .sidebar h2 {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 1rem;
        }

        .btn-brown {
            background-color: #4B2E2B;
            color: white;
            border: none;
        }

        .btn-brown:hover {
            background-color: #3a2220;
            color: white;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        #salesChart,
        #productChart {
            max-height: 350px;
        }

        main {
            padding-left: 0;
        }

        @media (min-width: 768px) {
            main {
                margin-left: 250px;
            }
        }
        .logout-btn-container {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px /* Push the logout button to the bottom */
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <nav class="sidebar col-md-3 col-lg-2 d-md-block p-3 d-flex flex-column sidebar-offcanvas" id="sidebar-wrapper">
            <div class="d-flex justify-content-end d-md-none">
                <button type="button" class="btn-close btn-close-white" onclick="toggleSidebar()"></button>
            </div>
            <h2 class="text-center mt-2 mb-4">Dashboard Owner</h2>
            <ul class="nav flex-column flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-section="dashboard">
                        <i class="fas fa-tachometer-alt"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-section="sales-report">
                        <i class="fas fa-chart-line"></i>Laporan Penjualan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-section="product-management">
                        <i class="fas fa-utensils"></i>Manajemen Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-section="employee-management">
                        <i class="fas fa-users"></i>Manajemen Karyawan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-section="inventory-management">
                        <i class="fas fa-box"></i>Manajemen Bahan Baku
                    </a>
                </li>
            </ul>
            <div class="logout-btn-container mt-auto">
                <button id="logout-btn" class="btn btn-brown w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top d-md-none">
                <div class="container-fluid">
                    <button class="btn btn-brown" type="button" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h5 class="my-2 ms-3">Selamat Datang, Owner!</h5>
                </div>
            </nav>

            <div class="pt-5 mt-3 pt-md-0 mt-md-0">
                <div id="dashboard" class="content-section active">
                    <div class="p-4 mb-4 bg-white rounded-3 shadow-sm">
                        <h1>Dashboard Overview</h1>
                        <p class="lead">Ringkasan kinerja bisnis Anda hari ini</p>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6 col-xl-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-muted">Penjualan Hari Ini</h5>
                                    <h1 class="display-5 fw-bold" id="sales-today-value">Rp 0</h1>
                                    <small class="text-success" id="sales-today-change">+0% dari kemarin</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-muted">Total Transaksi</h5>
                                    <h1 class="display-5 fw-bold" id="transactions-today-value">0</h1>
                                    <small class="text-success" id="transactions-today-change">+0% dari kemarin</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xl-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-muted">Produk Terlaris Hari Ini</h5>
                                    <h1 class="display-5 fw-bold" id="top-product-name">-</h1>
                                    <small class="text-muted" id="top-product-qty">0 porsi terjual</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-lg-7">
                            <div class="card shadow-sm h-100">
                                <div class="card-header">Grafik Penjualan Mingguan</div>
                                <div class="card-body">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card shadow-sm h-100">
                                <div class="card-header">Komposisi Produk Terlaris</div>
                                <div class="card-body">
                                    <canvas id="productChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="sales-report" class="content-section">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1>Laporan Penjualan</h1>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2 align-items-end border-bottom pb-3 mb-3">
                                <div>
                                    <label for="reportType" class="form-label">Tipe Laporan</label>
                                    <select id="reportType" class="form-select" onchange="toggleFilterInputs(this.value)">
                                        <option value="daily" selected>Harian</option>
                                        <option value="weekly">Mingguan</option>
                                        <option value="monthly">Bulanan</option>
                                        <option value="yearly">Tahunan</option>
                                    </select>
                                </div>
                                <div id="daily-filter">
                                    <label for="date-input" class="form-label">Tanggal</label>
                                    <input type="date" id="date-input" class="form-control">
                                </div>
                                <div id="weekly-filter" style="display: none;">
                                    <label for="week-input" class="form-label">Pilih Minggu</label>
                                    <input type="week" id="week-input" class="form-control">
                                </div>
                                <div id="monthly-filter" style="display: none;">
                                    <label for="month-input" class="form-label">Bulan</label>
                                    <input type="month" id="month-input" class="form-control">
                                </div>
                                <div id="yearly-filter" style="display: none;">
                                    <label for="year-input" class="form-label">Tahun</label>
                                    <input type="number" id="year-input" class="form-control" placeholder="Contoh: 2025">
                                </div>
                                <button class="btn btn-brown" onclick="fetchReport()">Terapkan</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 id="report-period-title">Data Penjualan</h5>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead id="table-header-row">
                                    </thead>
                                    <tbody id="salesTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="product-management" class="content-section">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1>Manajemen Produk</h1>
                        <button class="btn btn-brown" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus me-1"></i> Tambah Produk
                        </button>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-header">
                            Daftar Produk
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productsTableBody">
                                        <tr>
                                            <td colspan="6" class="text-center">Memuat data...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <nav aria-label="Product pagination" class="mt-3">
                                <ul class="pagination justify-content-center" id="productsPagination">
                                    </ul>
                            </nav>
                        </div>
                    </div>
                </div>

                <div id="employee-management" class="content-section">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1>Manajemen Karyawan</h1>
                        <button class="btn btn-brown">
                            <i class="fas fa-plus me-1"></i> Tambah Karyawan
                        </button>
                    </div>
                </div>

                <div id="inventory-management" class="content-section">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1>Manajemen Bahan Baku</h1>
                        <button class="btn btn-brown" data-bs-toggle="modal" data-bs-target="#addBahanModal">
                            <i class="fas fa-plus me-1"></i> Tambah Bahan Baku
                        </button>
                    </div>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            Daftar Bahan Baku
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Nama Bahan</th>
                                            <th scope="col">Stok Saat Ini</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="inventory-table-body">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Memuat data...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-header">
                            Riwayat Pembelian Bahan Baku
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tanggal Beli</th>
                                            <th scope="col">Nama Bahan</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Total Biaya</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purchase-history-body">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                Memuat riwayat pembelian...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="modal fade" id="addBahanModal" tabindex="-1" aria-labelledby="addBahanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBahanModalLabel">Tambah Bahan Baku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBahanForm">
                        <div class="mb-3">
                            <label for="addBahanName" class="form-label">Nama Bahan</label>
                            <input type="text" class="form-control" id="addBahanName" required>
                        </div>
                        <div class="mb-3">
                            <label for="addBahanUnit" class="form-label">Satuan (Unit)</label>
                            <input type="text" class="form-control" id="addBahanUnit" placeholder="Contoh: kg, liter, pcs" required>
                        </div>
                        <div class="mb-3">
                            <label for="addBahanStock" class="form-label">Stok Awal</label>
                            <input type="number" step="0.01" class="form-control" id="addBahanStock" value="0" required>
                            <div class="form-text">Masukkan jumlah stok yang ada saat ini. Isi 0 jika belum ada.</div>
                        </div>
                        <div id="addBahanErrors" class="alert alert-danger d-none"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveBahanBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="editProductId">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="editProductName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editProductDescription"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="editProductPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductCategory" class="form-label">Kategori</label>
                            <select class="form-select" id="editProductCategory"></select>
                        </div>
                        <div class="mb-3">
                            <label for="editProductStatus" class="form-label">Status</label>
                            <select class="form-select" id="editProductStatus">
                                <option value="1">Tersedia</option>
                                <option value="0">Tidak Tersedia</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="simpan-btn">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="beliBahanModal" tabindex="-1" aria-labelledby="beliBahanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="beliBahanModalLabel">Beli Bahan Baku</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="beliBahanForm">
                        <input type="hidden" id="beliIngredientId">
                        <div class="mb-3">
                            <label for="beliQuantity" class="form-label">Jumlah Dibeli</label>
                            <input type="number" step="0.01" class="form-control" id="beliQuantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="beliCost" class="form-label">Total Biaya</label>
                            <input type="number" step="0.01" class="form-control" id="beliCost" required>
                        </div>
                        <div class="mb-3">
                            <label for="beliDate" class="form-label">Tanggal Pembelian</label>
                            <input type="date" class="form-control" id="beliDate" required>
                        </div>
                        <div id="beliBahanErrors" class="alert alert-danger d-none"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="saveBeliBtn">Simpan Pembelian</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ===========================================
        // SCRIPT NAVIGASI & SIDEBAR
        // ===========================================
        function toggleSidebar() {
            document.getElementById('sidebar-wrapper').classList.toggle('show');
        }

        document.addEventListener("DOMContentLoaded", function () {
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('beliDate');
            if (dateInput) {
                dateInput.value = today;
            }
            loadDashboardData();
            populateCategoryDropdown('addProductCategory');
            populateCategoryDropdown('editProductCategory');

            document.getElementById('logout-btn').addEventListener('click', () => {
                // Remove the token from local storage
                localStorage.removeItem('token');
                // Redirect to the login page
                window.location.href = '/'; 
            });
        });

        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
                const sectionId = this.getAttribute('data-section');
                document.getElementById(sectionId).classList.add('active');

                if (window.innerWidth < 768) {
                    toggleSidebar();
                }

                if (sectionId === 'dashboard') {
                    loadDashboardData();
                } else if (sectionId === 'sales-report') {
                    initializeReportPage();
                } else if (sectionId === 'product-management') {
                    fetchProducts();
                } else if (sectionId === 'inventory-management') {
                    loadIngredients();
                    loadPurchaseHistory();
                }
            });
        });
    </script>

    <script>
        // ===========================================
        // SCRIPT DASHBOARD OVERVIEW
        // ===========================================
        let salesChart, productChart;

        async function loadDashboardData() {
            const apiUrl = "/api/dashboard-overview";
            const token = localStorage.getItem("token");

            try {
                const response = await fetch(apiUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const responseData = await response.json();

                if (responseData.success) {
                    const data = responseData.data;
                    document.getElementById("sales-today-value").innerText = "Rp " + data.overview.sales_today.value.toLocaleString('id-ID');
                    document.getElementById("sales-today-change").innerText = data.overview.sales_today.percentage_change + "% dari kemarin";
                    document.getElementById("transactions-today-value").innerText = data.overview.transactions_today.count;
                    document.getElementById("transactions-today-change").innerText = data.overview.transactions_today.percentage_change + "% dari kemarin";

                    if (data.overview.top_product_today) {
                        document.getElementById("top-product-name").innerText = data.overview.top_product_today.name;
                        document.getElementById("top-product-qty").innerText = data.overview.top_product_today.quantity_sold + " porsi terjual";
                    }

                    if (salesChart) salesChart.destroy();
                    if (productChart) productChart.destroy();

                    const salesCtx = document.getElementById("salesChart").getContext("2d");
                    salesChart = new Chart(salesCtx, {
                        type: "line",
                        data: {
                            labels: data.charts.weekly_sales.labels,
                            datasets: [{
                                label: "Penjualan (Rp)",
                                data: data.charts.weekly_sales.data,
                                borderColor: "#4B2E2B",
                                backgroundColor: "rgba(75, 46, 43, 0.1)",
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });

                    const productCtx = document.getElementById("productChart").getContext("2d");
                    productChart = new Chart(productCtx, {
                        type: "doughnut",
                        data: {
                            labels: data.charts.top_products.labels,
                            datasets: [{
                                data: data.charts.top_products.data,
                                backgroundColor: ["#4B2E2B", "#A0522D", "#D2B48C", "#F5F3ED", "#8B4513"]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "bottom"
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error("Gagal ambil data dashboard:", error);
                alert("Tidak bisa memuat dashboard. Silakan login ulang.");
                window.location.href = "/";
            }
        }
    </script>
    
    <script>
        // ===========================================
        // SCRIPT MANAJEMEN PRODUK
        // ===========================================
        const productsTableBody = document.getElementById('productsTableBody');
        const productsPagination = document.getElementById('productsPagination');
        const ITEMS_PER_PAGE = 10;
        let allProducts = [];
        let currentPage = 1;

        async function fetchProducts() {
            try {
                const response = await axios.get('/api/products');
                allProducts = response.data.products;
                if (!allProducts || allProducts.length === 0) {
                    productsTableBody.innerHTML = `<tr><td colspan="6" class="text-center">Belum ada produk.</td></tr>`;
                    productsPagination.innerHTML = '';
                    return;
                }
                renderProductsTable(allProducts, currentPage);
                setupPagination(allProducts);
            } catch (error) {
                console.error("Gagal memuat produk:", error);
                productsTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>`;
                productsPagination.innerHTML = '';
            }
        }
        
        function renderProductsTable(products, page) {
            productsTableBody.innerHTML = '';
            const start = (page - 1) * ITEMS_PER_PAGE;
            const end = start + ITEMS_PER_PAGE;
            const paginatedProducts = products.slice(start, end);

            paginatedProducts.forEach(prod => {
                const statusBadge = prod.is_available ? `<span class="badge bg-success">Tersedia</span>` : `<span class="badge bg-secondary">Tidak Tersedia</span>`;
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${prod.name}</td>
                    <td>${prod.category ? prod.category.name : '-'}</td>
                    <td>Rp ${Number(prod.price).toLocaleString('id-ID')}</td>
                    <td>${prod.stock ?? '-'}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="editProduct(${prod.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${prod.id})"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                productsTableBody.appendChild(tr);
            });
        }
        
        function setupPagination(products) {
            productsPagination.innerHTML = '';
            const pageCount = Math.ceil(products.length / ITEMS_PER_PAGE);

            if (pageCount <= 1) return;

            const createPageItem = (text, page, isActive = false, isDisabled = false) => {
                const li = document.createElement('li');
                li.className = `page-item ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.innerHTML = text;
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (!isDisabled) {
                        currentPage = page;
                        renderProductsTable(products, currentPage);
                        setupPagination(products);
                    }
                });
                li.appendChild(a);
                return li;
            };

            productsPagination.appendChild(createPageItem('&laquo;', currentPage - 1, false, currentPage === 1));

            for (let i = 1; i <= pageCount; i++) {
                productsPagination.appendChild(createPageItem(i, i, i === currentPage));
            }

            productsPagination.appendChild(createPageItem('&raquo;', currentPage + 1, false, currentPage === pageCount));
        }

        function editProduct(id) {
            axios.get(`/api/products/${id}`)
                .then(res => {
                    const prod = res.data.product;
                    document.getElementById("editProductId").value = prod.id;
                    document.getElementById("editProductName").value = prod.name;
                    document.getElementById("editProductDescription").value = prod.description ?? '';
                    document.getElementById("editProductPrice").value = prod.price;
                    document.getElementById("editProductCategory").value = prod.category?.id_categories ?? '';
                    document.getElementById("editProductStatus").value = prod.is_available ? '1' : '0';
                })
                .catch(err => console.error("Gagal ambil data produk:", err));
        }

        function deleteProduct(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) return;
            axios.delete(`/api/products/${id}`)
                .then(response => {
                    alert(response.data.message || 'Produk berhasil dihapus!');
                    fetchProducts();
                })
                .catch(error => {
                    console.error('Gagal menghapus produk:', error);
                    alert('Gagal menghapus produk.');
                });
        }

        document.getElementById('simpan-btn').addEventListener('click', async function(e) {
            e.preventDefault();
            const productId = document.getElementById('editProductId').value;
            const payload = {
                name: document.getElementById('editProductName').value,
                description: document.getElementById('editProductDescription').value,
                price: document.getElementById('editProductPrice').value,
                category_id: document.getElementById('editProductCategory').value,
                is_available: document.getElementById('editProductStatus').value === '1'
            };
            if (!confirm('Apakah Anda yakin ingin menyimpan perubahan ini?')) return;
            try {
                const response = await axios.put(`/api/products/${productId}`, payload);
                if (response.data.message) {
                    alert(response.data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
                    modal.hide();
                    fetchProducts();
                }
            } catch (error) {
                console.error("Gagal update produk:", error);
                if (error.response?.status === 422) {
                    const validationErrors = error.response.data.errors;
                    let errorMessages = "Harap perbaiki kesalahan berikut:\n";
                    for (const field in validationErrors) {
                        errorMessages += `- ${validationErrors[field].join(', ')}\n`;
                    }
                    alert(errorMessages);
                } else {
                    alert('Terjadi kesalahan saat menyimpan perubahan.');
                }
            }
        });

        document.getElementById('add-simpan-btn').addEventListener('click', async function(e) {
            e.preventDefault();
            const payload = {
                name: document.getElementById('addProductName').value,
                description: document.getElementById('addProductDescription').value,
                price: document.getElementById('addProductPrice').value,
                category_id: document.getElementById('addProductCategory').value,
                is_available: document.getElementById('addProductStatus').value === '1'
            };
            try {
                const response = await axios.post('/api/products', payload);
                if (response.data.message) {
                    alert(response.data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                    modal.hide();
                    fetchProducts();
                }
            } catch (error) {
                console.error("Gagal tambah produk:", error);
                if (error.response?.status === 422) {
                    const validationErrors = error.response.data.errors;
                    let errorMessages = "Harap perbaiki kesalahan berikut:\n";
                    for (const field in validationErrors) {
                        errorMessages += `- ${validationErrors[field].join(', ')}\n`;
                    }
                    alert(errorMessages);
                } else {
                    alert('Terjadi kesalahan saat menambah produk.');
                }
            }
        });

        async function populateCategoryDropdown(selectElementId) {
            const selectElement = document.getElementById(selectElementId);
            if (!selectElement) return console.error(`Elemen dropdown dengan ID "${selectElementId}" tidak ditemukan.`);
            try {
                const response = await axios.get('/api/categories');
                if (response.data.success) {
                    const categories = response.data.categories;
                    selectElement.innerHTML = '';
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Pilih Kategori...';
                    selectElement.appendChild(defaultOption);
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id_categories;
                        option.textContent = category.name;
                        selectElement.appendChild(option);
                    });
                }
            } catch (error) {
                console.error(`Gagal memuat kategori untuk #${selectElementId}:`, error);
                selectElement.innerHTML = `<option value="">Gagal memuat kategori</option>`;
            }
        }
    </script>

    <script>
        // ===========================================
        // SCRIPT LAPORAN PENJUALAN
        // ===========================================
        const reportTypeSelect = document.getElementById('reportType');
        const dateInput = document.getElementById('date-input');
        const weekInput = document.getElementById('week-input');
        const monthInput = document.getElementById('month-input');
        const yearInput = document.getElementById('year-input');
        const salesTableBody = document.getElementById('salesTableBody');
        const tableHeaderRow = document.getElementById('table-header-row');
        const reportPeriodTitle = document.getElementById('report-period-title');

        function toggleFilterInputs(type) {
            document.getElementById('daily-filter').style.display = 'none';
            document.getElementById('weekly-filter').style.display = 'none';
            document.getElementById('monthly-filter').style.display = 'none';
            document.getElementById('yearly-filter').style.display = 'none';
            document.getElementById(`${type}-filter`).style.display = 'block';
        }

        async function fetchReport() {
            const type = reportTypeSelect.value;
            let apiUrl = '';
            let payload = {};

            switch (type) {
                case 'daily':
                    apiUrl = '/api/reports/daily';
                    payload = {
                        date: dateInput.value
                    };
                    break;
                case 'weekly':
                    apiUrl = '/api/reports/weekly';
                    payload = {
                        week: weekInput.value
                    };
                    break;
                case 'monthly':
                    apiUrl = '/api/reports/monthly';
                    payload = {
                        month: monthInput.value
                    };
                    break;
                case 'yearly':
                    apiUrl = '/api/reports/yearly';
                    payload = {
                        year: yearInput.value
                    };
                    break;
                default:
                    console.error("Tipe laporan tidak dikenal:", type);
                    return;
            }

            salesTableBody.innerHTML = `<tr><td colspan="4" class="text-center">Memuat data...</td></tr>`;
            try {
                const response = await axios.post(apiUrl, payload);
                if (response.data.success) {
                    const report = response.data.report;
                    reportPeriodTitle.innerText = `Data Penjualan - ${report.period}`;
                    renderSalesTable(report.type, report.header, report.data);
                } else {
                    const errors = response.data.errors ? Object.values(response.data.errors).join(', ') : 'Gagal memuat data.';
                    salesTableBody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">${errors}</td></tr>`;
                }
            } catch (error) {
                console.error("Gagal mengambil laporan:", error);
                const errorMessage = error.response?.data?.message || error.message;
                salesTableBody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Terjadi error: ${errorMessage}</td></tr>`;
            }
        }

        function renderSalesTable(type, header, data) {
            salesTableBody.innerHTML = '';
            if (type === 'daily') {
                tableHeaderRow.innerHTML = `<tr><th>Waktu</th><th>${header}</th><th>Nama Pelanggan</th><th>Total Penjualan</th></tr>`;
            } else {
                tableHeaderRow.innerHTML = `<tr><th>${header}</th><th>Total Transaksi</th><th>Total Penjualan</th><th>Rata-rata per Transaksi</th></tr>`;
            }
            if (!data || data.length === 0) {
                salesTableBody.innerHTML = `<tr><td colspan="4" class="text-center">Tidak ada data untuk periode ini.</td></tr>`;
                return;
            }
            data.forEach(item => {
                const tr = document.createElement('tr');
                if (type === 'daily') {
                    tr.innerHTML = `
                        <td>${new Date(item.created_at).toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</td>
                        <td>${item.transaction_code}</td>
                        <td>${item.customer_name ?? '-'}</td>
                        <td>Rp ${parseInt(item.total_price).toLocaleString('id-ID')}</td>
                    `;
                } else {
                    const avg = item.total_transactions > 0 ? (item.total_sales / item.total_transactions) : 0;
                    let periodLabel = item.period_label;
                    if (type === 'weekly' || type === 'monthly') {
                        periodLabel = new Date(item.period_label + 'T00:00:00').toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                    } else if (type === 'yearly') {
                        periodLabel = new Date(item.period_label + '-01T00:00:00').toLocaleDateString('id-ID', {
                            month: 'long',
                            year: 'numeric'
                        });
                    }
                    tr.innerHTML = `
                        <td>${periodLabel}</td>
                        <td>${item.total_transactions}</td>
                        <td>Rp ${parseInt(item.total_sales).toLocaleString('id-ID')}</td>
                        <td>Rp ${Math.round(avg).toLocaleString('id-ID')}</td>
                    `;
                }
                salesTableBody.appendChild(tr);
            });
        }

        function initializeReportPage() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            dateInput.value = `${yyyy}-${mm}-${dd}`;
            monthInput.value = `${yyyy}-${mm}`;
            yearInput.value = yyyy;
            const getWeekNumber = (d) => {
                d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
                d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
                const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
                return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
            };
            const weekNum = getWeekNumber(today);
            weekInput.value = `${yyyy}-W${String(weekNum).padStart(2, '0')}`;
            toggleFilterInputs('daily');
            fetchReport();
        }
    </script>
    
    <script>
        // ===========================================
        // SCRIPT MANAJEMEN BAHAN BAKU
        // ===========================================
        const API_URL = "/api/ingredients";
        const PURCHASE_API = "/api/purchases";
        const inventoryTableBody = document.getElementById("inventory-table-body");
        const saveBahanBtn = document.getElementById("saveBahanBtn");
        const addBahanErrors = document.getElementById("addBahanErrors");
        const beliModalEl = document.getElementById("beliBahanModal");
        const beliModal = beliModalEl ? new bootstrap.Modal(beliModalEl) : null;
        const beliForm = document.getElementById("beliBahanForm");
        const saveBeliBtn = document.getElementById("saveBeliBtn");
        const beliErrors = document.getElementById("beliBahanErrors");
        const beliIngredientId = document.getElementById("beliIngredientId");
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const CSRF_META = csrfMeta ? csrfMeta.content : null;

        function getCookie(name) {
            const cookies = document.cookie ? document.cookie.split('; ') : [];
            for (let c of cookies) {
                const [k, ...rest] = c.split('=');
                if (k === name) return rest.join('=');
            }
            return null;
        }
        const XSRF = getCookie('XSRF-TOKEN') ? decodeURIComponent(getCookie('XSRF-TOKEN')) : null;

        function buildHeaders(json = true) {
            const headers = {
                "Accept": "application/json"
            };
            if (json) headers["Content-Type"] = "application/json";
            if (CSRF_META) {
                headers["X-CSRF-TOKEN"] = CSRF_META;
            } else if (XSRF) {
                headers["X-XSRF-TOKEN"] = XSRF;
            }
            return headers;
        }

        async function loadIngredients() {
            if (!inventoryTableBody) return;
            inventoryTableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Memuat data...</td></tr>`;
            try {
                const res = await fetch(API_URL, {
                    headers: buildHeaders(false)
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.message || "Gagal memuat data");
                const ingredients = result.data?.data ?? result.data ?? [];
                if (!ingredients.length) {
                    inventoryTableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>`;
                    return;
                }
                inventoryTableBody.innerHTML = "";
                ingredients.forEach(ing => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${ing.name}</td>
                        <td>${ing.current_stock}</td>
                        <td>${ing.unit}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success me-1" onclick="openBeliModal(${ing.id})">Beli</button>
                            <button class="btn btn-sm btn-warning me-1 edit-btn" data-id="${ing.id}">Edit</button>
                            <button class="btn btn-sm btn-danger hapus-btn" data-id="${ing.id}">Hapus</button>
                        </td>
                    `;
                    inventoryTableBody.appendChild(tr);
                });
                document.querySelectorAll(".hapus-btn").forEach(btn => {
                    btn.addEventListener("click", async e => {
                        const id = e.currentTarget.dataset.id;
                        if (confirm("Yakin ingin menghapus bahan ini?")) {
                            await deleteIngredient(id);
                        }
                    });
                });
                document.querySelectorAll(".edit-btn").forEach(btn => {
                    btn.addEventListener("click", async e => {
                        const id = e.currentTarget.dataset.id;
                        const qtyStr = prompt("Masukkan jumlah yang ingin dikurangi (angka):");
                        const qty = parseFloat(qtyStr);
                        if (!isNaN(qty) && qty > 0) await editIngredient(id, qty);
                        else alert("Jumlah tidak valid.");
                    });
                });
            } catch (err) {
                inventoryTableBody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Error: ${err.message}</td></tr>`;
                console.error(err);
            }
        }

        async function addIngredient() {
            if (!saveBahanBtn) return;
            addBahanErrors.classList.add("d-none");
            addBahanErrors.innerHTML = "";
            const name = document.getElementById("addBahanName").value.trim();
            const unit = document.getElementById("addBahanUnit").value.trim();
            const stock = document.getElementById("addBahanStock").value;
            try {
                const res = await fetch(API_URL, {
                    method: "POST",
                    headers: buildHeaders(true),
                    body: JSON.stringify({
                        name,
                        unit,
                        current_stock: stock
                    })
                });
                const result = await res.json();
                if (!res.ok) {
                    if (result.errors) {
                        let errHtml = "<ul>";
                        Object.values(result.errors).forEach(arr => arr.forEach(m => errHtml += `<li>${m}</li>`));
                        errHtml += "</ul>";
                        addBahanErrors.innerHTML = errHtml;
                        addBahanErrors.classList.remove("d-none");
                    } else {
                        alert(result.message || "Gagal menambahkan bahan");
                    }
                    return;
                }
                document.getElementById("addBahanForm").reset();
                const addModalEl = document.getElementById("addBahanModal");
                const addModalInst = addModalEl ? bootstrap.Modal.getInstance(addModalEl) : null;
                if (addModalInst) addModalInst.hide();
                loadIngredients();
            } catch (err) {
                alert("Terjadi kesalahan: " + err.message);
                console.error(err);
            }
        }

        async function deleteIngredient(id) {
            try {
                const res = await fetch(`${API_URL}/${id}`, {
                    method: "DELETE",
                    headers: buildHeaders(false)
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.message || "Gagal menghapus bahan");
                alert(result.message || "Berhasil dihapus");
                loadIngredients();
            } catch (err) {
                alert("Error: " + err.message);
                console.error(err);
            }
        }

        async function editIngredient(id, qty) {
            try {
                const getRes = await fetch(`${API_URL}/${id}`, {
                    headers: buildHeaders(false)
                });
                const getData = await getRes.json();
                if (!getRes.ok) throw new Error(getData.message || "Gagal ambil data bahan");
                const ing = getData.data;
                const oldStock = parseFloat(ing.current_stock) || 0;
                const newStock = oldStock - qty;
                if (newStock < 0) {
                    alert("Stok tidak boleh negatif!");
                    return;
                }
                await updateStock(id, ing, newStock);
            } catch (err) {
                alert("Error: " + err.message);
                console.error(err);
            }
        }

        async function updateStock(id, ing, newStock) {
            try {
                const res = await fetch(`${API_URL}/${id}`, {
                    method: "PUT",
                    headers: buildHeaders(true),
                    body: JSON.stringify({
                        name: ing.name,
                        unit: ing.unit,
                        current_stock: newStock
                    })
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || "Gagal update stok");
                alert("Stok berhasil diperbarui.");
                loadIngredients();
            } catch (err) {
                alert("Error: " + err.message);
                console.error(err);
            }
        }

        function openBeliModal(id) {
            if (!beliModal) return alert("Modal beli tidak ditemukan.");
            if (beliForm) beliForm.reset();
            if (beliErrors) {
                beliErrors.classList.add("d-none");
                beliErrors.innerHTML = "";
            }
            if (beliIngredientId) beliIngredientId.value = id;
            beliModal.show();
        }

        if (saveBeliBtn) {
            saveBeliBtn.addEventListener("click", async () => {
                if (!beliIngredientId) return;
                if (beliErrors) {
                    beliErrors.classList.add("d-none");
                    beliErrors.innerHTML = "";
                }
                const id = beliIngredientId.value;
                const qty = parseFloat(document.getElementById("beliQuantity").value);
                const cost = parseFloat(document.getElementById("beliCost").value);
                const date = document.getElementById("beliDate").value;
                if (isNaN(qty) || qty <= 0 || isNaN(cost) || cost < 0 || !date) {
                    if (beliErrors) {
                        beliErrors.classList.remove("d-none");
                        beliErrors.innerHTML = "Input tidak valid!";
                    } else alert("Input tidak valid!");
                    return;
                }
                try {
                    const res = await fetch(PURCHASE_API, {
                        method: "POST",
                        headers: buildHeaders(true),
                        body: JSON.stringify({
                            ingredient_id: id,
                            quantity_purchased: qty,
                            cost: cost,
                            purchase_date: date
                        })
                    });
                    const result = await res.json();
                    if (!res.ok) {
                        if (result.errors && beliErrors) {
                            let errHtml = "<ul>";
                            Object.values(result.errors).forEach(arr => arr.forEach(m => errHtml += `<li>${m}</li>`));
                            errHtml += "</ul>";
                            beliErrors.innerHTML = errHtml;
                            beliErrors.classList.remove("d-none");
                        } else {
                            throw new Error(result.message || "Gagal mencatat pembelian");
                        }
                        return;
                    }
                    if (beliModal) beliModal.hide();
                    alert(result.message || "Pembelian berhasil");
                    loadIngredients();
                    loadPurchaseHistory();
                } catch (err) {
                    if (beliErrors) {
                        beliErrors.classList.remove("d-none");
                        beliErrors.innerHTML = "Error: " + err.message;
                    } else alert("Error: " + err.message);
                    console.error(err);
                }
            });
        }

        async function loadPurchaseHistory() {
            const tbody = document.getElementById("purchase-history-body");
            if (!tbody) return;
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Memuat riwayat pembelian...</td></tr>`;
            try {
                const res = await fetch(PURCHASE_API, {
                    method: "GET",
                    headers: {
                        "Accept": "application/json"
                    }
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.message || "Gagal memuat riwayat");
                const purchases = result.data?.data;
                if (!purchases || purchases.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Belum ada riwayat pembelian.</td></tr>`;
                    return;
                }
                tbody.innerHTML = "";
                purchases.forEach((purchase, index) => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${purchase.purchase_date}</td>
                        <td>${purchase.ingredient?.name || "-"}</td>
                        <td>${purchase.quantity_purchased} ${purchase.ingredient?.unit || ""}</td>
                        <td>Rp ${Number(purchase.cost).toLocaleString()}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error: ${err.message}</td></tr>`;
                console.error(err);
            }
        }
        if (saveBahanBtn) saveBahanBtn.addEventListener("click", addIngredient);
        window.openBeliModal = openBeliModal;
    </script>
</body>
</html>