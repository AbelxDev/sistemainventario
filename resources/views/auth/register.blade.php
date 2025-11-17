@extends('adminlte::auth.register') <!-- Layout de AdminLTE -->

@section('title', 'Registro')

@section('auth_header', 'Registrar nuevo usuario') <!-- Encabezado en la caja -->

@section('auth_body')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nombre -->
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                placeholder="Nombre completo" value="{{ old('name') }}" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Email -->
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="Email" value="{{ old('email') }}" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Password -->
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Contraseña" required>
            <div class="input-group-append">
                <div class="input-group-text" data-toggle="tooltip"
                    title="La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, números y un símbolo.">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            <!-- Mensaje debajo del input -->
            <small class="form-text text-muted">
                La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, números y un símbolo.
            </small>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>


        <!-- Confirmar Password -->
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar contraseña"
                required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Registrar</button>
            </div>
        </div>
    </form>
@endsection

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('login') }}" class="text-center">Ya tengo una cuenta</a>
    </p>
@endsection
