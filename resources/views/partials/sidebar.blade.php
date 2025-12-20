<aside class="sidebar">
    <ul class="list-unstyled">
        {{-- Dashboard --}}
        <li>
            <a href="{{ route('admin.dashboard') }}" 
               :class="{ active: activePage === 'dashboard' }">
                <i class="fas fa-folder"></i> Dashboard
            </a>
        </li>
        
        {{-- Manajemen Data Penghuni --}}
        <li>
            <a href="#penghuniSub" 
               data-bs-toggle="collapse" 
               class="dropdown-toggle"
               :aria-expanded="activePage === 'penghuni' ? 'true' : 'false'">
                <i class="fas fa-file-invoice"></i> Manajemen Data Penghuni
            </a>
            <ul class="list-unstyled sidebar-submenu" 
                id="penghuniSub"
                :class="{ show: activePage === 'penghuni', collapse: true }">
                <li>
                    <a href="{{ route('admin.kelola-penghuni') }}"
                       :class="{ active: currentUrl.includes('kelola-penghuni') }">
                        <i class="fas fa-list"></i> Data Penghuni
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tambah-penghuni') }}"
                       :class="{ active: currentUrl.includes('tambah-penghuni') }">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </li>
            </ul>
        </li>

        {{-- Manajemen Distribusi Donasi --}}
        <li>
            <a href="#donasiSub" 
               data-bs-toggle="collapse" 
               class="dropdown-toggle"
               :aria-expanded="activePage === 'donasi' ? 'true' : 'false'">
                <i class="fas fa-box-open"></i> Manajemen Distribusi Donasi
            </a>
            <ul class="list-unstyled sidebar-submenu" 
                id="donasiSub"
                :class="{ show: activePage === 'donasi', collapse: true }">
                <li>
                    <a href="{{ route('admin.kelola-donasi') }}"
                       :class="{ active: currentUrl.includes('kelola-donasi') }">
                        <i class="fas fa-history"></i> Riwayat
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tambah-donasi') }}"
                       :class="{ active: currentUrl.includes('tambah-donasi') }">
                        <i class="fas fa-plus"></i> Tambah Donasi
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan-donasi') }}"
                       :class="{ active: currentUrl.includes('laporan-donasi') }">
                        <i class="fas fa-file-alt"></i> Laporan
                    </a>
                </li>
            </ul>
        </li>

        {{-- Manajemen Stok Barang --}}
        <li>
            <a href="#barangSub" 
               data-bs-toggle="collapse" 
               class="dropdown-toggle"
               :aria-expanded="activePage === 'barang' ? 'true' : 'false'">
                <i class="fas fa-boxes"></i> Manajemen Stok Barang
            </a>
            <ul class="list-unstyled sidebar-submenu" 
                id="barangSub"
                :class="{ show: activePage === 'barang', collapse: true }">
                <li>
                    <a href="{{ route('admin.data-barang') }}"
                       :class="{ active: currentUrl.includes('data-barang') }">
                        <i class="fas fa-clipboard-list"></i> Data Stok Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tambah-barang') }}"
                       :class="{ active: currentUrl.includes('tambah-barang') }">
                        <i class="fas fa-plus"></i> Tambah Stok Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ambil-stok') }}"
                       :class="{ active: currentUrl.includes('ambil-stok') }">
                        <i class="fas fa-minus-square"></i> Ambil Stok Barang
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    
    {{-- Logout Button --}}
    <div class="logout-wrapper">
        <a href="javascript:void(0)" 
           @click="logoutAdmin" 
           class="text-white text-decoration-none d-flex align-items-center gap-2">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div> 
</aside>