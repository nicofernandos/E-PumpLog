<!DOCTYPE html>
<html lang="en">
<head>
  @include('user.partials.head')
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <div class="container-scroller">
    
    <!-- Navbar -->

    @include('user.partials.navbar')
    
    <div class="container-fluid page-body-wrapper">
      
      <!-- Sidebar -->
      @include('user.partials.sidebar')
      
      <div class="main-panel">
        <div class="content-wrapper">
          
          <!-- Content -->
          @yield('content')
          
        </div>
        
        <!-- Footer -->
        @include('user.partials.footer')
        
      </div>
    </div>
  </div>

  <!-- Scripts -->
  @include('user.partials.scripts')
  @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])
</body>
</html>