@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h2>Nos biens</h2>
  <div class="row g-4">
    @foreach($props as $p)
      <div class="col-md-4">
        <div class="card h-100">
          @if($p->images->first())
            <img src="{{ asset('storage/'.$p->images->first()->path) }}" class="card-img-top" style="height:200px;object-fit:cover;">
          @endif
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $p->title }}</h5>
            <p class="card-text">{{ Str::limit($p->description,80) }}</p>
            <div class="mt-auto d-flex justify-content-between align-items-center">
              <a href="{{ route('properties.show',$p) }}" class="btn btn-outline-dark btn-sm">Voir</a>
              <small class="text-muted">{{ $p->price ? number_format($p->price) . ' FCFA' : '' }}</small>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-4">{{ $props->links() }}</div>
</div>
@endsection
