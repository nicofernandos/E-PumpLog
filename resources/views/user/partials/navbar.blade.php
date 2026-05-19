<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row border-bottom border-primary shadow-sm">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
        <span class="icon-menu"></span>
      </button>
    </div>
    <div>
      <a class="navbar-brand brand-logo" href="{{ url('/user/dashboard') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="180" height="40" viewBox="0 0 180 40">
          <text x="0" y="28" font-family="Montserrat, sans-serif" font-size="30" font-weight="700" fill="#00AEEF">E</text>
          <text x="20" y="28" font-family="Montserrat, sans-serif" font-size="25" font-weight="700" fill="#1A237E">PumpLog</text>
        </svg>
      </a>

      <a class="navbar-brand brand-logo-mini" href="{{ url('/user/dashboard') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="35" viewBox="0 0 35 35">
          <text x="0" y="30" font-family="Montserrat, sans-serif" font-size="18" font-weight="700" fill="#00AEEF">EPL</text>
        </svg>
      </a>
    </div>
  </div>
  
  <div class="navbar-menu-wrapper d-flex align-items-top">
    <ul class="navbar-nav">
      <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
        @if(Request::is('user/dashboard') || Request::is('dashboard'))
          <h1 class="welcome-text">Selamat Datang, <span class="text-black fw-bold">{{ Auth::user()->name ?? 'User' }}</span></h1>
          <h3 class="welcome-sub-text">Sistem Monitoring Pompa Air - {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</h3>
        @else
          <h1 class="welcome-text">
            @if(Request::is('user/pompa*') || Request::is('pompa*'))
              <span class="text-black fw-bold">Data Pompa</span>
            @elseif(Request::is('user/log*') || Request::is('log*'))
              <span class="text-black fw-bold">Log Aktivitas</span>
            @elseif(Request::is('user/lokasi*') || Request::is('lokasi*'))
              <span class="text-black fw-bold">Lokasi Stasiun Pompa</span>
            @elseif(Request::is('user/profil*') || Request::is('profil*'))
              <span class="text-black fw-bold">Profil Saya</span>
            @elseif(Request::is('user/laporan*') || Request::is('laporan*'))
              <span class="text-black fw-bold">Laporan Pompa</span>
            @elseif(Request::is('user/report*') || Request::is('report*'))
              <span class="text-black fw-bold">Input Laporan Harian</span>
            @elseif(Request::is('user/dailyreport*') || Request::is('dailyreport*'))
              <span class="text-black fw-bold">Draft Laporan Harian</span>
            @else
              <span class="text-black fw-bold">{{ ucfirst(Request::segment(2) ?? Request::segment(1) ?? 'Page') }}</span>
            @endif
          </h1>
        @endif
      </li>
    </ul>

    <ul class="navbar-nav ms-auto">
      <!-- User Profile Dropdown -->
      <li class="nav-item dropdown d-none d-lg-block user-dropdown">
        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="img-xs rounded-circle" src="{{ asset('assets/images/faces/face8.jpg') }}" alt="Profile image">
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          <div class="dropdown-header text-center">
            <img class="img-md rounded-circle" src="{{ asset('images/faces/face8.jpg') }}" alt="Profile image">
            <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name ?? 'User' }}</p>
            <p class="fw-light text-muted mb-0">{{ Auth::user()->email ?? 'user@epumplog.com' }}</p>
          </div>
          <a class="dropdown-item" href="{{ url('/user/profil') }}">
            <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Profil Saya
          </a>
          <a class="dropdown-item" href="{{ url('/user/log-aktivitas') }}">
            <i class="dropdown-item-icon mdi mdi-clipboard-text text-primary me-2"></i> Log Aktivitas
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i> Keluar
          </a>
          <form id="logout-form" action="{{ url('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </div>
      </li>
    </ul>
    
    <!-- Tombol Menu Mobile - KEMBALI KE KANAN -->
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>