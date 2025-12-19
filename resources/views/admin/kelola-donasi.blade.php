<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Distribusi Donasi - Panti Wredha BDK</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    
    <link rel="stylesheet" href="{{ asset('assets/css/style-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kelola-donasi.css') }}">
    
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-admin">

    <div id="donasiApp" class="admin-wrapper" v-cloak>
        <header class="top-header">
            <div class="header-left">
                <a href="{{ url('/admin') }}" style="text-decoration: none;">
                    <img src="{{ asset('assets/images/1.png') }}" alt="Logo BDK" class="header-logo">
                </a>
            </div>

            <div class="header-center">
                <div class="search-box">
                    <input type="text" v-model="searchQuery" placeholder="Cari donatur, jenis, atau petugas..." name="search">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="header-right">
                <a href="{{ url('/admin/notifikasi') }}" class="text-white text-decoration-none me-3 position-relative">
                    <i class="far fa-bell icon-bell"></i>
                    <span v-if="unreadCount > 0" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="font-size: 0.6rem;">
                        </span>
                </a>
                
                <span class="user-text me-3">Hai, ADMIN!</span>
                <i class="fas fa-user-circle icon-profile"></i>
            </div>
        </header>

        <aside class="sidebar">
            <ul class="list-unstyled">
                <li>
                    <a href="{{ url('/admin') }}">
                        <i class="fas fa-folder"></i> Dashboard
                    </a>
                </li>
                
                <li>
                    <a href="#penghuniSub" data-bs-toggle="collapse" class="dropdown-toggle">
                        <i class="fas fa-file-invoice"></i> Manajemen Data Penghuni
                    </a>
                    <ul class="collapse list-unstyled sidebar-submenu" id="penghuniSub">
                        <li><a href="{{ url('/admin/kelola-penghuni') }}"><i class="fas fa-list"></i> Data Penghuni</a></li>
                        <li><a href="{{ url('/admin/tambah-penghuni') }}"><i class="fas fa-plus"></i> Tambah Data</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#donasiSub" data-bs-toggle="collapse" class="dropdown-toggle" aria-expanded="true">
                        <i class="fas fa-box-open"></i> Manajemen Distribusi Donasi
                    </a>
                    <ul class="collapse show list-unstyled sidebar-submenu" id="donasiSub">
                        <li><a href="{{ url('/admin/kelola-donasi') }}" class="active"><i class="fas fa-history"></i> Riwayat</a></li>
                        <li><a href="{{ url('/admin/tambah-donasi') }}"><i class="fas fa-plus"></i> Tambah Donasi</a></li>
                        <li><a href="{{ url('/admin/laporan-donasi') }}"><i class="fas fa-file-alt"></i> Laporan</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#barangSub" data-bs-toggle="collapse" class="dropdown-toggle">
                        <i class="fas fa-boxes"></i> Manajemen Stok Barang
                    </a>
                    <ul class="collapse list-unstyled sidebar-submenu" id="barangSub">
                        <li><a href="{{ url('/admin/data-barang') }}"><i class="fas fa-clipboard-list"></i> Data Stok Barang</a></li>
                        <li><a href="{{ url('/admin/tambah-barang') }}"><i class="fas fa-plus"></i> Tambah Stok Barang</a></li>
                        <li><a href="{{ url('/admin/ambil-stok') }}"><i class="fas fa-minus-square"></i> Ambil Stok Barang</a></li>
                    </ul>
                </li>
            </ul>
            
            <div class="logout-wrapper">
                <a href="javascript:void(0)" @click="logoutAdmin" class="text-white text-decoration-none d-flex align-items-center gap-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div> 
        </aside>

        <main class="main-content" style="padding: 25px;">
            <div class="content-body">
                <div class="page-title-banner" style="margin-bottom: 25px;">
                    Riwayat Distribusi Donasi Panti
                </div>
                
                <div v-if="alertStatus === 'success'" class="alert alert-success text-center fw-bold mb-4" style="border-radius: 10px;">
                    Data Donasi Berhasil Ditambahkan! <i class="fas fa-check-circle"></i>
                </div>
                <div v-if="alertStatus === 'edited'" class="alert alert-info text-center fw-bold mb-4" style="border-radius: 10px;">
                    Data Donasi Berhasil Diperbarui! <i class="fas fa-check-circle"></i>
                </div>

                <div class="glass-panel" style="background-color: #ffffff; color: #333; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <a href="{{ url('/admin/tambah-donasi') }}" class="btn text-white" style="background-color: #1a5c7a; border-radius: 8px; padding: 10px 20px;">
                            <i class="fas fa-plus me-2"></i> Tambah Donasi
                        </a>
                        
                        <button class="btn btn-outline-secondary btn-sm" @click="showFilter = !showFilter">
                            <i class="fas fa-filter"></i> Filter (@{{ activeFiltersCount }})
                        </button>
                    </div>
                    
                    <div v-if="showFilter" class="mb-4 p-3 bg-light rounded">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="small text-muted">Jenis Donasi</label>
                                <select v-model="filterJenis" class="form-select form-select-sm">
                                    <option value="">Semua</option>
                                    <option v-for="j in uniqueJenis" :key="j" :value="j">@{{ j }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small text-muted">Status</label>
                                <select v-model="filterStatus" class="form-select form-select-sm">
                                    <option value="">Semua</option>
                                    <option v-for="s in uniqueStatus" :key="s" :value="s">@{{ s }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small text-muted">Petugas</label>
                                <select v-model="filterPetugas" class="form-select form-select-sm">
                                    <option value="">Semua</option>
                                    <option v-for="p in uniquePetugas" :key="p" :value="p">@{{ p }}</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button @click="resetFilter" class="btn btn-danger btn-sm w-100">Reset Filter</button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-custom">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>NO</th>
                                    <th>Tanggal</th>
                                    <th>Donatur</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Petugas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in paginatedList" :key="index">
                                    <td>@{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                                    <td>@{{ item.tanggal }}</td>
                                    <td>@{{ formatTitleCase(item.donatur) }}</td>
                                    <td>
                                        <span class="badge" :class="item.jenis === 'Uang' ? 'bg-success' : 'bg-primary'">
                                            @{{ item.jenis }}
                                        </span>
                                    </td>
                                    <td>@{{ item.jumlah }}</td>
                                    <td>
                                        <span class="badge" :class="item.status === 'Langsung' ? 'bg-info text-dark' : 'bg-warning text-dark'">
                                            @{{ item.status }}
                                        </span>
                                    </td>
                                    <td>@{{ formatTitleCase(item.petugas) }}</td>
                                    <td class="text-center">
                                        <button @click="goToEditPage(item)" class="btn btn-sm btn-outline-primary" title="Edit Detail">
                                            <i class="fas fa-file-invoice"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="paginatedList.length === 0">
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Tidak ada data donasi ditemukan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center mt-4" v-if="totalPages > 1">
                        <button @click="prevPage" :disabled="currentPage === 1" class="btn btn-link text-dark text-decoration-none btn-sm">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="mx-2 small">Halaman @{{ currentPage }} dari @{{ totalPages }}</span>
                        <button @click="nextPage" :disabled="currentPage === totalPages" class="btn btn-link text-dark text-decoration-none btn-sm">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/main-admin.js') }}"></script>
    <script src="{{ asset('assets/js/kelola-donasi.js') }}"></script>
</body>
</html>