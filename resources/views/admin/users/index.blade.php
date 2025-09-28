@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3>Gestion des utilisateurs</h3>
  <table class="table">
    <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr></thead>
    <tbody>
      @foreach($users as $u)
      <tr>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->role }}</td>
        <td>
          <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-primary">Éditer</a>
          <form action="{{ route('admin.users.destroy', $u) }}" method="POST" style="display:inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Suppr</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $users->links() }}
</div>
@endsection
