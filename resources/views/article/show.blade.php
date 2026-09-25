@extends('layouts.landing')

@section('content')
    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="padding-top: 80px; padding-bottom: 40px; background-color: #333;">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">{{ $article->title }}</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Beranda</a></li>
            <li><a href="{{ route('article.index') }}">Artikel</a></li>
            <li class="current">{{ \Illuminate\Support\Str::limit($article->title, 20) }}</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <section id="article-details" class="portfolio-details section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-8 offset-lg-2">
            @if($article->image)
              <div class="mb-4 text-center">
                <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="img-fluid rounded" style="max-height: 500px; width: 100%; object-fit: cover;">
              </div>
            @endif
            <div class="portfolio-description text-center mb-4">
                <span class="text-muted"><i class="bi bi-calendar"></i> {{ $article->created_at->format('d M Y') }}</span>
            </div>
            <div class="portfolio-description">
              {!! $article->content !!}
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
