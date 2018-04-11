@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Registo de Usuarios</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('registrar') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirme Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="empresa" class="col-md-4 control-label">Empresa</label>

                            <div class="col-md-6">
                                <select id="empresa" name="empresa" class="form-control">
                                    @foreach($empresas as $empresa)
                                    <option value="{{$empresa->id}}">{{$empresa->nombre}} {{$empresa->rif}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        
                        <div class="form-group">
                            <label for="representante" class="col-md-4 control-label">Tipo de Usuario</label>

                            <div class="col-md-6">
                                <select id="representante" name="representante" class="form-control">
                                    <option value="usuario">Usuario</option>
                                    <option value="admin">Contacto de la empresa</option>
                                </select>
                            </div>
                            
                        </div>
                        
                        <div class="form-group">
                            <label for="activo" class="col-md-4 control-label">Activo</label>

                            <div class="col-md-6">
                                <select id="activo" name="activo" class="form-control">
                                    <option value="no">Inactivo</option>
                                    <option value="si">Activo</option>
                                </select>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Registrar
                                </button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
