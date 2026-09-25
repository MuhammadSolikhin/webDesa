@extends('layouts.landing')

@section('content')
    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="padding-top: 80px; padding-bottom: 40px; background-color: #333;">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Artikel</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Beranda</a></li>
            <li class="current">Artikel</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <section id="articles" class="services section light-background">
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
          <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada artikel.</p>
          </div>
          @endforelse
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $articles->links() }}
        </div>
      </div>
    </section>
@endsection
