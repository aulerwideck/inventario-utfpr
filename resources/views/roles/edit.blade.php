@extends('layouts.app', ['activePage' => 'role-management', 'titlePage' => __('Role Management')])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <form method="post" action="{{ route('role.update', $role) }}" autocomplete="off"
                      class="form-horizontal">
                    @csrf
                    @method('put')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Edit Role') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('role.index') }}"
                                       class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               name="name" id="input-name" type="text"
                                               placeholder="{{ __('Name') }}" value="{{ old('name', $role->name) }}"
                                               required="true" aria-required="true"/>
                                        @if ($errors->has('name'))
                                            <span id="name-error" class="error text-danger"
                                                  for="input-name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-10">
                <form method="post" action="{{ route('role.connect', $role) }}" autocomplete="off"
                      class="form-horizontal">
                    @csrf
                    @method('put')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Connect Permissions') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="row card-body ">
                            @foreach($permissions as $permission)
                                <div class="row col-sm-4">
                                    <div class="form-group{{ $errors->has($permission->name) ? ' has-danger' : '' }}">
                                        <input type="checkbox" id="{{$permission->id}}"
                                               name="{{$permission->id}}" {{$role->hasPermissionTo($permission->name) ? 'checked' : ''}}>
                                        <label for="{{$permission->id}}" class="col-form-label">{{ __($permission->name) }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Connect') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
