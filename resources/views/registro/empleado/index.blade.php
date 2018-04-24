@extends ('layouts.admin')
@section ('contenido')
<div class="row">
      <div class="col-lg-8 col-md-8 col-sm8 col-xs-12">
      <h3>Listado de Empleados <a href="empleado/create"><button class="btn btn-success">Nuevo</button></a>
      @include('registro.empleado.search')
      </div>
      </div>

     <div class="row">
     	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
     	   <div class="table-responsive">
     	   	    <table class="table table-striped table-bordered table-condensed table-hover">
     	   	    	<thead>
     	   	    		<th>Id</th>
     	   	    		<th>Nombre</th>
                        <th>Apellido</th>
     	   	    		<th>Identificación</th>
                        <th>Empresa</th>
                        <th>Cargo</th>
                        <th>Teléfono</th>
     	   	    		<th>Opciones</th>                        
     	   	    	</thead>
     	   	    	@foreach ($empleados as $empl)
     	   	    	<tr>
     	   	    		<td>{{ $empl->idusuario}}</td>
                        <td>{{ $empl->nombre}}</td>
                        <td>{{ $empl->apellido}}</td>
                        <td>{{ $empl->identificacion}}</td>
                        <td>{{ $empl->empresa}}</td>
                        <td>{{ $empl->cargo}}</td>
                        <td>{{ $empl->telefono}}</td>
                        <td>
                            <a href="{{URL::action('EmpleadoController@edit',$empl->idusuario)}}"><button class="btn btn-info">Editar</button></a>
                            <a href="" data-target="#modal-delete-{{$empl->idusuario}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
     	   	    		</td>   	   	    		
     	   	    	</tr>
                    @include('registro.empleado.modal')
     	   	    	@endforeach
     	   	    </table>
     	   </div>
     	   {{$empleados->render()}}
     	</div>
     </div>

@endsection   