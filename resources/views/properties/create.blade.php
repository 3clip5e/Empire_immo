@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Ajouter un bien</h2>
    <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" id="propertyForm">
        @csrf

        {{-- Titre --}}
        <div class="mb-3">
            <input name="title" class="form-control" placeholder="Titre" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <textarea name="description" class="form-control" placeholder="Description"></textarea>
        </div>

        {{-- Type de bien --}}
        <div class="mb-3">
            <label class="form-label">Type de bien</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">-- Choisir --</option>
                <option value="studio">Studio</option>
                <option value="chambre">Chambre étudiant</option>
                <option value="appartement">Appartement</option>
                <option value="villa">Villa / Maison</option>
            </select>
        </div>

        {{-- Champs dynamiques selon type --}}
        <div id="fields"></div>

        {{-- Localisation --}}
        <div class="mb-3 position-relative">
            <input id="address" name="address" class="form-control" placeholder="Adresse" autocomplete="off" required>
            <ul id="suggestions" class="list-group position-absolute w-100" style="z-index:1000;"></ul>
        </div>

        <div id="map" style="height:300px;" class="mb-3"></div>

        <div class="mb-3 row">
            <div class="col"><input id="lat" name="lat" class="form-control" placeholder="Latitude" readonly></div>
            <div class="col"><input id="lng" name="lng" class="form-control" placeholder="Longitude" readonly></div>
        </div>

        <div class="mb-3 row">
            <div class="col"><input id="city" name="city" class="form-control" placeholder="Ville" readonly></div>
            <div class="col"><input id="country" name="country" class="form-control" placeholder="Pays" readonly></div>
        </div>

        {{-- Prix --}}
        <div class="mb-3">
            <input name="price" class="form-control" placeholder="Prix (FCFA)">
        </div>

        {{-- Images --}}
        <div class="mb-3">
            <input type="file" name="images[]" multiple class="form-control">
        </div>

        <button class="btn btn-warning">Créer</button>
    </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
#suggestions { max-height:200px; overflow-y:auto; cursor:pointer; }
</style>

<script>
const typeSelect = document.getElementById('type');
const fields = document.getElementById('fields');

typeSelect.addEventListener('change', function() {
    const t = this.value;
    fields.innerHTML = '';

    if(t === 'studio') {
        fields.innerHTML = `
          <div class="mb-3"><input name="pieces" class="form-control" placeholder="Nombre de pièces"></div>
          <div class="mb-3"><input name="surface" class="form-control" placeholder="Superficie (m²)"></div>
          <div class="form-check"><input type="checkbox" name="kitchen" class="form-check-input" id="kitchen"><label class="form-check-label" for="kitchen">Cuisine interne</label></div>
          <div class="form-check"><input type="checkbox" name="furnished" class="form-check-input" id="furnished"><label class="form-check-label" for="furnished">Meublé</label></div>
        `;
    }
    if(t === 'chambre') {
        fields.innerHTML = `
          <div class="mb-3"><input name="surface" class="form-control" placeholder="Superficie (m²)"></div>
          <div class="form-check"><input type="checkbox" name="internal_bathroom" class="form-check-input" id="internal_bathroom"><label class="form-check-label" for="internal_bathroom">Douche interne</label></div>
          <div class="form-check"><input type="checkbox" name="kitchen" class="form-check-input" id="kitchen"><label class="form-check-label" for="kitchen">Espace cuisine</label></div>
          <div class="form-check"><input type="checkbox" name="furnished" class="form-check-input" id="furnished"><label class="form-check-label" for="furnished">Meublé</label></div>
          <div class="form-check"><input type="checkbox" name="internet" class="form-check-input" id="internet"><label class="form-check-label" for="internet">Accès internet</label></div>
          <div class="form-check"><input type="checkbox" name="water_included" class="form-check-input" id="water"><label class="form-check-label" for="water">Eau incluse</label></div>
          <div class="form-check"><input type="checkbox" name="electricity_included" class="form-check-input" id="electricity"><label class="form-check-label" for="electricity">Électricité incluse</label></div>
        `;
    }
    if(t === 'appartement') {
        fields.innerHTML = `
          <div class="mb-3"><input name="rooms" class="form-control" placeholder="Nombre de chambres"></div>
          <div class="mb-3"><input name="bathrooms" class="form-control" placeholder="Nombre de salles de bain"></div>
          <div class="mb-3"><input name="surface" class="form-control" placeholder="Superficie (m²)"></div>
          <div class="mb-3"><input name="floor" class="form-control" placeholder="Étage"></div>
          <div class="form-check"><input type="checkbox" name="balcony" class="form-check-input" id="balcony"><label class="form-check-label" for="balcony">Balcon/Terrasse</label></div>
          <div class="form-check"><input type="checkbox" name="parking" class="form-check-input" id="parking"><label class="form-check-label" for="parking">Parking</label></div>
          <div class="form-check"><input type="checkbox" name="furnished" class="form-check-input" id="furnished"><label class="form-check-label" for="furnished">Meublé</label></div>
          <div class="form-check"><input type="checkbox" name="security" class="form-check-input" id="security"><label class="form-check-label" for="security">Sécurité</label></div>
        `;
    }
    if(t === 'villa') {
        fields.innerHTML = `
          <div class="mb-3"><input name="rooms" class="form-control" placeholder="Nombre de chambres"></div>
          <div class="mb-3"><input name="bathrooms" class="form-control" placeholder="Nombre de salles de bain"></div>
          <div class="mb-3"><input name="surface" class="form-control" placeholder="Superficie habitable (m²)"></div>
          <div class="mb-3"><input name="land_surface" class="form-control" placeholder="Superficie du terrain (m²)"></div>
          <div class="form-check"><input type="checkbox" name="parking" class="form-check-input" id="parking"><label class="form-check-label" for="parking">Garage / Parking</label></div>
          <div class="form-check"><input type="checkbox" name="pool" class="form-check-input" id="pool"><label class="form-check-label" for="pool">Piscine</label></div>
          <div class="form-check"><input type="checkbox" name="dependency" class="form-check-input" id="dependency"><label class="form-check-label" for="dependency">Dépendance</label></div>
          <div class="form-check"><input type="checkbox" name="furnished" class="form-check-input" id="furnished"><label class="form-check-label" for="furnished">Meublé</label></div>
          <div class="form-check"><input type="checkbox" name="security" class="form-check-input" id="security"><label class="form-check-label" for="security">Sécurité (clôture, gardien...)</label></div>
        `;
    }
});

