@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ $mensaje }}</h2></div>
                	<div class="panel-body">
                            
                                @if (count($empresas)===0 )
                                        <p>No existe ninguna empresa!!!!</p>
                                        <a href="" class="btn btn-info" data-toggle="modal" data-target="#myEmpresa">Crear empresa</a>
                                @else

                                        <h4>Ultimas Empresas Agregadas</h4>
                                        <ul>
                                        @foreach($empresas as $empresa)
                                        <li><a href="/empresa/{{$empresa->id}}">{{ $empresa->nombre }}</a></li>
                                        @endforeach
                                        </ul>	


                                <hr>
                                <a href="" class="btn btn-info" data-toggle="modal" data-target="#myEmpresa">Crear empresa</a>
                                @endif
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Modal Empresa -->
<div class="modal fade" id="myEmpresa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
{!! Form::open(['url' => '/empresa']) !!}
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crear Empresa</h4>
      </div>
      <div class="modal-body">
        
          <div class="form-group">	
                {!! Form::label('nombre','Razon Social: ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
        	{!! Form::text('nombre', null, ['class' => 'form-control']) !!}
                </div>
          </div>
          <br>
          <div class="form-group">
        	{!! Form::label('rif','RIF: ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
        	{!! Form::text('rif', null, ['class' => 'form-control']) !!}
                </div>
          </div>
          <br>
          <div class="form-group">
        	{!! Form::label('email','Correo: ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
        	{!! Form::text('email', null,['class' => 'form-control']) !!}
                </div>
          </div>
          <br>
          <div class="form-group">
        	{!! Form::label('direccion','Direccion : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
        	{!! Form::text('direccion', null,['class' => 'form-control']) !!}
                </div>
          </div>
          <br>
          <div class="form-group">
        	{!! Form::label('telefono','Telefono: ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
        	{!! Form::text('telefono', null,['class' => 'form-control']) !!}
                </div>
          </div>
          <br>
          <div class="form-group">
        	{!! Form::label('empleados','Empleados: ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
        	{!! Form::number('empleados', 100,['class' => 'form-control']) !!}
                
                </div>
          </div>
          <br>
          <div class="form-group">
        	{!! Form::label('activo','Estatus Empresa: ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    Activa: {!! Form::radio('activo','si', true) !!}&nbsp;Inactiva: {!! Form::radio('activo','no', false) !!}
                </div>
          </div>
          <br>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      	<button type="submit" class="btn btn-primary">Crear Empresa</button>
      </div>
    </div>
  </div>
  {!! Form::close() !!}
</div>


@endsection