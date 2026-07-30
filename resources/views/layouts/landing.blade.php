<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Company Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('landingPage/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('landingPage/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('landingPage/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landingPage/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('landingPage/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('landingPage/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landingPage/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('landingPage/css/main.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Company
  * Template URL: https://bootstrapmade.com/company-free-html-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center">

      <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="{{ asset('landingPage/img/logo.png') }}" alt=""> -->
        <h1 class="sitename">Profil Desa</h1><span>.</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          @foreach($menus as $menu)
            @if($menu->children->count() > 0)
              <li class="dropdown"><a href="{{ url($menu->url ?? '#') }}"><span>{{ $menu->name }}</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  @foreach($menu->children as $child)
                    <li><a href="{{ url($child->url ?? '#') }}">{{ $child->name }}</a></li>
                  @endforeach
                </ul>
              </li>
            @else
              <li><a href="{{ url($menu->url ?? '#') }}" class="{{ request()->is(ltrim($menu->url, '/')) || ($menu->url == '/#hero' && request()->is('/')) ? 'active' : '' }}">{{ $menu->name }}</a></li>
            @endif
          @endforeach
          @auth
            <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
          @endauth
          @guest
            <li><a href="{{ route('login') }}">Log in</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
          @endguest
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="header-social-links">
        <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
      </div>

    </div>
  </header>

  <main class="main">
    @yield('content')
  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <span class="sitename">Profil Desa</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Jl. Balai Desa No. 1</p>
            <p>Kecamatan, Kabupaten, Kode Pos</p>
            <p class="mt-3"><strong>Telepon:</strong> <span>021-1234567</span></p>
            <p><strong>Email:</strong> <span>admin@desa.go.id</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Tautan Berguna</h4>
          <ul>
            <li><a href="#">Beranda</a></li>
            <li><a href="#">Tentang Desa</a></li>
            <li><a href="#">Layanan Publik</a></li>
            <li><a href="#">Syarat & Ketentuan</a></li>
            <li><a href="#">Kebijakan Privasi</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Layanan Kami</h4>
          <ul>
            <li><a href="#">Administrasi Penduduk</a></li>
            <li><a href="#">Surat Pengantar</a></li>
            <li><a href="#">Informasi Bansos</a></li>
            <li><a href="#">Perpustakaan Desa</a></li>
            <li><a href="#">Gedung Serbaguna</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Berita Desa</h4>
          <p>Berlangganan buletin kami untuk mendapatkan berita terbaru seputar desa!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Langganan"></div>
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Permintaan langganan Anda telah dikirim. Terima kasih!</div>
          </form>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Pemerintah Desa</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a href=“https://themewagon.com>ThemeWagon
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('landingPage/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('landingPage/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('landingPage/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('landingPage/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('landingPage/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('landingPage/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('landingPage/vendor/waypoints/noframework.waypoints.js') }}"></script>
  <script src="{{ asset('landingPage/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('landingPage/js/main.js') }}"></script>

</body>

</html>