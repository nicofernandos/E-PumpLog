<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- DASHBOARD -->
    <li class="nav-item {{ request()->routeIs('user.dashboard.index') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('user.dashboard.index') }}">
        <i class="mdi mdi-view-dashboard menu-icon"></i>
        <span class="menu-title">Dashboard Utama</span>
      </a>
    </li>

    <!-- PENGINPUTAN LAPORAN (CORE FUNCTION) -->
    <li class="nav-item nav-category">Penginputan Data</li>
    <li class="nav-item {{ request()->routeIs('user.report.index') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('user.report.index') }}">
        <i class="mdi mdi-pencil-box-outline menu-icon"></i>
        <span class="menu-title">Input Laporan Harian</span>
      </a>
    </li>
    
    <li class="nav-item {{ request()->routeIs('user.dailyreport.index') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('user.dailyreport.index') }}">
        <i class="mdi mdi-pencil-lock-outline menu-icon"></i>
        <span class="menu-title">Draft Laporan Harian</span>
      </a>
    </li>

    <!-- LAPORAN SAYA & STATUS -->
    <li class="nav-item nav-category">Status Laporan Saya</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#laporan-saya" 
         aria-expanded="{{ request()->routeIs('user.laporan.*') ? 'true' : 'false' }}" 
         aria-controls="laporan-saya">
        <i class="menu-icon mdi mdi-file-document-box"></i>
        <span class="menu-title">Daftar Laporan</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ request()->routeIs('user.laporan.*') ? 'show' : '' }}" id="laporan-saya">
        <ul class="nav flex-column sub-menu">
          <!-- Daftar Semua Laporan User -->
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.laporan.index') ? 'active' : '' }}" 
               href="">Semua Laporan</a>
          </li>
          <!-- Laporan yang sudah disetujui -->
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.laporan.approved') ? 'active' : '' }}" 
               href="">Disetujui</a>
          </li>
          <!-- Laporan yang perlu direvisi -->
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.laporan.revision') ? 'active' : '' }}" 
               href="">Perlu Revisi</a>
          </li>
        </ul>
      </div>
    </li>

    <!-- PENGATURAN AKUN -->
    <li class="nav-item nav-category">Akun</li>
    <li class="nav-item {{ request()->is('user/profil') ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('user/profil') }}">
        <i class="menu-icon mdi mdi-account-circle-outline"></i>
        <span class="menu-title">Profil Saya</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('logout') }}">
        <i class="menu-icon mdi mdi-logout"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>

  </ul>
</nav>