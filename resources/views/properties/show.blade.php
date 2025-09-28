@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="row">
    <div class="col-md-8">
      <h2>{{ $property->title }}</h2>
      <p>{{ $property->description }}</p>

      <div id="viewer" style="height:500px; background:#000"></div>

      <div class="mt-3 d-flex gap-2">
        @auth
          <a href="https://meet.jit.si/empire-immo-{{ $property->id }}-{{ Illuminate\Support\Str::random(6) }}" target="_blank" class="btn btn-outline-success">Appel vidéo</a>
        @endauth
      </div>

      <div class="mt-3">
        <h5>Galerie</h5>
        <div class="d-flex gap-2 flex-wrap">
          @foreach($property->images as $img)
            <img src="{{ asset('storage/'.$img->path) }}" style="width:120px;cursor:pointer;" onclick="showImage('{{ asset('storage/'.$img->path) }}')">
          @endforeach
        </div>
      </div>

      <hr>
      <h5>Réserver</h5>
      @auth
        <form method="POST" action="{{ route('properties.reserve', $property) }}">
          @csrf
          <div class="row g-2">
            <div class="col"><input type="date" name="start_date" class="form-control" required></div>
            <div class="col"><input type="date" name="end_date" class="form-control" required></div>
            <div class="col-auto"><button class="btn btn-warning">Demander</button></div>
          </div>
        </form>
      @else
        <p><a href="{{ route('login') }}">Connectez-vous</a> pour réserver.</p>
      @endauth

      <hr>
      <h5>Avis</h5>
      <p>Note moyenne : {{ $property->averageRating() }}</p>
      @auth
        <form method="POST" action="{{ route('properties.reviews', $property) }}">
          @csrf
          <div class="mb-2">
            <select name="rating" class="form-select" required>
              <option value="">Note</option>
              <option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option><option value="1">1</option>
            </select>
          </div>
          <div class="mb-2"><textarea name="comment" class="form-control" placeholder="Commentaire"></textarea></div>
          <button class="btn btn-sm btn-primary">Envoyer avis</button>
        </form>
      @endauth

      <hr>
      <h5>Signaler</h5>
      @auth
        <form method="POST" action="{{ route('properties.report', $property) }}">
          @csrf
          <div class="mb-2"><input name="reason" class="form-control" placeholder="Raison" required></div>
          <div class="mb-2"><textarea name="details" class="form-control" placeholder="Détails"></textarea></div>
          <button class="btn btn-sm btn-danger">Signaler</button>
        </form>
      @endauth

    </div>

    <div class="col-md-4">
      <h5>Propriétaire</h5>
      <p><strong>{{ $property->owner->name }}</strong></p>
      <p>{{ $property->city }}</p>
      <p><strong>{{ $property->price ? number_format($property->price) . ' FCFA' : '' }}</strong></p>

      <hr>
      <h5>Recommandations</h5>
      @php
        $recs = \App\Models\Property::where('city', $property->city)
            ->where('id','<>',$property->id)
            ->withCount(['reservations as confirmed_count' => function($q){ $q->where('status','confirmed'); }])
            ->orderByDesc('confirmed_count')->limit(6)->get();
      @endphp
      @foreach($recs as $r)
        <div class="mb-2">
          <a href="{{ route('properties.show',$r) }}">{{ $r->title }}</a><br>
          <small>{{ $r->price ? number_format($r->price).' FCFA' : '' }}</small>
        </div>
      @endforeach
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/photo-sphere-viewer@4/dist/photo-sphere-viewer.min.css">
<script src="https://unpkg.com/three@0.140.0/build/three.min.js"></script>
<script src="https://unpkg.com/photo-sphere-viewer@4/dist/photo-sphere-viewer.min.js"></script>

<script>
  let viewer;
  const images = [
    @foreach($property->images as $img)
      "{{ asset('storage/'.$img->path) }}",
    @endforeach
  ];

  function initViewer(src){
    if(viewer) viewer.destroy();
    viewer = new PhotoSphereViewer.Viewer({
      container: document.getElementById('viewer'),
      panorama: src || images[0] || '',
      defaultYaw: '0rad',
      navbar: ['zoom','fullscreen']
    });
  }

  function showImage(url){ initViewer(url); }

  document.addEventListener('DOMContentLoaded', function(){ if(images.length) initViewer(images[0]); });
</script>
@endsection
