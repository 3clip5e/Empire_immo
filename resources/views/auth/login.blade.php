@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h3>Connexion</h3>
      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-3"><input name="email" type="email" class="form-control" placeholder="Email" required></div>
        <div class="mb-3"><input name="password" type="password" class="form-control" placeholder="Mot de passe" required></div>
        <div class="mb-3 form-check"><input type="checkbox" class="form-check-input" id="remember" name="remember"><label class="form-check-label" for="remember">Se souvenir</label></div>
        <button class="btn btn-warning">Se connecter</button>
      </form>
    </div>
  </div>
</div>
@endsection
