@extends ('layouts.admin')
@section ('contenido')
<div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
      <h3>Listado de Productos <a href="producto/create"><button class="btn btn-success">Nuevo</button></a>
      @include('almacen.producto.search')
      </div>
      </div>

     <div class="row">
     	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
     	   <div class="table-responsive">
     	   	    <table class="table table-striped table-bordered table-condensed table-hover">
     	   	    	<thead>
     	   	    		<th>Id</th>
     	   	    		<th>Nombre</th>
     	   	    		<th>Kgs</th>
                        <th>Categoria</th>
                        <th>Carbohidrátos(gr)</th>
                        <th>Proteínas(gr)</th>
                        <th>Grasas(gr)</th>
                        <th>Calorías</th>
                        <th>Imagen</th>
                        <th>Estado</th>
                        <th>Opciones</th>                        
     	   	  
     	   	    	@foreach ($productos as $pro)
     	   	    	<tr>
     	   	    		<td>{{ $pro->idproducto}}</td>
     	   	    		<td>{{ $pro->nombre}}</td>
     	   	    		<td>{{ $pro->existencia}}</td>
                        <td>{{ $pro->categoria}}</td>
                        <td>{{ $pro->carbohidratos}}</td>
                        <td>{{ $pro->proteinas}}</td>
                        <td>{{ $pro->grasa}}</td>
                        <td>{{ $pro->calorias}}</td>
                        <td>
                            <img src="{{asset('/imagenes/productos/'.$pro->imagen)}}" alt="{{ $pro->nombre}}" height="80px" width="80px" class="img-thumbnail">
                        </td>
                        <td>{{ $pro->estado}}</td>
                        <td>
                            <a href="{{URL::action('ProductoController@edit',$pro->idproducto)}}"><button class="btn btn-info">Editar</button></a>
                            <a href="" data-target="#modal-delete-{{$pro->idproducto}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
     	   	    		</td>   	   	    		
     	   	    	</tr>
                    @include('almacen.producto.modal')
     	   	    	@endforeach
     	   	    </table>
     	   </div>
     	   {{$productos->render()}}
     	</div>
     </div>

@endsection   