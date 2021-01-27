<!DOCTYPE html>
<html>
<head>
    <!-- ICON -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <link rel="icon" type="image/png" href="{{ URL::asset('/assets/img/theme/geofencing.jpg') }}">
    <title>Geofencing Pasien</title>

    <!-- Css Link -->
    @include('layouts.linkcss')

    @section('css')
    @show
</head>

<body>
  <!-- Sidebar -->
  @include('layouts.sidebar')
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    @include('layouts.navbar')
    <!-- Header -->
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        @yield('header')
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <!-- Footer -->
        @yield('content')
        @include('layouts.footer')
    </div>
  </div>
  <!-- Argon Scripts -->
  <!-- Core -->
  @include('layouts.linkjs')

  @section('js')
  @show
</body>
</html>
