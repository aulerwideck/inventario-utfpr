@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="{{ route('inventory.show', ['inventory' => $local->inventory()->first()]) }}"
                                   class="btn btn-primary btn-block">Voltar</a>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <button class="btn btn-secondary btn-block" onclick="openFormAntigo()">Tombo Antigo
                                </button>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <button class="btn btn-secondary btn-block" onclick="openFormProep()">Tombo PROEP
                                </button>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <button class="btn btn-secondary btn-block" onclick="openFormSemPatrimonio()">Item sem
                                    patrimônio
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header">Coleta - {{$local->value}}</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" id="collectform" action="#"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4"><label for="search">SIORG</label></div>
                                <div class="col-12 col-md-8">
                                    <input id="search" name="search" type="text"
                                           class="form-control @error('search') is-invalid @enderror" required
                                           autofocus>
                                </div>
                                @error('search')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="d-block d-sm-none">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-sm-4">
                                        <input type="submit" class="btn btn-success btn-block" value="Buscar"><br>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-12 col-md-8">
                            <input id="local_id" name="local_id" type="number" value="{{$local->id}}"
                                   style="display: none">
                        </div>
                    </div>
                </div>
                <br>
                <div class="card" id="collect-card">
                    <div class="card-header">Item - <span id="collect-tombo"></span></div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" id="updateform" action="{{ route ('collect.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input id="id"
                                   name="id"
                                   type="number"
                                   style="display: none">

                            <div class="row justify-content-center">
                                <div class="col-12 col-md-4">
                                    <label for="tombo">Tombo</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <input id="tombo" name="tombo" type="text" class="form-control @error('tombo') is-invalid @enderror" disabled>
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
                                    <input id="tombo_old" name="tombo_old" type="text" class="form-control @error('tombo_old') is-invalid @enderror">
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
                                    <textarea id="description" name="description" rows="4" cols="20" class="form-control @error('description') is-invalid @enderror"></textarea>
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
                                    <textarea id="observation" name="observation" rows="4" cols="20" class="form-control @error('observation') is-invalid @enderror"></textarea>
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
                                    <select disabled name="responsible" id="responsible" class="form-control selectpicker" data-live-search="true" data-size="5">
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
                                    <label for="state">Estado</label>
                                </div>
                                <div class="col-12 col-md-8">
                                    <select name="state" id="state" class="form-control selectpicker" data-live-search="true" data-size="5">
                                        <option value="0"></option>
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
    <div class="col-12 form-popup" id="tombo_antigo_pop_up"><br>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6 card">
                <div class="card-body">
                    <form method="POST" id="tombo_antigo_form" action="{{route ('inventory.store')}}"
                          enctype="multipart/form-data">
                        @csrf
                        <input id="tombo_antigo_local_id" name="tombo_antigo_local_id" type="number" value="{{$local->id}}"
                               style="display: none">
                        <div class="row justify-content-center">
                            <div style="display: none">
                                <div class="col-6 col-md-4">
                                    <label for="tombo_antigo_select">Tombo antigo?</label>
                                </div>
                                <div class="col-6 col-md-8">
                                    <input type="checkbox" id="tombo_antigo_select" name="tombo_antigo_select" checked>
                                </div>
                            </div>
                            @error('search')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-4">
                                <label for="tombo_antigo_search">Tombo Antigo</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input id="tombo_antigo_search" name="tombo_antigo_search" type="text"
                                       class="form-control @error('search') is-invalid @enderror" required autofocus>
                            </div>
                            @error('search')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-block d-sm-none">
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-4">
                                    <input type="submit" class="btn btn-success btn-block" value="Buscar"><br>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br/>
                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-4">
                            <input class="btn btn-danger btn-block" onclick="closeFormAntigo()" value="Cancelar"><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 form-popup" id="proep_pop_up"><br>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6 card">
                <div class="card-body">
                    <form method="POST" id="form_proep" action="{{ route ('collect.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <input id="local_id_proep" name="local_id_proep" type="number" value="{{$local->id}}"
                               style="display: none">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-4">
                                <label for="tombo_proep">PROEP</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input id="tombo_proep" name="tombo_proep" type="text" class="form-control @error('tombo_proep') is-invalid @enderror">
                            </div>
                            @error('tombo_proep')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-4">
                                <label for="description_proep">Descrição</label>
                            </div>
                            <div class="col-12 col-md-8">
                                    <textarea id="description_proep" name="description_proep" rows="4" cols="20" class="form-control @error('description_proep') is-invalid @enderror" required></textarea>
                            </div>
                            @error('description_proep')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-4">
                                <label for="state_proep">Estado</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <select name="state_proep" id="state_proep" class="form-control selectpicker" data-live-search="true" data-size="5" required>
                                    <option value="0"></option>
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
                                <label for="observation_proep">Observação</label>
                            </div>
                            <div class="col-12 col-md-8">
                                    <textarea id="observation_proep" name="observation_proep" rows="4" cols="20" class="form-control @error('observation_proep') is-invalid @enderror"></textarea>
                            </div>
                            @error('observation_proep')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-12 col-sm-4">
                                <input type="submit" class="btn btn-success btn-block" value="Salvar"><br>
                            </div>
                            <div class="col-12 col-sm-4">
                                <input class="btn btn-danger btn-block" onclick="closeFormProep()"
                                       value="Cancelar"><br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 form-popup" id="sem_patrimonio_pop_up"><br>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6 card">
                <div class="card-body">
                    <form method="POST" id="form_sem_pat" action="{{ route ('collect.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <input id="local_id_sem_pat" name="local_id_sem_pat" type="number" value="{{$local->id}}"
                               style="display: none">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-4">
                                <label for="description_sem_pat">Descrição</label>
                            </div>
                            <div class="col-12 col-md-8">
                                    <textarea id="description_sem_pat" name="description_sem_pat" rows="4" cols="20" class="form-control @error('description_sem_pat') is-invalid @enderror" required></textarea>
                            </div>
                            @error('description_sem_pat')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-4">
                                <label for="state_sem_pat">Estado</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <select name="state_sem_pat" id="state_sem_pat" class="form-control selectpicker" data-live-search="true" data-size="5" required>
                                    <option value="0"></option>
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
                                <label for="observation_sem_pat">Observação</label>
                            </div>
                            <div class="col-12 col-md-8">
                                    <textarea id="observation_sem_pat" name="observation_sem_pat" rows="4" cols="20" class="form-control @error('observation_sem_pat') is-invalid @enderror"></textarea>
                            </div>
                            @error('observation_sem_pat')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-12 col-sm-4">
                                <input type="submit" class="btn btn-success btn-block" value="Salvar"><br>
                            </div>
                            <div class="col-12 col-sm-4">
                                <input class="btn btn-danger btn-block" onclick="closeFormSemPatrimonio()"
                                       value="Cancelar"><br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript">

        $(document).ready(function () {
            closeFormAntigo();
            closeFormSemPatrimonio();
            $('#search').focus();
            document.getElementById('search');

            $("#collectform").bind('submit', function (e) {
                e.preventDefault();
                jQuery.ajax({
                    'processing': true,
                    'serverSide': false,
                    type: "GET",
                    data: {
                        tombo: $("#search").val(),
                        select: $("#select").prop('checked'),
                        local_id: $("#local_id").val()
                    },
                    url: "/collect/ajax",
                    success: function (response) {
                        var patrimony = JSON.parse(response)[0];

                        $('#collect-tombo').text(patrimony.tombo);
                        $('#id').val(patrimony.collect_id);
                        $('#tombo').val(patrimony.tombo);
                        $('#tombo_old').val(patrimony.tombo_old);
                        $('#description').val(patrimony.description);
                        $('#observation').val(patrimony.observation);
                        $("#responsible").val(patrimony.responsible['id']);
                        $("#state").val(patrimony.state['id']);

                        if (patrimony.collectedhere == true) {
                            // var r = $.prompt('Esse item já foi registrado nesse local.\\nDeseja salvar novamente?',{ buttons: { Ok: true, Cancel: false }, focus: 1 });
                            var r = confirm("Esse item já foi registrado nesse local.\nDeseja salvar novamente?");
                            if (r == true) {
                                jQuery.ajax({
                                    'processing': true,
                                    'serverSide': false,
                                    type: "GET",
                                    data: {
                                        tombo: $("#search").val(),
                                        select: $("#select").prop('checked'),
                                        local_id: $("#local_id").val()
                                    },
                                    url: "/collect/ajaxDualCollect",
                                    success: function (response) {
                                        var patrimony = JSON.parse(response)[0];

                                        $('#collect-tombo').text(patrimony.tombo);
                                        $('#id').val(patrimony.collect_id);
                                        $('#tombo').val(patrimony.tombo);
                                        $('#tombo_old').val(patrimony.tombo_old);
                                        $('#description').val(patrimony.description);
                                        $('#observation').val(patrimony.observation);
                                        $("#responsible").val(patrimony.responsible['id']);
                                        $("#state").val(patrimony.state['id']);
                                    }
                                });
                            }
                        }
                    },
                    error: function () {
                        alert("Não foi possível encontrar o item");
                    }
                }).always(function () {
                    $('#search').val('');
                    $("#select").prop('checked', false);
                });
                return true;
            });

            $("#tombo_antigo_form").bind('submit', function (e) {
                e.preventDefault();
                jQuery.ajax({
                    'processing': true,
                    'serverSide': false,
                    type: "GET",
                    data: {
                        tombo: $("#tombo_antigo_search").val(),
                        select: $("#tombo_antigo_select").prop('checked'),
                        local_id: $("#tombo_antigo_local_id").val()
                    },
                    url: "/collect/ajax",
                    success: function (response) {
                        var patrimony = JSON.parse(response)[0];

                        $('#collect-tombo').text(patrimony.tombo);
                        $('#id').val(patrimony.collect_id);
                        $('#tombo').val(patrimony.tombo);
                        $('#tombo_old').val(patrimony.tombo_old);
                        $('#description').val(patrimony.description);
                        $('#observation').val(patrimony.observation);
                        $("#responsible").val(patrimony.responsible['id']);
                        $("#state").val(patrimony.state['id']);

                        if (patrimony.collectedhere == true) {
                            // var r = $.prompt('Esse item já foi registrado nesse local.\\nDeseja salvar novamente?',{ buttons: { Ok: true, Cancel: false }, focus: 1 });
                            var r = confirm("Esse item já foi registrado nesse local.\nDeseja salvar novamente?");
                            if (r == true) {
                                jQuery.ajax({
                                    'processing': true,
                                    'serverSide': false,
                                    type: "GET",
                                    data: {
                                        tombo: $("#tombo_antigo_search").val(),
                                        select: $("#tombo_antigo_select").prop('checked'),
                                        local_id: $("#tombo_antigo_local_id").val()
                                    },
                                    url: "/collect/ajaxDualCollect",
                                    success: function (response) {
                                        var patrimony = JSON.parse(response)[0];

                                        $('#collect-tombo').text(patrimony.tombo);
                                        $('#id').val(patrimony.collect_id);
                                        $('#tombo').val(patrimony.tombo);
                                        $('#tombo_old').val(patrimony.tombo_old);
                                        $('#description').val(patrimony.description);
                                        $('#observation').val(patrimony.observation);
                                        $("#responsible").val(patrimony.responsible['id']);
                                        $("#state").val(patrimony.state['id']);
                                    }
                                });
                            }
                        }
                    },
                    error: function () {
                        alert("Não foi possível encontrar o item");
                    }
                }).always(function () {
                    $('#tombo_antigo_search').val('');
                    closeFormAntigo();
                });
                return true;
            });
        });

        function openFormSemPatrimonio() {
            document.getElementById("sem_patrimonio_pop_up").style.display = "block";
        }

        function closeFormSemPatrimonio() {
            document.getElementById("sem_patrimonio_pop_up").style.display = "none";
        }

        function openFormAntigo() {
            document.getElementById("tombo_antigo_pop_up").style.display = "block";
        }

        function closeFormAntigo() {
            document.getElementById("tombo_antigo_pop_up").style.display = "none";
        }

        function openFormProep() {
            document.getElementById("proep_pop_up").style.display = "block";
        }

        function closeFormProep() {
            document.getElementById("proep_pop_up").style.display = "none";
        }
    </script>
@endsection
