@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Vérification en deux étapes</h5>

          @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

          <form method="POST" action="{{ route('2fa.verify.post') }}">
            @csrf
            <div class="mb-3">
              <label>Code (6 chiffres)</label>
              <input type="text" name="code" class="form-control" required />
            </div>
            <button class="btn btn-primary">Vérifier</button>
          </form>

          <form method="POST" action="{{ route('2fa.resend') }}" class="mt-2">
            @csrf
            <button class="btn btn-link">Renvoyer le code</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
