@extends ('layouts.admin')
@section ('contenido')
      <div class="row">
           <div class="col-lg-6 col-sm-6 col-xs-12">
                 <h3>Nuevo Empleado</h3>
                 @if (count($errors)>0)
                 <div class="alert alert-danger">
                 <ul>
                 	@foreach ($errors->all() as $error)
                 	<li>{{$error}}</li>
                 	@endforeach
                 </ul>
                 	
                 </div>
           	     @endif

           	     {!!Form::open(array('url'=>'registro/empleado','method'=>'POST','autocomplete'=>'off'))!!}
           	     {{Form::token()}}
           	     <div class="form-group">
           	        <label for="nombre">Nombre</label>
           	        <input type="text" name="nombre" class="form-control" placeholder="Nombre...">
           	     	
           	     </div>
                 <div class="form-group">
                    <label for="nombre">Apellido</label>
                    <input type="text" name="apellido" class="form-control" placeholder="Apellido...">
                  
                 </div>
                 <div class="form-group">
                    <label for="nombre">Identificacion</label>
                    <input type="text" name="identificacion" class="form-control" placeholder="Identificación...">
                  </div>
                  <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
            <label>Empresa</label>
            <select name="idempresa" class="form-control">
               @foreach ($empresas as $emp)
               <option value="{{$emp->idempresa}}">{{$emp->nombre}}</option>
               @endforeach 
            </select>
                 </div>
           	     <div class="form-group">
           	        <label for="descripcion">Cargo</label>
           	        <input type="text" name="cargo" class="form-control" placeholder="Cargo...">
           	     	
           	     </div>
                 <div class="form-group">
                    <label for="descripcion">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" placeholder="Teléfono...">
                  
                 </div>
           	     <div class="form-group">
           	        <button class="btn btn-primary" type="submit">Guardar</button>
           	        <button class="btn btn-danger" type="reset">Cancelar</button>
           	     	
           	     </div>

           	     {!!Form::close()!!}
           </div>
      	
      </div>

@endsection   