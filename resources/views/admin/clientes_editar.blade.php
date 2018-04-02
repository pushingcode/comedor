@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $mensaje }}</div>
                @foreach($users as $user)
                <div class="panel-body">
                    <!--Panel-->
                    <form class="form-horizontal" method="POST" action="/clientes/{{ $user->idCliente }}">
                        {{ csrf_field() }}{{ method_field('PUT') }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                @role('superadmin')
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->userNombre }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                @endrole
                                
                                @role('admin')
                                    {{ $user->userNombre }}
                                @endrole
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                @role('superadmin')
                                <input id="email" type="email" class="form-control" name="email" value="{{ $user->userEmail }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                @endrole
                                
                                @role('admin')
                                    {{ $user->userEmail }}
                                @endrole
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="empresa" class="col-md-4 control-label">Empresa</label>

                            <div class="col-md-6">
                                @role('superadmin')
                                <select id="empresa" name="empresa" class="form-control">
                                    <option selected="selected" value="{{$user->empresaID}}">{{$user->empresaNombre}} {{$user->empresaRif}}</option>
                                    @foreach($empresas as $empresa)
                                    <option value="{{$empresa->id}}">{{$empresa->nombre}} {{$empresa->rif}}</option>
                                    @endforeach
                                </select>
                                @endrole
                                
                                @role('admin')
                                    {{$user->empresaNombre}} {{$user->empresaRif}}
                                @endrole
                            </div>
                            
                        </div>
                        
                        <div class="form-group">
                            <label for="representante" class="col-md-4 control-label">Tipo de Usuario</label>

                            <div class="col-md-6">
                                <select id="representante" name="representante" class="form-control">
                                    <option value="usuario" @if($user->userCargo === "usuario") selected @endif>Usuario</option>
                                    <option value="admin" @if($user->userCargo === "admin") selected @endif>Contacto de la empresa</option>
                                </select>
                            </div>
                            
                        </div>
                        
                        <div class="form-group">
                            <label for="activo" class="col-md-4 control-label">Activo</label>

                            <div class="col-md-6">
                                <select id="activo" name="activo" class="form-control">
                                    <option value="no" @if($user->userActivo === "no") selected @endif>Inactivo</option>
                                    <option value="si" @if($user->userActivo === "si") selected @endif>Activo</option>
                                </select>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Editar a {{ $user->userNombre }}</button>
                                <button type="button" class="btn btn-danger" onclick="event.preventDefault();
                                                     document.getElementById('deleteCliente').submit();">Eliminar a {{ $user->userNombre }}</button>
                            </div>
                        </div>
                        
                    </form>
                    <form id="deleteCliente" action="/clientes/{{ $user->idCliente }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}{{ method_field('DELETE') }}
                    </form>
                    <!--Panel-->
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
