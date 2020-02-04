@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Novo Local</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{route ('local.store')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="value">Nome</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input id="value"
                                           name="value"
                                           type="text"
                                           class="form-control @error('value') is-invalid @enderror"
                                           required>
                                </div>
                                @error('value')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <input id="inventory_id"
                                   name="inventory_id"
                                   type="number"
                                   value="{{$inventory->id}}"
                                   required>
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