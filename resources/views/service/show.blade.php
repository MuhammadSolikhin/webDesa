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
@endsection
