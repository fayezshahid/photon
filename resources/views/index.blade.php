<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" href="photon.webp">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/toastr.min.css">
    <title>PHOTON</title>
</head>
<body>
    <nav class="navbar navbar-light bg-dark fixed-top mb-3">
      <div class="container-fluid">
        <a href="{{ route('home') }}" class="navbar-brand" style="color:white;">PHOTON</a>
        <div class="dropdown">
          <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
            {{ auth()->user()->email }}
          </button>
          <div class="dropdown-menu">
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <a class="btn" onclick="this.parentNode.submit()">Logout</a>
            </form>
          </div>
        </div>
      </div>
    </nav>
    <hr style="width: 100%;margin-top:0% ; margin-bottom:0%">
    <div class="d-flex flex-row" style="margin-top:0 ;">
      <div class="d-flex container bg-light" style="width:15%; padding-bottom:100rem">
        <nav class="navbar bg-light position-absolute top-0 start-0">
          <div class="container mt-5 ">
            <ul class="navbar-nav">
              <li class="nav-item ">
                <a class="nav-link nav-bar-side" href="{{ route('home') }}"><i class="nav-bar-side fa-solid fa-images px-2"></i>Photos</a>
              </li>
              <li class="nav-item ">
                <a class="nav-link nav-bar-side" href="{{ route('explore') }}"><i class="nav-bar-side fa-solid fa-magnifying-glass px-2"></i>Explore</a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-bar-side" href="{{ route('sharing') }}"><i class="fa-solid fa-users px-2 nav-bar-side"></i>Sharing</a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-bar-side" href="{{ route('archive') }}"><i class='fa fa-box-archive px-2 nav-bar-side'></i>Archieved</a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-bar-side" href="{{ route('favourite') }}"><i class='fa fa-star px-2 nav-bar-side'></i>Favourite</a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-bar-side" href="{{ route('trash') }}"><i class='fa fa-trash px-2 nav-bar-side'></i>Trash</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
      <div class="p-4 mt-5" style="width:85%">

        @yield('content')
        
      </div>
    </div>
</body>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery-3.6.1.min.js"></script>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/js/toastr.min.js"></script>

<script>
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
</script>

@yield('scripts')

</html>