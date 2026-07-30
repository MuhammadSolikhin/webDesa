@extends('layouts.landing')

@section('content')


    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        @foreach($heroes as $hero)
        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
          <img src="{{ Storage::url($hero->image) }}" alt="">
          <div class="container">
            <h2>{{ $hero->title }}</h2>
            <p>{{ $hero->description }}</p>
            <a href="about.html" class="btn-get-started">Read More</a>
          </div>
        </div><!-- End Carousel Item -->
        @endforeach

        <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

        <ol class="carousel-indicators"></ol>

      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row position-relative">

          <div class="col-lg-7 about-img" data-aos="zoom-out" data-aos-delay="200"><img src="{{ asset($settings['about_image'] ?? 'landingPage/img/about.jpg') }}"></div>

          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
            <h2 class="inner-title">{{ $settings['about_title'] ?? '' }}</h2>
            <div class="our-story">
              <h4>Profil Desa</h4>
              <h3>{{ $settings['about_subtitle'] ?? '' }}</h3>
              <p>{{ $settings['about_description'] ?? '' }}</p>
              <ul>
                @foreach($settings['about_points'] ?? [] as $point)
                <li><i class="bi bi-check-circle"></i> <span>{{ $point }}</span></li>
                @endforeach
              </ul>
              <p>{{ $settings['about_summary'] ?? '' }}</p>

              <div class="watch-video d-flex align-items-center position-relative">
                <i class="bi bi-play-circle"></i>
                <a href="{{ $settings['about_video_url'] ?? '#' }}" class="glightbox stretched-link">Watch Video</a>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="services" class="services section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Fasilitas & Layanan Publik</h2>
        <p>Berbagai layanan dan fasilitas yang tersedia untuk kemudahan masyarakat desa</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          @foreach($services as $service)
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
            <div class="service-item item-cyan position-relative">
              <div class="icon">
                {!! $service->icon !!}
              </div>
              <a href="service-details.html" class="stretched-link">
                <h3>{{ $service->title }}</h3>
              </a>
              <p>{{ $service->description }}</p>
            </div>
          </div><!-- End Service Item -->
        @endforeach

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Objek Wisata</h2>
        <p>Jelajahi keindahan alam, kekayaan budaya, dan pesona wisata di desa kami</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

          <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
            <li data-filter="*" class="filter-active">Semua</li>
            <li data-filter=".filter-alam">Alam</li>
            <li data-filter=".filter-budaya">Budaya</li>
            <li data-filter=".filter-kuliner">Kuliner</li>
          </ul><!-- End Portfolio Filters -->

          <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

            @foreach($portfolios as $portfolio)
            <div class="col-lg-4 col-md-6 portfolio-item isotope-item {{ $portfolio->category }}">
              <img src="{{ Storage::url($portfolio->image) }}" class="img-fluid" alt="{{ $portfolio->title }}">
              <div class="portfolio-info">
                <h4>{{ $portfolio->title }}</h4>
                <p>{{ $portfolio->description }}</p>
                <a href="{{ Storage::url($portfolio->image) }}" title="{{ $portfolio->title }}" data-gallery="portfolio-gallery-{{ str_replace('filter-', '', $portfolio->category) }}" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                <a href="#" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
              </div>
            </div><!-- End Portfolio Item -->
            @endforeach

          </div><!-- End Portfolio Container -->

        </div>

      </div>

    </section><!-- /Portfolio Section -->

    <!-- Clients Section -->
    <section id="clients" class="clients section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Mitra Desa</h2>
        <p>Lembaga dan instansi yang bekerja sama dalam membangun desa kami</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0 clients-wrap">

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('landingPage/img/clients/client-1.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('landingPage/img/clients/client-2.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('landingPage/img/clients/client-3.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('landingPage/img/clients/client-4.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('landingPage/img/clients/client-5.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('landingPage/img/clients/client-6.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('landingPage/img/clients/client-7.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

          <div class="col-xl-3 col-md-4 client-logo">
            <img src="{{ asset('landingPage/img/clients/client-8.png') }}" class="img-fluid" alt="">
          </div><!-- End Client Item -->

        </div>

      </div>

    </section><!-- /Clients Section -->

  
@endsection
