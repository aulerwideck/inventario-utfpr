@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">{{$local->value}}</div>
                    <div class="card-body">
                        <div class="">
                            <h4 class="card-title">Itens</h4>
                        </div>
                        <table id="patrimonies-local-table"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <th class="all">Tombo</th>
                            <th class="all">Tombo Antigo</th>
                            <th class="none">Descrição</th>
                            <th>Local</th>
                            <th class="none">Responsável</th>
                            <th class="none">Lido</th>
                            </thead>
                        </table>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <h4 class="card-title">Itens Coletados</h4>
                        </div>
                        <table id="patrimonies-local-table-collected"
                               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <th class="all">Tombo</th>
                            <th class="all">Tombo Antigo</th>
                            <th class="none">Descrição</th>
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
        $(document).ready(function () {
            $('#patrimonies-local-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('local.listPatrimonies',['id_local' => $local->id]) !!}',
                columns: [
                    {data: 'tombo', name: 'tombo'},
                    {data: 'tombo_old', name: 'tombo_old'},
                    {data: 'description', name: 'description'},
                    {data: 'locals', name: 'locals.value'},
                    {data: 'responsibles', name: 'responsibles.value'},
                    {data: 'collect', name: 'collect.all', searchable: false}
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
                "lengthMenu": [[20, 50, 100, -1], ["20", "50", "100", "Todos"]],
                "order": [[0, "asc"]]
            });
            $('#patrimonies-local-table-collected').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('local.listPatrimoniesCollecteds',['id_local' => $local->id]) !!}',
                columns: [
                    {data: 'tombo', name: 'tombo'},
                    {data: 'tombo_old', name: 'tombo_old'},
                    {data: 'description', name: 'description'},
                    {data: 'local', name: 'local'},
                    {data: 'responsible', name: 'responsible'},
                    {data: 'collect', name: 'collect.all', searchable: false}
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
                "lengthMenu": [[20, 50, 100, -1], ["20", "50", "100", "Todos"]],
                "order": [[0, "asc"]]
            });
        });
    </script>
@endsection
