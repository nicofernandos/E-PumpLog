<!DOCTYPE html>
<html lang="en">
<head>
  @include('admin.partials.head')
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <div class="container-scroller">
    
    <!-- Navbar -->

    @include('admin.partials.navbar')
    
    <div class="container-fluid page-body-wrapper">
      
      <!-- Settings Panel -->
      @include('admin.partials.settings')
      
      <!-- Sidebar -->
      @include('admin.partials.sidebar')
      
      <div class="main-panel">
        <div class="content-wrapper">
          
          <!-- Content -->
          @yield('content')
          
        </div>
        
        <!-- Footer -->
        @include('admin.partials.footer')
        
      </div>
    </div>
  </div>

  <!-- Scripts -->
  @include('admin.partials.scripts')
  @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])
</body>
</html>