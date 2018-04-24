@extends ('layouts.admin')
@section ('contenido')
      <div class="row">
           <div class="col-lg-6 col-sm-6 col-xs-12">
                 <h3>Editar Datos del Empleado: {{ $empleado->nombre}}</h3>
                 @if (count($errors)>0)
                 <div class="alert alert-danger">
                 <ul>
                 	@foreach ($errors->all() as $error)
                 	<li>{{$error}}</li>
                 	@endforeach
                 </ul>
                 	
                 </div>
           	     @endif

           	     {!!Form::model($empleado,['method'=>'PATCH','route'=>['empleado.update',$empleado->idempleado]])!!}
           	     {{Form::token()}}
           	     <div class="form-group">
           	        <label for="nombre">Nombre</label>
           	        <input type="text" name="nombre" class="form-control" value="{{$empleado->nombre}}" placeholder="Nombre...">
           	     	
           	     </div>
                  <div class="form-group">
                    <label for="nombre">Apellido</label>
                    <input type="text" name="apellido" class="form-control" value="{{$empleado->apellido}}" placeholder="Apellido...">
                  
                 </div>
           	     <div class="form-group">
           	        <label for="descripcion">Identificación</label>
           	        <input type="text" name="identificacion" class="form-control" value="{{$empleado->identificacion}}" placeholder="Identificación...">
           	     	
           	     </div>
                 <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
            <label>Empleado</label>
            <select name="idcategoria" class="form-control">
               @foreach ($empresas as $emp)
               @if ($emp->idempresa==$empleado->idempresa)
               <option value="{{$emp->idempresa}}" selected>{{$emp->nombre}}</option>
               @else
               <option value="{{$emp->idempresa}}">{{$emp->nombre}}</option>
               @endif
               @endforeach 
            </select>
              
            </div>
            <div class="form-group">
                    <label for="descripcion">Cargo</label>
                    <input type="text" name="cargo" class="form-control" value="{{$empleado->cargo}}" placeholder="Cargo...">
                  
                 </div>
                 <div class="form-group">
                    <label for="descripcion">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{$empleado->telefono}}" placeholder="Teléfono...">
                  
                 </div>
           	     <div class="form-group">
           	        <button class="btn btn-primary" type="submit">Guardar</button>
           	        <button class="btn btn-danger" type="reset">Cancelar</button>
           	     	
           	     </div>

           	     {!!Form::close()!!}
           </div>
      	
      </div>

@endsection   