@extends('adminlte::auth.login') <!-- Usa el layout de AdminLTE -->

@section('title', 'Login')

@section('auth_header', 'Iniciar sesión') <!-- Encabezado en la caja de login -->

@section('auth_body')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email -->
    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               placeholder="Email" value="{{ old('email') }}" required autofocus>
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
               placeholder="Password" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="row mb-3">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Recordarme</label>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
        </div>
    </div>
</form>
@endsection

@section('auth_footer')
@if (Route::has('password.request'))
    <p class="my-2">
        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
    </p>
@endif
{{-- <p class="my-0">
    <a href="{{ route('register') }}" class="text-center">Registrar un nuevo usuario</a>
</p> --}}
@endsection
