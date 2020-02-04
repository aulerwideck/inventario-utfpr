@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Editar Inventário - {{$inventory->year}}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{route ('inventory.update',['inventory' => $inventory])}}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="year">Ano</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input id="year"
                                           name="year"
                                           type="number"
                                           class="form-control @error('year') is-invalid @enderror"
                                           value="{{$inventory->year}}"
                                           required>
                                </div>
                                @error('year')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="description">Descrição</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <textarea id="description"
                                              name="description"
                                              rows="4"
                                              cols="20"
                                              class="form-control @error('description') is-invalid @enderror"
                                              required>{{$inventory->description}}</textarea>
                                </div>
                                @error('description')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="final_prevision">Data de Finalização</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input id="final_prevision"
                                           name="final_prevision"
                                           type="date"
                                           class="form-control @error('final_prevision') is-invalid @enderror"
                                           value="{{$inventory->final_prevision}}"
                                           required>
                                </div>
                                @error('final_prevision')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="observation">Observação</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <textarea id="observation"
                                              name="observation"
                                              rows="7"
                                              cols="20"
                                              class="form-control @error('observation') is-invalid @enderror">{{$inventory->observation}}</textarea>
                                </div>
                                @error('observation')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="finished">Finalizado</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input type="checkbox" id="finished" name="finished"
                                           @if($inventory->finished) checked @endif>
                                </div>
                                @error('observation')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="clearfix"></div>
                            <br/>
                            <div class="row justify-content-center">
                                @can('update inventories')
                                    <div class="col-12 col-sm-4">
                                        <input type="submit" class="btn btn-success btn-block" value="Salvar"><br>
                                    </div>
                                @else
                                    <div class="col-12 col-sm-4">
                                        <input type="submit" class="btn btn-success btn-block disabled" value="Salvar"><br>
                                    </div>
                                @endcan
                                <div class="col-12 col-sm-4">
                                    <a href="{{ route('home') }}" class="btn btn-primary btn-block">Voltar</a><br>
                                </div>
                                <div class="col-12 col-sm-4">
                                    @can('delete inventories')
                                        <a href="{{ route('inventory.destroy',['inventory' => $inventory]) }}"
                                           class="btn btn-danger btn-block" onclick="return confirm('Deseja remover este inventário?');" >Excluir</a>
                                    @else
                                        <a href="#"
                                           class="btn btn-danger btn-block disabled">Excluir</a>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
