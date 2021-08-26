@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <h3 class="mb-0">Coleta via Arquivo</h3>
                            </div>
                            <div class="col-5 text-right">
                                <a href="{{ route('inventory.show', ['inventory' => $inventory]) }}"
                                   class="btn btn-lg btn-primary">Voltar</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" id="searchform" action="{{route ('collect.store.archive_old', ['inventory' => $inventory])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="responsible">Responsável</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <select name="responsible" id="responsible" class="form-control selectpicker"
                                            data-live-search="true" data-size="5" required>
                                        @foreach($responsibles as $responsible)
                                            <option value="{{ $responsible->id}}">
                                                {{$responsible->value}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="local">Local</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <select name="local" id="local" class="form-control selectpicker"
                                            data-live-search="true" data-size="5" required>
                                        @foreach($locals as $local)
                                            <option value="{{ $local->id}}">
                                                {{$local->value}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="state">Estado</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <select name="state" id="state" class="form-control selectpicker"
                                            data-live-search="true" data-size="5" required>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id}}">
                                                {{$state->value}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
