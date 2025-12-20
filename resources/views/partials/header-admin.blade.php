<header class="top-header">
    <div class="header-left">
        <a href="{{ route('admin.dashboard') }}" style="text-decoration: none;">
            <img src="{{ asset('assets/images/1.png') }}" alt="Logo BDK" class="header-logo">
        </a>
    </div>

    <div class="header-center">
        <div class="search-box">
            <input type="text" v-model="searchQuery" placeholder="Cari data di halaman ini..." name="search">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <div class="header-right">
        <a href="{{ route('admin.notifikasi') }}" 
           class="text-white text-decoration-none me-3 position-relative" 
           :class="{ active: activePage === 'notifikasi' }">
            <i class="far fa-bell icon-bell"></i>
            <span v-if="unreadCount > 0" 
                  class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" 
                  style="font-size: 0.6rem;">
            </span>
        </a>
        
        <span class="user-text me-3">Hai, ADMIN!</span>
        <i class="fas fa-user-circle icon-profile"></i>
    </div>
</header>