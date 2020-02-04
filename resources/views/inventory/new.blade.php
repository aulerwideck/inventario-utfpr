@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Novo Inventário</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{route ('inventory.store')}}" enctype="multipart/form-data">
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
                                           value="{{\Carbon\Carbon::now()->year}}"
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
                                              required></textarea>
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
                                              rows="4"
                                              cols="20"
                                              class="form-control @error('observation') is-invalid @enderror"></textarea>
                                </div>
                                @error('observation')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="image">Arquivo</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input id="image"
                                           name="image"
                                           type="file"
                                           class="@error('image') is-invalid @enderror"
                                           required>
                                </div>
                                @error('image')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="clearfix"></div>
                            <br/>
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-4">
                                    <input type="submit" class="btn btn-success btn-block" value="Salvar"><br>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <input type="reset" class="btn btn-primary btn-block" value="Reiniciar"><br>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <a href="{{ route('home') }}" class="btn btn-danger btn-block">Cancelar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection