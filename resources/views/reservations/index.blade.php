@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3>Mes réservations</h3>

  @foreach($reservations as $res)
    <div class="card mb-3">
      <div class="card-body d-flex justify-content-between">
        <div>
          <h5>{{ $res->property->title }}</h5>
          <p>{{ $res->start_date->format('Y-m-d') }} → {{ $res->end_date->format('Y-m-d') }}</p>
          <small>Statut : {{ $res->status }}</small>
        </div>
        <div class="text-end">
          @if(auth()->user()->role === 'bailleur')
            <form method="POST" action="{{ route('reservations.status', $res) }}">
              @csrf @method('PATCH')
              <select name="status" class="form-select d-inline-block w-auto">
                <option value="pending" @if($res->status==='pending') selected @endif>pending</option>
                <option value="confirmed" @if($res->status==='confirmed') selected @endif>confirmed</option>
                <option value="cancelled" @if($res->status==='cancelled') selected @endif>cancelled</option>
                <option value="completed" @if($res->status==='completed') selected @endif>completed</option>
              </select>
              <button class="btn btn-sm btn-primary">Mettre à jour</button>
            </form>
          @endif

          @if(auth()->id() === $res->user_id)
            <form method="POST" action="{{ route('reservations.destroy', $res) }}" class="mt-2">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">Annuler</button>
            </form>
          @endif
        </div>
      </div>
    </div>
  @endforeach

  {{ $reservations->links() }}
</div>
@endsection
