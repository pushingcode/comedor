@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ $mensaje }}</h2></div>
                	<div class="panel-body">
                	@if(count($planes) === 0)
                		<p>No se han creado Planes de Venta.</p>
                		<p>Para crear planes debe enviar producciones a planificacion <a href="/plan/create">Crear</a></p>
                	@else

                	<table class="table">
        				<thead>
        					<th>Codigo</th>
        					<th>Cantidad</th>
        					<th>Servicio</th>
        					<th>Usuario</th>
        					<th>Fecha</th>
                		</thead>
                		<tbody>
                		@foreach($planes as $plan)
                			<td>{{ $plan->codigo }}</td>
                			<td>{{ $plan->cantidad }}</td>
                			<td>{{ $plan->servicio }}</td>
                			<td>{{ $plan->user_id }}</td>
                			<td>{{ $plan->created_at }}</td>
                		@endforeach
                		</tbody>
                	</table>

                	@endif
                	</div>
			</div>
		</div>
	</div>
</div>

@endsection