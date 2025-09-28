@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <h3>Inscription</h3>
      <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="mb-3"><input name="name" class="form-control" placeholder="Nom complet" required></div>
        <div class="mb-3"><input name="email" type="email" class="form-control" placeholder="Email" required></div>
        <div class="mb-3"><input name="phone" class="form-control" placeholder="Téléphone (optionnel)"></div>
        <div class="mb-3 row">
          <div class="col"><input name="password" type="password" class="form-control" placeholder="Mot de passe" required></div>
          <div class="col"><input name="password_confirmation" type="password" class="form-control" placeholder="Confirmer mot de passe" required></div>
        </div>
        <div class="mb-3">
          <select name="role" class="form-select" required>
            <option value="client">Je suis client</option>
            <option value="bailleur">Je suis bailleur</option>
          </select>
        </div>
        <button class="btn btn-warning">S'inscrire</button>
      </form>
    </div>
  </div>
</div>
@endsection
