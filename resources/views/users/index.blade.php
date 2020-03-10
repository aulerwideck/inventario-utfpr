@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('User Management')])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-6 text-left">
                                <h4 class="card-title ">{{ __('Users') }}</h4>
                                <p class="card-category"> {{ __('Here you can manage users') }}</p>
                            </div>
                            <div class="col-6 text-right">
                                @can('create users')
                                    <a href="{{ route('user.create') }}"
                                       class="btn btn-outline-success">{{ __('Add user') }}</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="material-icons">close</i>
                                        </button>
                                        <span>{{ session('status') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row justify-content-end">
                            {{ $users->links() }}
                            <div class="col-6 text-right">
                                <form class="form-inline"
                                      action="/{{ Request::segment(1) }}/search"
                                      method="get">
                                    {{ csrf_field() }}
                                    <div class="col col-lg-8">
                                        <input class="form-control mr-sm-2" type="search" name="search_text"
                                               placeholder="Procurar" aria-label="Procurar">
                                    </div>
                                    <div class="col col-lg-4">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                                            Procurar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                <th>
                                    {{ __('Name') }}
                                </th>
                                <th>
                                    {{ __('Email') }}
                                </th>
                                <th>
                                    {{ __('Creation date') }}
                                </th>
                                <th class="text-right">
                                    {{ __('Actions') }}
                                </th>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            {{ $user->name }}
                                        </td>
                                        <td>
                                            {{ $user->email }}
                                        </td>
                                        <td>
                                            {{ $user->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="td-actions text-right">
                                            @if ($user->id != auth()->id())
                                                <form action="{{ route('user.destroy', $user) }}" method="post">
                                                    @can('update users')
                                                        <a rel="tooltip" class="btn btn-success btn-link"
                                                           href="{{ route('user.edit', $user) }}"
                                                           data-original-title="" title="">Editar
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                    @endcan
                                                    @can('delete users')
                                                        @csrf
                                                        @method('delete')
                                                        <button type="button" class="btn btn-danger btn-link"
                                                                data-original-title="" title=""
                                                                onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                            Excluir
                                                            <div class="ripple-container"></div>
                                                        </button>
                                                    @endcan
                                                </form>
                                            @else
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