// ====================
// Localisation Leaflet
// ====================
const addressInput = document.getElementById('address');
const suggestions = document.getElementById('suggestions');
const latInput = document.getElementById('lat');
const lngInput = document.getElementById('lng');
const cityInput = document.getElementById('city');
const countryInput = document.getElementById('country');

let map = L.map('map').setView([3.848, 11.5021], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let marker;
function placeMarker(lat,lng){
    if(marker) marker.setLatLng([lat,lng]);
    else marker = L.marker([lat,lng],{draggable:true}).addTo(map);

    latInput.value = lat;
    lngInput.value = lng;

    marker.on('dragend', async e=>{
        const pos = e.target.getLatLng();
        latInput.value = pos.lat;
        lngInput.value = pos.lng;
        try{
            const res = await fetch(`{{ route('geo.reverse') }}?lat=${pos.lat}&lng=${pos.lng}`);
            const data = await res.json();
            if(data.display_name){
                addressInput.value = data.display_name;
                cityInput.value = data.address.city || data.address.town || data.address.village || data.address.suburb || data.address.hamlet || '';
                countryInput.value = data.address.country || '';
            }
        }catch(err){ console.error(err); }
    });
}

// Charger marker existant
if(latInput.value && lngInput.value){
    placeMarker(latInput.value,lngInput.value);
    map.setView([latInput.value,lngInput.value],15);
}

// Autocomplete avec debounce
let timeout;
addressInput.addEventListener('input', function(){
    clearTimeout(timeout);
    const q = this.value.trim();
    if(!q){ suggestions.innerHTML=''; return; }
    timeout = setTimeout(async ()=>{
        try{
            const res = await fetch("{{ route('geo.search') }}?q=" + encodeURIComponent(q));
            if(!res.ok) throw new Error('Erreur serveur');
            const data = await res.json();
            suggestions.innerHTML = '';
            data.forEach(d=>{
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action';
                li.textContent = d.display_name;
                li.addEventListener('click', ()=>{
                    addressInput.value = d.display_name;
                    latInput.value = d.lat;
                    lngInput.value = d.lon;
                    cityInput.value = d.address.city || d.address.town || d.address.village || d.address.suburb || d.address.hamlet || '';
                    countryInput.value = d.address.country || '';
                    suggestions.innerHTML = '';
                    map.setView([d.lat,d.lon],15);
                    placeMarker(d.lat,d.lon);
                });
                suggestions.appendChild(li);
            });
        }catch(err){
            console.error(err);
            suggestions.innerHTML = '<li class="list-group-item text-danger">Erreur serveur</li>';
        }
    },300);
});

// Fermer suggestions si clic à l'extérieur
document.addEventListener('click', e=>{ if(!addressInput.contains(e.target)) suggestions.innerHTML = ''; });
</script>
@endsection
