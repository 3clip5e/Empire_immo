@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3>Éditer utilisateur</h3>
  <form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf @method('PUT')
    <div class="mb-2"><input name="name" value="{{ $user->name }}" class="form-control"></div>
    <div class="mb-2"><input name="email" value="{{ $user->email }}" class="form-control"></div>
    <div class="mb-2">
      <select name="role" class="form-select">
        <option value="client" @if($user->role==='client') selected @endif>Client</option>
        <option value="bailleur" @if($user->role==='bailleur') selected @endif>Bailleur</option>
        <option value="admin" @if($user->role==='admin') selected @endif>Admin</option>
      </select>
    </div>
    <div class="mb-2"><input name="phone" value="{{ $user->phone }}" class="form-control"></div>
    <button class="btn btn-primary">Enregistrer</button>
  </form>
</div>
@endsection
