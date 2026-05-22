<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- DASHBOARD -->
    <li class="nav-item {{ request()->routeIs('user.dashboard.index') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('user.dashboard.index') }}">
        <i class="mdi mdi-view-dashboard menu-icon"></i>
        <span class="menu-title">Dashboard</span>
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

      <a class="nav-link"
        data-bs-toggle="collapse"
        href="#laporan-saya"

        aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}"
        aria-controls="laporan-saya">

        <i class="menu-icon mdi mdi-file-document-box"></i>

        <span class="menu-title">Daftar Laporan</span>

        <i class="menu-arrow"></i>

      </a>

      <div class="collapse {{ request()->routeIs('report.*') ? 'show' : '' }}"
          id="laporan-saya">

        <ul class="nav flex-column sub-menu">

          <!-- Semua -->
          <li class="nav-item">
            <a class="nav-link {{ request()->missing('status') ? 'active' : '' }}"
              href="{{ route('user.report.list') }}">

              Semua Laporan

            </a>
          </li>

          <!-- Submitted -->
          <li class="nav-item">
            <a class="nav-link {{ request('status') == 'submitted' ? 'active' : '' }}"
              href="{{ route('user.report.list', ['status' => 'submitted']) }}">

              Menunggu Approval

            </a>
          </li>

          <!-- Approved -->
          <li class="nav-item">
            <a class="nav-link {{ request('status') == 'approved' ? 'active' : '' }}"
              href="{{ route('user.report.list', ['status' => 'approved']) }}">

              Disetujui

            </a>
          </li>

          <!-- Revision -->
          <li class="nav-item">
            <a class="nav-link {{ request('status') == 'revision' ? 'active' : '' }}"
              href="{{ route('user.report.list', ['status' => 'revision']) }}">

              Perlu Revisi

            </a>
          </li>

          <!-- Rejected -->
          <li class="nav-item">
            <a class="nav-link {{ request('status') == 'rejected' ? 'active' : '' }}"
              href="{{ route('user.report.index', ['status' => 'rejected']) }}">

              Ditolak

            </a>
          </li>

        </ul>

      </div>

    </li>

    <!-- PENGATURAN AKUN -->
    <li class="nav-item nav-category">Akun</li>
    <li class="nav-item {{ request()->is('profile') ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('profile') }}">
        <i class="menu-icon mdi mdi-account-circle-outline"></i>
        <span class="menu-title">Profil Saya</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="#" 
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="menu-icon mdi mdi-logout"></i>
          <span class="menu-title">Logout</span>
      </a>
  </li>

  <form id="logout-form" action="{{ url('logout') }}" method="POST" class="d-none">
      @csrf
  </form>

  </ul>
</nav>