@extends ('layouts.admin')
@section ('contenido')
      <div class="row">
           <div class="col-lg-6 col-sm-6 col-xs-12">
                 <h3>Editar Datos de Empresa: {{ $empresa->nombre}}</h3>
                 @if (count($errors)>0)
                 <div class="alert alert-danger">
                 <ul>
                 	@foreach ($errors->all() as $error)
                 	<li>{{$error}}</li>
                 	@endforeach
                 </ul>
                 	
                 </div>
           	     @endif

           	     {!!Form::model($empresa,['method'=>'PATCH','route'=>['empresa.update',$empresa->idempresa]])!!}
           	     {{Form::token()}}
           	     <div class="form-group">
           	        <label for="nombre">Nombre</label>
           	        <input type="text" name="nombre" class="form-control" value="{{$empresa->nombre}}" placeholder="Nombre...">
           	     	
           	     </div>
                  <div class="form-group">
                    <label for="nombre">Código</label>
                    <input type="text" name="codigo" class="form-control" value="{{$empresa->codigo}}" placeholder="Código...">
                  
                 </div>
           	     <div class="form-group">
           	        <label for="descripcion">Dirección</label>
           	        <input type="text" name="direccion" class="form-control" value="{{$empresa->direccion}}" placeholder="Dirección...">
           	     	
           	     </div>
                 <div class="form-group">
                    <label for="descripcion">Cantidad de Empleados</label>
                    <input type="text" name="can_empleados" class="form-control" value="{{$empresa->can_empleados}}" placeholder="Cantidad de Empleados...">
                  
                 </div>
           	     <div class="form-group">
           	        <button class="btn btn-primary" type="submit">Guardar</button>
           	        <button class="btn btn-danger" type="reset">Cancelar</button>
           	     	
           	     </div>

           	     {!!Form::close()!!}
           </div>
      	
      </div>

@endsection   