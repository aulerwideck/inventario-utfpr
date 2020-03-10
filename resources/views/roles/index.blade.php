@extends('layouts.app', ['activePage' => 'role-management', 'titlePage' => __('Role Management')])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-6 text-left">
                                <h4 class="card-title ">{{ __('Roles') }}</h4>
                                <p class="card-category"> {{ __('Here you can manage roles') }}</p>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('role.create') }}"
                                   class="btn btn-outline-success">{{ __('Add Role') }}</a>
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
                            {{ $roles->links() }}
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                <th>
                                    {{ __('Name') }}
                                </th>
                                <th>
                                    {{ __('Creation date') }}
                                </th>
                                <th class="text-right">
                                    {{ __('Actions') }}
                                </th>
                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>
                                            {{ $role->name }}
                                        </td>
                                        <td>
                                            {{ $role->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="td-actions text-right">
                                            <form action="{{ route('role.destroy', $role) }}" method="post">
                                                @csrf
                                                @method('delete')

                                                <a rel="tooltip" class="btn btn-success btn-link"
                                                   href="{{ route('role.edit', $role) }}" data-original-title=""
                                                   title="">
                                                    Editar
                                                    <div class="ripple-container"></div>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-link"
                                                        data-original-title="" title=""
                                                        onclick="confirm('{{ __("Are you sure you want to delete this role?") }}') ? this.parentElement.submit() : ''">
                                                    Excluir
                                                    <div class="ripple-container"></div>
                                                </button>
                                            </form>

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
