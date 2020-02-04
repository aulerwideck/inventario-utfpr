@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <h3 class="mb-0">Dashboard</h3>
                            </div>
                            <div class="col-5 text-right">
                                @can('create inventories')
                                    <a href="{{ route('inventory.create') }}"
                                       class="btn btn-lg btn-success">Novo Inventário</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @can('read inventories')
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if (isset($success) && $message = $success)
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                            @endif
                            <div style="display: flex; flex-wrap: nowrap;">
                                @foreach($inventarios as $inventario)
                                    <div class="card col-12 col-sm-6 col-lg-4">
                                        <div class="card-body" style="display: flex; flex-wrap: wrap;">
                                            <div class="col-10">
                                                <h4 class="card-title">{{$inventario->year}}</h4>
                                            </div>
                                            <div class="col-2">
                                                @if($inventario->finished)
                                                    <i class="fa fa-check" style="color:green"></i>
                                                @else
                                                    <i class="fa fa-times" style="color:red"></i>
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <p class="card-text">{{$inventario->description}}<br/></p>
                                            </div>
                                            <div class="col-12">
                                                <p>
                                                    @if($inventario->finished)
                                                        <a href="#"
                                                           class="btn btn-primary disabled" style="display: block;">Iniciar
                                                            Coleta</a>
                                                    @else
                                                        <a href="{{ route('inventory.show', ['inventory' => $inventario]) }}"
                                                           class="btn btn-primary" style="display: block;">Iniciar
                                                            Coleta</a>
                                                    @endif
                                                </p>
                                            </div>
                                            @can('see relatories')
                                                <div class="col-12">
                                                    <p>
                                                        <a href="{{ route('inventory.relatories', ['inventory' => $inventario]) }}"
                                                           class="btn btn btn-primary"
                                                           style="display: block;">Relatórios</a>
                                                    </p>
                                                </div>
                                            @endcan
                                            @can('update inventories')
                                                <div class="col-12">
                                                    <p>
                                                        <a href="{{ route('inventory.edit', ['inventory' => $inventario]) }}"
                                                           class="btn btn-secondary" style="display: block;">Editar</a>
                                                    </p>

                                                </div>@endcan
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
@endsection
