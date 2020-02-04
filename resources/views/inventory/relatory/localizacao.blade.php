@extends('layouts.app')

@section('content')
    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <h3 class="mb-0">{{ $title }}</h3>
                            </div>
                            <div class="col-5 text-right">
                                <a href="{{ url()->previous() }}" class="btn btn-lg btn-primary">Voltar</a>
                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <table id="relatory-table"
                                       class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <th class="all">Tombo</th>
                                    <th class="all">Tombo Antigo</th>
                                    <th style="width:150px; max-width: 150px;" class="all">Descrição</th>
                                    {{--                                    <th class="none">Observação</th>--}}
                                    <th class="all">Estado</th>
                                    <th class="all">Local Antigo</th>
                                    <th class="all">Local Novo</th>
                                    <th class="all">Responsável</th>
                                    {{--                                    <th class="none">Data</th>--}}
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#relatory-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route( $route, ['inventory' => $inventory]) !!}',
                    columns: [
                        // {data: 'tombo', name: 'tombo'},
                        // {data: 'tombo_old', name: 'tombo_old'},
                        // {data: 'description', name: 'description'},
                        // {data: 'estado', name: 'estado'},
                        // {data: 'local_antigo', name: 'local_antigo'},
                        // {data: 'local_novo', name: 'local_novo'},
                        // {data: 'responsible', name: 'responsible'},,
                        {data: 'patrimony.tombo', name: 'patrimony.tombo'},
                        {data: 'patrimony.tombo_old', name: 'patrimony.tombo_old'},
                        {data: 'description', name: 'description'},
                        {data: 'state_id', name: 'state_id'},
                        {data: 'local_id', name: 'local_id'},
                        {data: 'patrimony.local_id', name: 'patrimony.local_id'},
                        // {data: 'local.value', name: 'local.value'},
                        {data: 'user_id', name: 'user_id'},
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
        </script>
@endsection