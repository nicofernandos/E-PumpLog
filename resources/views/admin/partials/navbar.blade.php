<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
        <span class="icon-menu"></span>
      </button>
    </div>
    <div>
        <a class="navbar-brand brand-logo" href="{{ url('/') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="180" height="40" viewBox="0 0 180 40">
                <text x="0" y="28" font-family="Montserrat, sans-serif" font-size="30" font-weight="700" fill="#00AEEF">E</text>
                <text x="20" y="28" font-family="Montserrat, sans-serif" font-size="25" font-weight="700" fill="#1A237E">PumpLog</text>
            </svg>
        </a>

        <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="35" viewBox="0 0 35 35">
                <text x="0" y="30" font-family="Montserrat, sans-serif" font-size="18" font-weight="700" fill="#00AEEF">EPL</text>
            </svg>
        </a>
    </div>
  </div>
  
  <div class="navbar-menu-wrapper d-flex align-items-top">
    <ul class="navbar-nav">
        <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            @if(Request::is('dashboard') || Request::is('admin/dashboard') || Request::is('/'))
                {{-- Tampilan untuk Dashboard --}}
                <h1 class="welcome-text">Selamat Datang, <span class="text-black fw-bold">{{ Auth::user()->name ?? 'Supervisor' }}</span></h1>
                <h3 class="welcome-sub-text">Ringkasan Laporan Minggu Ini: **5** Menunggu Validasi</h3>
            @else
                {{-- Tampilan untuk Halaman Lain --}}
                <h1 class="welcome-text">
                    @if(Request::is('users*') || Request::is('admin/users*'))
                        <span class="text-black fw-bold">Data Users</span>
                    @elseif(Request::is('profile*') || Request::is('admin/profile*'))
                        <span class="text-black fw-bold">Profil Saya</span>
                    @elseif(Request::is('laporan*') || Request::is('admin/laporan*'))
                        <span class="text-black fw-bold">Laporan Injeksi</span>
                    @elseif(Request::is('monitoring*') || Request::is('admin/monitoring*'))
                        <span class="text-black fw-bold">Monitoring Pompa</span>
                    @elseif(Request::is('data-master*') || Request::is('admin/data-master*'))
                        <span class="text-black fw-bold">Data Master</span>
                    @else
                        <span class="text-black fw-bold">{{ ucfirst(Request::segment(1) ?? 'Page') }}</span>
                    @endif
                </h1>
            @endif
        </li>
    </ul>

    <ul class="navbar-nav ms-auto">
    
        {{-- Calendar/Datepicker - Hanya tampil di halaman tertentu --}}
        @if(
            Request::is('dashboard') || 
            Request::is('admin/dashboard') || 
            Request::is('/') ||
            Request::is('laporan*') || 
            Request::is('admin/laporan*') ||
            Request::is('monitoring*') || 
            Request::is('admin/monitoring*')
        )
        <li class="nav-item d-none d-lg-block">
            <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                <span class="input-group-addon input-group-prepend border-right">
                    <span class="icon-calendar input-group-text calendar-icon"></span>
                </span>
                <input type="text" class="form-control" placeholder="Pilih Tanggal Laporan">
            </div>
        </li>

        @endif

        <li class="nav-item dropdown d-none d-lg-block user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="img-xs rounded-circle" src="{{ asset('assets/images/faces/face8.jpg') }}" alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                    <img class="img-md rounded-circle" src="{{ asset('assets/images/faces/face8.jpg') }}" alt="Profile image">
                    <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name ?? 'Supervisor Name' }}</p>
                    <p class="fw-light text-muted mb-0">{{ Auth::user()->email ?? 'supervisor@eptest.com' }}</p>
                </div>
                <a class="dropdown-item" href="/profile"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Profil Saya</a>
                <a class="dropdown-item" href="/laporan/export"><i class="dropdown-item-icon mdi mdi-download text-primary me-2"></i> Unduh Laporan</a>
                <div class="dropdown-divider"></div>
                
                {{-- Logout --}}
                 <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i> Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form> 
            </div>
        </li>
    </ul>
    
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
        <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>