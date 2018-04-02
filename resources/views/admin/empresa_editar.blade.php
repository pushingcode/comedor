@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $mensaje }}</div>
                @foreach($empresas as $empresa)
                <div class="panel-body">
                    {!! Form::open(['action' => ['EmpresaController@update', $empresa->id]]) !!}
                    {{ method_field('PUT') }}
                    <div class="form-group">	
                        {!! Form::label('nombre','Razon Social: ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                        {!! Form::text('nombre', $empresa->nombre, ['class' => 'form-control']) !!}
                        </div>
                  </div>
                  <br>
                   <br>
                  <div class="form-group">
                        {!! Form::label('rif','RIF: ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                        {!! Form::text('rif', $empresa->rif, ['class' => 'form-control']) !!}
                        </div>
                  </div>
                  <br>
                   <br>
                  <div class="form-group">
                        {!! Form::label('email','Correo: ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                        {!! Form::text('email', $empresa->email,['class' => 'form-control']) !!}
                        </div>
                  </div>
                  <br>
                   <br>
                  <div class="form-group">
                        {!! Form::label('direccion','Direccion : ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                        {!! Form::text('direccion', $empresa->direccion,['class' => 'form-control']) !!}
                        </div>
                  </div>
                  <br>
                   <br>
                  <div class="form-group">
                        {!! Form::label('telefono','Telefono: ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                        {!! Form::text('telefono', $empresa->telefono,['class' => 'form-control']) !!}
                        </div>
                  </div>
                  <br>
                   <br>
                  <div class="form-group">
                        {!! Form::label('empleados','Empleados: ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                        {!! Form::number('empleados', $empresa->empleados,['class' => 'form-control']) !!}

                        </div>
                  </div>
                  <br>
                   <br>
                  <div class="form-group">
                        {!! Form::label('activo','Estatus Empresa: ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            @if($empresa->activo === 'si')
                                Activa: {!! Form::radio('activo','si', true) !!}&nbsp;Inactiva: {!! Form::radio('activo','no', false) !!}
                            @else
                                Activa: {!! Form::radio('activo','si', false) !!}&nbsp;Inactiva: {!! Form::radio('activo','no', true) !!}
                            @endif
                        </div>
                        
                  </div>
                   <br>
                   <br>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary form-control">Editar</button>
                    {!! Form::close() !!}
                    
                    <hr>
                    @can('eliminar empresa')
                    <h4>Eliminar {{ $empresa->nombre }} <small>Esta accion creara una marca de eliminacion sobre el registro</small></h4>
                    <button type="button" class="btn btn-danger form-control" onclick="event.preventDefault();
                                                     document.getElementById('deleteEmpresa').submit();">Eliminar</button>
                    <form id="deleteEmpresa" action="/empresa/{{ $empresa->id }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}{{ method_field('DELETE') }}
                    </form>
                    @endcan
                 </div>
                    
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection