@extends('adminlte::page')

@section('title', 'Ambientes')

@section('content_header')
    <h1> </h1>
@stop


@section('content')

<div class="container-fluid">
    <div class="card shadow">

        <div class="card-header text-center" style="background-color: #1b9ce1ff;">
            <h3 class="card-title" style="color: white;">Gestión de Ambientes</h3>
        </div>

        <div class="card-body">
            @livewire('ambientes')
        </div>

    </div>
</div>

@stop
