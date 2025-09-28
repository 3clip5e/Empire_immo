@extends('layouts.app')

@section('content')
<div class="container py-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Bonjour, {{ auth()->user()->name }}</h2>
    <span class="badge bg-info text-dark">Rôle : {{ ucfirst(auth()->user()->role) }}</span>
  </div>

  {{-- Recherche & filtres côté client --}}
  @if(auth()->user()->role !== 'bailleur')
    <div class="card shadow-sm mb-5 p-4 border-0">
      <h5 class="fw-semibold mb-3">🔍 Trouver un logement</h5>
      <form action="{{ route('properties.index') }}" method="GET" class="row g-3 align-items-end">
        
        <div class="col-md-4">
          <label class="form-label">Ville / Quartier</label>
          <input type="text" name="q" class="form-control" placeholder="Ex: Yaoundé, Warda...">
        </div>
        
        <div class="col-md-3">
          <label class="form-label">Type de bien</label>
          <select name="type" class="form-select">
            <option value="">Tous</option>
            <option value="studio">Studio</option>
            <option value="chambre">Chambre étudiant</option>
            <option value="appartement">Appartement</option>
            <option value="villa">Villa</option>
          </select>
        </div>
        
        <div class="col-md-3">
          <label class="form-label">Prix max</label>
          <input type="number" name="max_price" class="form-control" placeholder="FCFA">
        </div>
        
        <div class="col-md-2">
          <button class="btn btn-warning w-100">Rechercher</button>
        </div>
      </form>
    </div>
  @endif

  {{-- Vue bailleur --}}
  @if(auth()->user()->role === 'bailleur')
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-semibold">Vos annonces</h4>
      <a href="{{ route('properties.create') }}" class="btn btn-warning">+ Ajouter un bien</a>
    </div>

    @if(auth()->user()->properties->isEmpty())
      <div class="alert alert-info">Vous n’avez encore ajouté aucun bien.</div>
    @else
      <div class="row g-4">
        @foreach(auth()->user()->properties as $p)
          <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
              @if($p->images->first())
                <img src="{{ asset('storage/'.$p->images->first()->path) }}" 
                     class="card-img-top" style="height:200px; object-fit:cover;">
              @else
                <div class="bg-light d-flex align-items-center justify-content-center" 
                     style="height:200px;">
                  <span class="text-muted">Pas d’image</span>
                </div>
              @endif
              <div class="card-body d-flex flex-column">
                <h5 class="fw-bold">{{ $p->title }}</h5>
                <p class="text-muted small mb-2">{{ $p->city }} — 
                   {{ $p->price ? number_format($p->price).' FCFA' : 'Prix sur demande' }}</p>
                <div class="mt-auto d-flex gap-2">
                  <a href="{{ route('properties.edit',$p) }}" 
                     class="btn btn-sm btn-outline-primary">✏️ Éditer</a>
                  <form action="{{ route('properties.destroy',$p) }}" 
                        method="POST" onsubmit="return confirm('Supprimer ce bien ?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">🗑️ Supprimer</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  {{-- Vue client --}}
  @else
    <h4 class="fw-semibold mb-4">🏠 Résultats récents</h4>
    <div class="row g-4">
      @php
        $properties = \App\Models\Property::latest()->take(12)->get();
      @endphp
      @forelse($properties as $p)
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            @if($p->images->first())
              <img src="{{ asset('storage/'.$p->images->first()->path) }}" 
                   class="card-img-top" style="height:220px; object-fit:cover;">
            @else
              <div class="bg-light d-flex align-items-center justify-content-center" 
                   style="height:220px;">
                <span class="text-muted">Pas d’image</span>
              </div>
            @endif
            <div class="card-body d-flex flex-column">
              <h5 class="fw-bold">{{ $p->title }}</h5>
              <p class="text-muted small mb-2">{{ $p->city }} — 
                 {{ $p->price ? number_format($p->price).' FCFA' : 'Prix sur demande' }}</p>
              <a href="{{ route('properties.show',$p) }}" class="btn btn-warning mt-auto">
                Voir le bien
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="alert alert-warning">Aucun bien disponible pour l’instant.</div>
        </div>
      @endforelse
    </div>
  @endif

</div>
@endsection
