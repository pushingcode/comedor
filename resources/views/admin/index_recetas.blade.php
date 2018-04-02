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
                                                        <th>Accion <a href="recetas/create" class="btn btn-success btn-xs">+ Receta</a></th>
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
									<a href="/produccion/producir_receta/{{ $key[1] }}"> Producir</a>&nbsp;
								@endcan
								@can('editar receta')
									<a href="/recetas/{{ $key[1] }}/edit">Editar</a>&nbsp;
								@endcan 
								@can('eliminar receta')
									<a href="#" onclick="event.preventDefault(); document.getElementById('delform{{ $key[1] }}').submit();">
                                        Eliminar
                                    </a>&nbsp;

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