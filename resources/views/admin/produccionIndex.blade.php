@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Produccion actual</h2></div>
                	<div class="panel-body">

					@foreach($producciones as $produccion)
						<a href="recetas/{{ $produccion->id_r }}">{{ $produccion->nombre }}</a>
						<br>
						Cantidades en produccion: {{ $produccion->cantidad_e }}
						<br>
						Cantidades planificadas: {{ $produccion->cantidad_s }}
						<br>
						Codigo de produccion: {{ $produccion->codigo }}
						<br>
						Produccion cargada por: <a href="users/{{ $produccion->user_id }}">{{ $produccion->name }}</a>
						<hr>

					@endforeach
					<p><a href="/recetas" target="_self">Volver a recetas</a> | <a href="/plan/create" target="_self"> Ir a Planificacion</a></p>

				</div>
			</div>
		</div>
	</div>
</div>

@endsection