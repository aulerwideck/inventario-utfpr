@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <h3 class="mb-0">Relatórios - Inventário - {{$inventory->year}}</h3>
                            </div>
                            <div class="col-5 text-right">
                                {{--                                <a href="{{ route('inventory.show', ['inventory' => $inventory]) }}"--}}
                                <a href="{{ url('/home') }}"
                                   class="btn btn-lg btn-primary">Voltar</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="col">
                            <div class="row">
                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">
                                                        Encontrados</h5>
                                                    <span class="h2 font-weight-bold mb-0">
                                                    {{$inventory->collects()->count()}}
                                                </span>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success mr-2"><i class="fa fa-percent"></i>
                                                {{number_format (($inventory->collects()->count()/$inventory->patrimonies()->count())*100,2)}}%
                                            </span>
                                            </p>
                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ route('inventory.relatory.final', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Exibir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">
                                                        Duplicados</h5>
                                                    <span class="h2 font-weight-bold mb-0">
                                                    {{$inventory->collects()->groupBy('patrimony_id')->selectRaw('count(*) as count, patrimony_id')->get()->where('count','>',1)->where('patrimony_id', '!=', null)->count()}}
                                                </span>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success mr-2"><i class="fa fa-percent"></i>
                                                {{ $inventory->collects()->count() > 0 ?number_format (($inventory->collects()->groupBy('patrimony_id')->selectRaw('count(*) as count, patrimony_id')->get()->where('count','>',1)->where('patrimony_id', '!=', null)->count()/$inventory->patrimonies()->count())*100,2):"0.00"}}%
                                            </span>
                                            </p>
                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ route('inventory.relatory.duplicado', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Exibir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">Faltantes</h5>
                                                    <span class="h2 font-weight-bold mb-0">
                                                    {{ $inventory->patrimonies()->where('collected','=', 0)->get()->count() }}
                                                </span>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success mr-2"><i class="fa fa-percent"></i>
                                                {{number_format (($inventory->patrimonies()->where('collected','=', 0)->get()->count()/$inventory->patrimonies()->count())*100,2)}}%
                                            </span>
                                            </p>
                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ route('inventory.relatory.perdido', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Exibir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">
                                                        Observações</h5>
                                                    <span class="h2 font-weight-bold mb-0">
                                                    {{ $inventory->collects()->where('observation', '!=', null)->where('collects.observation', '!=', ' - Item PROEP')->where('collects.observation', '!=', ' - Item Sem Patrimônio')->count() }}
                                                </span>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success mr-2"><i class="fa fa-percent"></i>
                                                {{number_format (($inventory->collects()->where('observation', '!=', null)->where('collects.observation', '!=', ' - Item PROEP')->where('collects.observation', '!=', ' - Item Sem Patrimônio')->count()/$inventory->collects()->count())*100,2)}}%
                                            </span>
                                            </p>
                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ route('inventory.relatory.observacao', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Exibir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">Alteração de
                                                        estado</h5>
                                                    <span class="h2 font-weight-bold mb-0">
                                                    {{ $inventory->collects()->where('state_id', '!=', 1)->where('patrimony_id', '!=', null)->count() }}
                                                </span>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success mr-2"><i class="fa fa-percent"></i>
                                                {{number_format (($inventory->collects()->where('state_id', '!=', 1)->where('patrimony_id', '!=', null)->count()/$inventory->collects()->count())*100,2)}}%
                                            </span>
                                            </p>
                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ route('inventory.relatory.avariado', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Exibir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">Alteração de
                                                        local</h5>
                                                    <span class="h2 font-weight-bold mb-0">
                                                        {{$inventory->collects()->join('patrimonies', function ($join) { $join->on('patrimonies.id', '=', 'collects.patrimony_id')->on('patrimonies.local_id', '!=', 'collects.local_id'); })->count()}}
	                                            </span>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success mr-2"><i class="fa fa-percent"></i>
                                                {{number_format (($inventory->collects()->join('patrimonies', function ($join) { $join->on('patrimonies.id', '=', 'collects.patrimony_id')->on('patrimonies.local_id', '!=', 'collects.local_id'); })->count()/$inventory->collects()->count())*100,2)}}%
                                            </span>
                                            </p>
                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ route('inventory.relatory.localizacao', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Exibir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">Itens PROEP</h5>
                                                    <span class="h2 font-weight-bold mb-0">
                                                        {{$inventory->collects()->whereNotNull ('tombo_proep')->count()}}
	                                            </span>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success mr-2"><i class="fa fa-percent"></i>
                                                {{number_format (($inventory->collects()->whereNotNull ('tombo_proep')->count()/$inventory->collects()->count())*100,2)}}%
                                            </span>
                                            </p>
                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ route('inventory.relatory.proep', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Exibir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">Itens Sem Patrimônio</h5>
                                                    <span class="h2 font-weight-bold mb-0">
                                                        {{$inventory->collects()->whereNull ('collects.patrimony_id')->whereNull ('collects.tombo_proep')->whereNull ('collects.tombo_old')->whereNull ('collects.tombo')->count()}}
	                                            </span>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success mr-2"><i class="fa fa-percent"></i>
                                                {{number_format (($inventory->collects()->whereNull ('collects.patrimony_id')->whereNull ('collects.tombo_proep')->whereNull ('collects.tombo_old')->whereNull ('collects.tombo')->count()/$inventory->collects()->count())*100,2)}}%
                                            </span>
                                            </p>
                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ route('inventory.relatory.semPatrimonio', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Exibir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-7">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">Relatório
                                                        Personalizado</h5>
                                                </div>
                                                <div class="col-5 text-right">
                                                    <a href="{{ route('inventory.relatory.geral', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Gerar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection


