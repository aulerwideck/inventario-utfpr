@extends('layouts.app', ['activePage' => 'permission-management', 'titlePage' => __('Permission Management')])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-6 text-left">
                                <h4 class="card-title ">{{ __('Permissions') }}</h4>
                                <p class="card-category"> {{ __('Here you can manage permissions') }}</p>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('permission.create') }}"
                                   class="btn btn-outline-success">{{ __('Add Permission') }}</a>
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
                            {{ $permissions->links() }}
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
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>
                                            {{ $permission->name }}
                                        </td>
                                        <td>
                                            {{ $permission->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="td-actions text-right">
                                            <form action="{{ route('permission.destroy', $permission) }}" method="post">
                                                @csrf
                                                @method('delete')

                                                <a rel="tooltip" class="btn btn-success btn-link"
                                                   href="{{ route('permission.edit', $permission) }}"
                                                   data-original-title=""
                                                   title="">
                                                    Editar
                                                    <div class="ripple-container"></div>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-link"
                                                        data-original-title="" title=""
                                                        onclick="confirm('{{ __("Are you sure you want to delete this permission?") }}') ? this.parentElement.submit() : ''">
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
