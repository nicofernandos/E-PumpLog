<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <!-- Dashboard -->
    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('/') }}">
        <i class="mdi mdi-view-dashboard menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <!-- Laporan Injeksi -->
    <li class="nav-item nav-category">Laporan Injeksi</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#laporan" aria-expanded="false" aria-controls="laporan">
        <i class="menu-icon mdi mdi-file-document-box"></i>
        <span class="menu-title">Laporan Harian</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="laporan">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ url('laporan/daftar') }}">Daftar Laporan</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('laporan/disetujui') }}">Laporan Disetujui</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('laporan/revisi') }}">Laporan Revisi</a></li>
        </ul>
      </div>
    </li>

    <!-- Monitoring Pompa -->
    <li class="nav-item nav-category">Monitoring</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#monitoring" aria-expanded="false" aria-controls="monitoring">
        <i class="menu-icon mdi mdi-chart-line"></i>
        <span class="menu-title">Monitoring Pompa</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="monitoring">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ url('monitoring/statistik') }}">Statistik Injeksi</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('monitoring/grafik') }}">Grafik Tekanan & RPM</a></li>
        </ul>
      </div>
    </li>

    <!-- Data Master -->
    <li class="nav-item nav-category">Manajemen Data</li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#data-master" aria-expanded="false" aria-controls="data-master">
        <i class="menu-icon mdi mdi-database"></i>
        <span class="menu-title">Data Master</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="data-master">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ url('data/operator') }}">Data Operator</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('data/pompa') }}">Data Pompa</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('data/lokasi') }}">Data Lokasi SP</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('data/users') }}">Data User</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#user-management" aria-expanded="false" aria-controls="user-management">
        <i class="menu-icon mdi mdi-account-group"></i>
        <span class="menu-title">Manajemen Users</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="user-management">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ url('user/add') }}">Tambah Users</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('user/list') }}">Daftar Users</a></li>
        </ul>
      </div>
    </li>

    <!-- Export dan Cetak -->
    <li class="nav-item nav-category">Laporan & Export</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#export" aria-expanded="false" aria-controls="export">
        <i class="menu-icon mdi mdi-file-export"></i>
        <span class="menu-title">Export & Cetak</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="export">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ url('laporan/export-pdf') }}">Export ke PDF</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('laporan/export-excel') }}">Export ke Excel</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('laporan/rekap-bulanan') }}">Rekap Bulanan</a></li>
        </ul>
      </div>
    </li>

    <!-- Pengaturan Akun -->
    <li class="nav-item nav-category">Akun</li>
    <li class="nav-item">
      <a class="nav-link" href="{{ url('profil') }}">
        <i class="menu-icon mdi mdi-account-circle-outline"></i>
        <span class="menu-title">Profil</span>
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
