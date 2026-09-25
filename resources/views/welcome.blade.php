@extends('layouts.landing')

@section('content')

@push('styles')
<style>
    :root {
        --theme-green-overlay: rgba(20, 83, 45, 0.85); /* Dark green overlay */
    }
    
    .parallax-section {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
        color: white;
    }
    
    .parallax-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: var(--theme-green-overlay);
        z-index: 1;
    }
    
    .parallax-section .container {
        position: relative;
        z-index: 2;
    }
    
    /* Text inside parallax sections */
    .parallax-section .section-title h2, 
    .parallax-section .section-title p,
    .parallax-section h2.inner-title,
    .parallax-section .our-story h3,
    .parallax-section .our-story h4,
    .parallax-section .our-story p,
    .parallax-section .our-story li span {
        color: white !important;
    }

    /* Cards should remain white and text inside should be dark */
    .parallax-section .card, 
    .parallax-section .service-item, 
    .parallax-section .portfolio-info {
        background-color: white !important;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .parallax-section .card h4, 
    .parallax-section .card p, 
    .parallax-section .card span,
    .parallax-section .service-item h3, 
    .parallax-section .service-item p,
    .parallax-section .portfolio-info h4, 
    .parallax-section .portfolio-info p {
        color: #333 !important;
    }

    /* Button overrides */
    .parallax-section .btn-outline-primary {
        border-color: #10b981;
        color: #10b981;
    }
    .parallax-section .btn-outline-primary:hover {
        background-color: #10b981;
        color: white;
    }

    /* Solid green section style */
    .solid-green-section {
        background-color: var(--theme-green-overlay) !important;
        color: white;
    }
    
    .solid-green-section .section-title h2, 
    .solid-green-section .section-title p,
    .solid-green-section h2.inner-title,
    .solid-green-section .our-story h3,
    .solid-green-section .our-story h4,
    .solid-green-section .our-story p,
    .solid-green-section .our-story li span {
        color: white !important;
    }

    .solid-green-section .card, 
    .solid-green-section .service-item, 
    .solid-green-section .portfolio-info {
        background-color: white !important;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .solid-green-section .card h4, 
    .solid-green-section .card p, 
    .solid-green-section .card span,
    .solid-green-section .service-item h3, 
    .solid-green-section .service-item p,
    .solid-green-section .portfolio-info h4, 
    .solid-green-section .portfolio-info p {
        color: #333 !important;
    }

    /* Adjust about image z-index */
    .about-img {
        position: relative;
        z-index: 2;
    }
</style>
@endpush

@php
    $bgImage = isset($settings['parallax_bg_1']) && !empty($settings['parallax_bg_1']) 
                ? Storage::url($settings['parallax_bg_1']) 
                : ((isset($heroes[0]) && !empty($heroes[0]->image)) ? Storage::url($heroes[0]->image) : asset('landingPage/img/hero-carousel/hero-carousel-1.jpg'));
    
    $bgImage2 = isset($settings['parallax_bg_2']) && !empty($settings['parallax_bg_2'])
                ? Storage::url($settings['parallax_bg_2'])
                : asset('landingPage/img/services.jpg');
@endphp

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

    <!-- Latest Articles Section -->
    <section id="latest-articles" class="services section light-background">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Artikel Terbaru</h2>
        <p>Ikuti perkembangan berita dan informasi terbaru dari kami</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row gy-4">
          @forelse($articles as $article)
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
              @if($article->image)
                <img src="{{ Storage::url($article->image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 250px; object-fit: cover;">
              @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                  <span class="text-muted"><i class="bi bi-image text-secondary" style="font-size: 3rem;"></i></span>
                </div>
              @endif
              <div class="card-body p-4 d-flex flex-column">
                <h4 class="card-title fw-bold mb-3">{{ $article->title }}</h4>
                <p class="card-text text-muted mb-4">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 120) }}</p>
                <div class="mt-auto">
                    <a href="{{ route('article.show', $article) }}" class="btn btn-outline-primary rounded-pill px-4">Baca Selengkapnya</a>
                </div>
              </div>
            </div>
          </div>
          @empty
          <div class="col-12 text-center">
            <p class="text-muted">Belum ada artikel.</p>
          </div>
          @endforelse
        </div>
        @if($articles->count() > 0)
        <div class="text-center mt-5">
            <a href="{{ route('article.index') }}" class="btn btn-primary rounded-pill px-4 py-2">Lihat Semua Artikel</a>
        </div>
        @endif
      </div>
    </section><!-- /Latest Articles Section -->

    <!-- About Section -->
    <section id="about" class="about section parallax-section" style="background-image: url('{{ $bgImage }}');">

      <div class="container">

        <div class="row position-relative">

          @php
              $aboutImage = $settings['about_image'] ?? 'landingPage/img/about.jpg';
              $aboutImageUrl = str_starts_with($aboutImage, 'landingPage/') ? asset($aboutImage) : Storage::url($aboutImage);
          @endphp
          <div class="col-lg-7 about-img" data-aos="zoom-out" data-aos-delay="200"><img src="{{ $aboutImageUrl }}"></div>

          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
            <h2 class="inner-title">{{ $settings['about_title'] ?? '' }}</h2>
            <div class="our-story">
              <h4>Profil Kec. Ketungau Hulu</h4>
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
                <a href="{{ $settings['about_video_url'] ?? '#' }}" class="glightbox stretched-link">{{ $settings['about_video_text'] ?? 'Watch Video' }}</a>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="services" class="services section solid-green-section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>JELAJAHI KETUNGAU HULU</h2>
        <p>Temukan keindahan alam, kekayaan budaya, dan potensi ekowisata di kawasan Ketungau Hulu.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          @foreach($services as $service)
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
            <div class="service-item item-cyan position-relative">
              <div class="icon">
                {!! $service->icon !!}
              </div>
              <a href="{{ route('service.show', $service) }}" class="stretched-link">
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
    <section id="portfolio" class="portfolio section light-background">

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
              @php
                  $images = is_array($portfolio->image) ? $portfolio->image : (empty($portfolio->image) ? [] : [$portfolio->image]);
                  $firstImage = count($images) > 0 ? $images[0] : '';
              @endphp
              <img src="{{ Storage::url($firstImage) }}" class="img-fluid" alt="{{ $portfolio->title }}">
              <div class="portfolio-info">
                <h4>{{ $portfolio->title }}</h4>
                <p>{{ $portfolio->description }}</p>
                <a href="{{ Storage::url($firstImage) }}" title="{{ $portfolio->title }}" data-gallery="portfolio-gallery-{{ $portfolio->id }}" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                @if(count($images) > 1)
                  @foreach(array_slice($images, 1) as $img)
                    <a href="{{ Storage::url($img) }}" title="{{ $portfolio->title }}" data-gallery="portfolio-gallery-{{ $portfolio->id }}" class="glightbox" style="display: none;"></a>
                  @endforeach
                @endif
                <a href="{{ route('portfolio.show', $portfolio) }}" title="Lihat Detail & Peta" class="details-link"><i class="bi bi-info-circle"></i></a>
              </div>
            </div><!-- End Portfolio Item -->
            @endforeach

          </div><!-- End Portfolio Container -->

        </div>

      </div>

    </section><!-- /Portfolio Section -->

    <!-- Team Section -->
    <section id="team" class="team section solid-green-section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Tim Kami</h2>
        <p>Orang-orang hebat di balik kesuksesan kawasan kami</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row gy-5">
          @forelse($teams as $team)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
              <div class="member d-flex flex-column align-items-center text-center p-4 card border-0 shadow-sm rounded-4 h-100" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="pic mb-4 overflow-hidden rounded-circle shadow" style="width: 150px; height: 150px;">
                  @if($team->image)
                    <img src="{{ Storage::url($team->image) }}" class="img-fluid" alt="{{ $team->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                  @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 100%; height: 100%;">
                      <i class="bi bi-person text-secondary" style="font-size: 4rem;"></i>
                    </div>
                  @endif
                </div>
                <div class="member-info">
                  <h4 class="fw-bold mb-2">{{ $team->name }}</h4>
                  <span class="text-muted d-block mb-3" style="font-size: 0.9rem;">{{ $team->position }}</span>
                </div>
              </div>
            </div><!-- End Team Member -->
          @empty
            <div class="col-12 text-center text-white">
              <p>Belum ada data anggota tim.</p>
            </div>
          @endforelse
        </div>
      </div>
    </section><!-- /Team Section -->

    <!-- Tour Packages Section -->
    <section id="tour-packages" class="services section parallax-section" style="background-image: url('{{ $bgImage2 }}');">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Paket Wisata</h2>
        <p>Pilih paket wisata terbaik untuk pengalaman tak terlupakan di desa kami</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          @foreach($tourPackages as $package)
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)'">
              @if(!empty($package->image))
                  @php
                      $images = is_array($package->image) ? $package->image : [$package->image];
                  @endphp
                  @if(count($images) > 0)
                      <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top" alt="{{ $package->name }}" style="height: 250px; object-fit: cover;">
                  @else
                      <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                          <span class="text-muted"><i class="bi bi-image text-secondary" style="font-size: 3rem;"></i></span>
                      </div>
                  @endif
              @else
              <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                  <span class="text-muted"><i class="bi bi-image text-secondary" style="font-size: 3rem;"></i></span>
              </div>
              @endif
              <div class="card-body p-4 d-flex flex-column">
                <h4 class="card-title fw-bold mb-3" style="color: var(--heading-color);">{{ $package->name }}</h4>
                <p class="card-text text-muted mb-4">{{ \Illuminate\Support\Str::limit($package->description, 120) }}</p>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <span class="fw-bold fs-5" style="color: var(--accent-color);">{{ $package->price ? 'Rp ' . number_format($package->price, 0, ',', '.') : 'Gratis' }}</span>
                    <div>
                        <a href="{{ route('tour-package.show', $package) }}" class="btn btn-outline-secondary rounded-pill px-3 me-2">Detail</a>
                        <a href="{{ route('checkout.show', $package) }}" class="btn text-white rounded-pill px-4" style="background-color: var(--accent-color);">Pesan</a>
                    </div>
                </div>
              </div>
            </div>
          </div><!-- End Tour Package Item -->
          @endforeach

        </div>

      </div>

    </section><!-- /Tour Packages Section -->


@endsection
