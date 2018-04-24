@extends ('layouts.admin')
@section ('contenido')
<div class="row">
      <div class="col-lg-8 col-md-8 col-sm8 col-xs-12">
      <h3>Listado de Menús <a href="menu/create"><button class="btn btn-success">Nuevo</button></a>
      @include('menu.menu.search')
      </div>
      </div>

     <div class="row">
     	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
     	   <div class="table-responsive">
     	   	    <table class="table table-striped table-bordered table-condensed table-hover">
     	   	    	<thead>
                        <th>ID</th>
     	   	    		<th>Descripción</th>
     	   	    		<th>Ingredientes</th>
     	   	    		<th>Fecha</th>
                        <th>Estado</th>
     	   	    		<th>Opciones</th>                        
     	   	    	</thead>
     	   	    	@foreach ($menus as $men)
     	   	    	<tr>
     	   	    		<td>{{ $men->idmenu}}</td>
     	   	    		<td>{{ $men->descripcion}}</td>
                        <td>{{ $men->ingredientes}}</td>
     	   	    		<td>{{ $men->fecha}}</td>
                        <td>{{ $men->estado}}</td>
                        <td>
                            <a href="{{URL::action('MenuController@show',$men->idmenu)}}"><button class="btn btn-primary">Detalles</button></a>
                            <a href="" data-target="#modal-delete-{{$men->idmenu}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a>
     	   	    		</td>   	   	    		
     	   	    	</tr>
                    @include('menu.menu.modal')
     	   	    	@endforeach
     	   	    </table>
     	   </div>
     	   {{$menus->render()}}
     	</div>
     </div>

@endsection   