@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Cambiar Contraseña</h1>
    <form method="POST" action="{{ route('password.change') }}" class="card p-4">
        @csrf
        <div class="form-group mb-3">
            <label for="current_password">Contraseña actual</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">Nueva Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
            <a href="{{ route('home') }}" class="btn btn-secondary">Regresar al Panel</a>
        </div>
    </form>
</div>
@endsection
