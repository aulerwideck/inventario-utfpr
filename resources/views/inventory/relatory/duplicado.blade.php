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
                                    <th class="all">Descrição</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function format(d) {
                // `d` is the original data object for the row
                str = '<div class="slider">' +
                    '<table class="col-12">' +
                    '<thead>' +
                    '<th>Tombo Antigo</th>' +
                    '<th>Local</th>' +
                    '<th>Responsável</th>' +
                    '<th>Estado</th>' +
                    '<th>Observação</th>' +
                    '<th>Equipe</th>' +
                    '<th>Data</th>' +
                    '</thead>' +
                    '<tbody>';

                for (i in d.duplicados) {
                    console.log(d.duplicados[i]);
                    if(d.duplicados[i].tombo_old == null)
                        d.duplicados[i].tombo_old = '';
                    str += '<tr>' +
                        '<td>'+d.duplicados[i].tombo_old+'</td>' +
                        '<td>'+d.duplicados[i].local+'</td>' +
                        '<td>'+d.duplicados[i].responsible+'</td>' +
                        '<td>'+d.duplicados[i].estado+'</td>' +
                        '<td>'+d.duplicados[i].observation+'</td>' +
                        '<td>'+d.duplicados[i].equipe+'</td>' +
                        '<td>'+d.duplicados[i].data+'</td>' +
                        '</tr>';
                }

                // str += d.duplicados.forEach(listar);

                str +=
                    '</tbody>' +
                    '</table>' +
                    '</div>';
                return str;

            }

            $(document).ready(function () {
                var table = $('#relatory-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route( $route, ['inventory' => $inventory]) !!}',
                    columns: [
                        {data: 'tombo', name: 'tombo'},
                        {data: 'description', name: 'description'},
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
                $('#relatory-table tbody').on('click', 'td', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);

                    if (row.child.isShown()) {
                        // This row is already open - close it
                        $('div.slider', row.child()).slideUp(function () {
                            row.child.hide();
                            tr.removeClass('shown');
                        });
                    } else {
                        // Open this row
                        row.child(format(row.data()), 'no-padding').show();
                        tr.addClass('shown');

                        $('div.slider', row.child()).slideDown();
                    }
                });
            });
        </script>
@endsection
