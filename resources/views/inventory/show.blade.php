@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">

                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <h3 class="mb-0">Inventário - {{$inventory->year}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
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
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-stats mb-4 mb-xl-0 shadow">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-0">Itens
                                                    encontrados</h5>
                                            </div>
                                        </div>
                                        <div class="progress-bar progress-bar-success" role="progressbar"
                                             style="background-color: #5cb85c; width:{{number_format (( $inventory->collects()->count()/$inventory->patrimonies()->count() <= 1 ? $inventory->collects()->count()/$inventory->patrimonies()->count():1)*100,2)}}%">
                                            <span
                                                style="@if(($inventory->collects()->count()/$inventory->patrimonies()->count())*100 < 30)color:black; @else color:white; @endif">{{number_format (( $inventory->collects()->count()/$inventory->patrimonies()->count() <= 1 ?$inventory->collects()->count()/$inventory->patrimonies()->count():1)*100,2)}}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"><br></div>
                        <div class="row">
                            <div class="col">
                                <div class="card shadow">
                                    <div class="card-header border-0">
                                        <div class="row align-items-center">
                                            <div class="col-7">
                                                <h3 class="mb-0">Locais</h3>
                                            </div>
                                            <div class="col-5 text-right">
                                                @can('create locals')
                                                    <a href="{{ route('local.create', ['inventory' => $inventory]) }}"
                                                       class="btn btn-sm btn-primary">Adicionar Local</a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Local</th>
                                                <th class="d-none d-md-table-cell" scope="col">Itens</th>
                                                <th class="d-none d-md-table-cell" scope="col">Lidos</th>
                                                <th class="d-none d-sm-table-cell" scope="col">%</th>
                                                <th scope="col" style="width:200px;"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($inventory->locals()->get() as $local)
                                                <tr>
                                                    <td>{{$local->value}}</td>
                                                    <td class="d-none d-md-table-cell">{{$local->patrimonies->count()}}</td>
                                                    <td class="d-none d-md-table-cell">{{$local->collects()->count()}}</td>
                                                    @if($local->patrimonies->count()>0)
                                                        <td class="d-none d-sm-table-cell"> {{number_format(($local->patrimonies->where('collected','=',1)->count()/$local->patrimonies->count())*100,2)}}
                                                            %
                                                        </td>
                                                    @else
                                                        <td class="d-none d-sm-table-cell"> 0%</td>
                                                    @endif
                                                    <td>
                                                        @can('read locals')
                                                            @can('collect '.$local->value.' - '.$inventory->year)
                                                                <a href="{{ route('collect.home', ['local' => $local ]) }}"
                                                                   class="btn btn-primary">Coletar</a>
                                                            @endcan
                                                        @endcan
                                                        <a href="{{ route('local.show', ['local' => $local ]) }}"
                                                           class="btn btn-secondary">Ver local</a></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        @can('archive collect')
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-stats mb-4 shadow">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-7">
                                                    <h5 class="card-title text-uppercase text-muted mb-0">Coleta via
                                                        arquivo</h5>
                                                </div>
                                                <div class="col-5 text-right">
                                                    <a href="{{ route('collect.archive', ['inventory' => $inventory]) }}"
                                                       class="btn btn-lg btn-primary">Coleta</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
@endsection
