@extends('layouts.landing')

@section('content')
    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <div class="mb-4" style="font-size: 4rem; color: var(--accent-color);">
                {!! $service->icon !!}
              </div>
              <h1>{{ $service->title }}</h1>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">{{ $service->title }}</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5 text-center">
                    <div class="card-text fs-5 text-start" style="line-height: 1.8;">
                        {!! nl2br(e($service->description)) !!}
                    </div>
                    <div class="mt-5">
                        <a href="{{ url('/#services') }}" class="btn btn-outline-primary px-4 py-2 rounded-pill"><i class="bi bi-arrow-left"></i> Kembali ke Layanan</a>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    @if(str_contains(strtolower($service->title), 'galeri'))
    <!-- Gallery Section -->
    <section id="gallery" class="portfolio section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Galeri Foto</h2>
        <p>Kumpulan foto-foto terbaik dari kawasan kami.</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
          <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
            @forelse($galleries as $gallery)
              <div class="col-lg-4 col-md-6 portfolio-item isotope-item">
                <div class="portfolio-content h-100">
                  <img src="{{ asset('storage/' . $gallery->image) }}" class="img-fluid" alt="{{ $gallery->title }}" style="width: 100%; height: 250px; object-fit: cover;">
                  <div class="portfolio-info">
                    @if($gallery->title)
                      <h4>{{ $gallery->title }}</h4>
                    @endif
                    @if($gallery->video_url)
                        <a href="{{ $gallery->video_url }}" title="{{ $gallery->title }}" data-gallery="portfolio-gallery-gallery" class="glightbox preview-link"><i class="bi bi-play-circle text-danger"></i></a>
                    @else
                        <a href="{{ asset('storage/' . $gallery->image) }}" title="{{ $gallery->title }}" data-gallery="portfolio-gallery-gallery" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                    @endif
                  </div>
                </div>
              </div><!-- End Gallery Item -->
            @empty
              <div class="col-12 text-center">
                <p>Belum ada foto galeri.</p>
              </div>
            @endforelse
          </div><!-- End Gallery Container -->
        </div>
      </div>
    </section><!-- /Gallery Section -->
    @endif
@endsection
