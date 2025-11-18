@extends('adminlte::auth.login') <!-- Usamos layout de AdminLTE para auth -->

@section('title', 'Verificar Email')

@section('auth_header', 'Verifica tu correo electrónico')

@section('auth_body')
@if (session('resent'))
    <div class="alert alert-success" role="alert">
        Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
    </div>
@endif

<p>
    Antes de continuar, revisa tu correo para el enlace de verificación.<br>
    Si no recibiste el correo,
</p>

<form method="POST" action="{{ route('verification.resend') }}">
    @csrf
    <button type="submit" class="btn btn-primary btn-block">clic aquí para solicitar otro</button>
</form>
@endsection

@section('auth_footer')
<p class="my-2">
    <a href="{{ route('login') }}">Volver al inicio de sesión</a>
</p>
@endsection
