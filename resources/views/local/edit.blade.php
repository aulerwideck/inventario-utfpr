@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{$local->value}}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{route ('local.update', ['local' => $local])}}" enctype="multipart/form-data">
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
                                           value="{{$local->value}}"
                                           required>
                                </div>
                                @error('value')
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
                                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-block">Voltar</a>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <a href="{{ route('local.destroy',['local' => $local]) }}" class="btn btn-danger btn-block">Cancelar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

{{--                {{ dd($local->collects->first()->user->name)}}--}}
                <div class="card">
                    <div class="card-header">Itens</div>

                    <div class="card-body">
                        <table id="patrimonies-local-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <th class="all">Tombo</th>
                                <th class="none">Tombo Antigo</th>
                                <th class="all">Descrição</th>
                                <th>Local</th>
                                <th class="none">Responsável</th>
                                <th class="none">Lido</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready( function () {
            $('#patrimonies-local-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('local.listPatrimonies',['id_local' => $local->id]) !!}',
                columns: [
                    { data: 'tombo', name: 'tombo'},
                    { data: 'tombo_old', name: 'tombo_old'},
                    { data: 'description', name: 'description'},
                    { data: 'locals', name: 'locals.value'},
                    { data: 'responsibles', name: 'responsibles.value'},
                    { data: 'collect', name:'collect.all', searchable: false}
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
                    }
                },
                "lengthMenu": [[20,50,100,-1], ["20","50","100","Todos"]],
                "order": [[ 0, "asc" ]]
            });
        } );
    </script>
@endsection
