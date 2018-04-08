@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ $mensaje }}</h2></div>
                	<div class="panel-body">
                	@if($errors->any())
						<h4>{{$errors->first()}}</h4>
					@endif
					<!--tabla para mostrar las recetas-->
					<table class="table table-striped">
						<thead>
							<th>Nombre</th>
							<th>Tipo</th>
							<th>Fecha</th>
                            <th>Accion <a data-toggle="tooltip" data-placement="top" title="Crear nueva receta" href="recetas/create" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Receta</a></th>
						</thead>
						<tbody>
						@foreach($recetas as $key => $values)
						@php
						$key = explode("|",$key);
						@endphp
						<tr>
							<td>{{ $key[0] }}</td>
							<td>{{ $key[2] }}</td>
							<td>{{ $key[3] }}</td>
							<td>
								@can('crear produccion')
									<a data-toggle="tooltip" data-placement="top" title="Cargar Produccion" class="btn btn-success" href="/produccion/producir_receta/{{ $key[1] }}"><i class="fa fa-cogs" aria-hidden="true"></i></a>
								@endcan
								@can('editar receta')
									<a data-toggle="tooltip" data-placement="top" title="Editar Receta" class="btn btn-info" href="/recetas/{{ $key[1] }}/edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								@endcan 
								@can('eliminar receta')
									<a data-toggle="tooltip" data-placement="top" title="Eliminar Recetas" class="btn btn-danger" href="#" onclick="event.preventDefault(); document.getElementById('delform{{ $key[1] }}').submit();">
									<i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>

                                        <form id="delform{{ $key[1] }}" action="/recetas/{{ $key[1] }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                        </form>
								@endcan
							</td>
						</tr>
						@endforeach
						</tbody>
					</table>
					<!--tabla para mostrar las recetas-->
					</div>
			</div>
		</div>
	</div>
</div>

@endsection