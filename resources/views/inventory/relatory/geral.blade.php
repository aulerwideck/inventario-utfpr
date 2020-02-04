@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <h3 class="mb-0">Relatório Personalizado</h3>
                            </div>
                            <div class="col-5 text-right">
                                <a href="{{ route('inventory.relatories', ['inventory' => $inventory]) }}"
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
                        <form method="POST" id="searchform" action="{{route ('inventory.relatory.search', ['inventory' => $inventory])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="tombo">Tombo</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input id="tombo"
                                           name="tombo"
                                           type="number"
                                           class="form-control @error('tombo') is-invalid @enderror">
                                </div>
                                @error('tombo')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="tombo_old">Tombo Antigo</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input id="tombo_old"
                                           name="tombo_old"
                                           type="number"
                                           class="form-control @error('tombo_old') is-invalid @enderror">
                                </div>
                                @error('tombo_old')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="description">Descrição</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input id="description"
                                           name="description"
                                           type="text"
                                           class="form-control @error('description') is-invalid @enderror">
                                </div>
                                @error('description')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="observation">Observação</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input id="observation"
                                           name="observation"
                                           type="text"
                                           class="form-control @error('observation') is-invalid @enderror">
                                </div>
                                @error('observation')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="responsible">Responsável</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <select name="responsible" id="responsible" class="form-control selectpicker"
                                            data-live-search="true" data-size="5">
                                        <option value="0"></option>
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
                                            data-live-search="true" data-size="5">
                                        <option value="0"></option>
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
                                            data-live-search="true" data-size="5">
                                        <option value="0"></option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id}}">
                                                {{$state->value}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <br/>
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-4">
                                    <input type="submit" class="btn btn-success btn-block" value="Buscar"><br>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <table id="relatory-table"
                                       class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <th class="all">Tombo</th>
                                    <th class="none">Tombo Antigo</th>
                                    <th style="width:150px; max-width: 150px;" class="all">Descrição</th>
                                    <th class="none">Observação</th>
                                    <th class="all">Estado</th>
                                    <th class="all">Local</th>
                                    <th class="all">Responsável</th>
                                    <th class="none">Data</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var table;
        $(document).ready(function () {
            table = $('#relatory-table').DataTable({
                processing: true,
                serverSide: false,
                ajax:  {
                    url: '{!! route('inventory.relatory.search', ['inventory' => $inventory])  !!}',
                    data: {
                        tombo: $("#tombo").val(),
                        tombo_old: $("#tombo_old").val(),
                        state: $("#state").val(),
                        responsible: $("#responsible").val(),
                        local: $("#local").val(),
                        description: $("#description").val(),
                        observation: $("#observation").val(),
                    }
                },
                columns: [
                    {data: 'tombo', name: 'tombo'},
                    {data: 'tombo_old', name: 'tombo_old'},
                    {data: 'description', name: 'description'},
                    {data: 'observation', name: 'observation'},
                    {data: 'estado', name: 'estado'},
                    {data: 'local', name: 'local'},
                    {data: 'responsible', name: 'responsible'},
                    {data: 'data', name: 'data', searchable: false},
                ],
                "language": {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "Resultados por Página: _MENU_",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    },
                    buttons: {
                        pageLength: {
                            _: "Listar %d registros",
                            '-1': "Todos registros"
                        }
                    }
                },
                "lengthMenu": [[20, 50, 100, -1], ["20", "50", "100", "Todos"]],
                "order": [[0, "asc"]],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'excel', 'pdf', 'csv'
                ],

            });

        });
        $("#searchform").bind('submit', function (e) {
            e.preventDefault();
            table.destroy();
            table = $('#relatory-table').DataTable({
                processing: true,
                serverSide: false,
                ajax:  {
                    url: '{!! route('inventory.relatory.search', ['inventory' => $inventory])  !!}',
                    data: {
                        tombo: $("#tombo").val(),
                        tombo_old: $("#tombo_old").val(),
                        state: $("#state").val(),
                        responsible: $("#responsible").val(),
                        local: $("#local").val(),
                        description: $("#description").val(),
                        observation: $("#observation").val(),
                    }
                },
                columns: [
                    {data: 'tombo', name: 'tombo'},
                    {data: 'tombo_old', name: 'tombo_old'},
                    {data: 'description', name: 'description'},
                    {data: 'observation', name: 'observation'},
                    {data: 'estado', name: 'estado'},
                    {data: 'local', name: 'local'},
                    {data: 'responsible', name: 'responsible'},
                    {data: 'data', name: 'data', searchable: false},
                ],
                "language": {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "Resultados por Página: _MENU_",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    },
                    buttons: {
                        pageLength: {
                            _: "Listar %d registros",
                            '-1': "Todos registros"
                        }
                    }
                },
                "lengthMenu": [[20, 50, 100, -1], ["20", "50", "100", "Todos"]],
                "order": [[0, "asc"]],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'excel', 'pdf', 'csv'
                ],

            });

            return true;
        });
    </script>
@endsection