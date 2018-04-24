@extends ('layouts.admin')
@section ('contenido')
<div class="row">
      <div class="col-lg-8 col-md-8 col-sm8 col-xs-12">
      <h3>Listado de Empresas <a href="empresa/create"><button class="btn btn-success">Nuevo</button></a>
      @include('registro.empresa.search')
      </div>
      </div>

     <div class="row">
     	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
     	   <div class="table-responsive">
     	   	    <table class="table table-striped table-bordered table-condensed table-hover">
     	   	    	<thead>
     	   	    		<th>Id</th>
     	   	    		<th>Nombre</th>
                        <th>Código</th>
     	   	    		<th>Dirección</th>
                        <th>Cantidad de Empleados</th>
                        <th>Estado</th>
     	   	    		<th>Opciones</th>                        
     	   	    	</thead>
     	   	    	@foreach ($empresas as $emp)
     	   	    	<tr>
     	   	    		<td>{{ $emp->idempresa}}</td>
                        <td>{{ $emp->nombre}}</td>
                        <td>{{ $emp->codigo}}</td>
                        <td>{{ $emp->direccion}}</td>
                        <td>{{ $emp->can_empleados}}</td>
                        <td>{{ $emp->estado}}</td>
                        <td>
                            <a href="{{URL::action('EmpresaController@edit',$emp->idempresa)}}"><button class="btn btn-info">Editar</button></a>
                            <a href="" data-target="#modal-delete-{{$emp->idempresa}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
     	   	    		</td>   	   	    		
     	   	    	</tr>
                    @include('registro.empresa.modal')
     	   	    	@endforeach
     	   	    </table>
     	   </div>
     	   {{$empresas->render()}}
     	</div>
     </div>

@endsection   