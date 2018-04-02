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
						@if (count($inventarios)===0)
						<p>Alerta ninguno de los ingredientes esta disponible!!!!</p>
						@endif

						<p><a href="/recetas/create">Crear Nueva Receta</a> | <a href="/produccion" target="_self">Inspeccionar Produccion</a></p>

						@foreach($recetas as $key => $values)

						@php
						$key = explode("|",$key);
						@endphp
							<!--Titulo de receta y tipo de producto-->
							<h3>{{ $key[0] }}<small> {{ $key[2] }}</small></h3>
							<p>Creado el: {{ $key[3] }}</p>
							@php

								$stop 			= array();
								$proteinas 		= array();
								$grasas			= array();
								$carbohidratos          = array();
								$calorias		= array();
								$x = 0;
								for ($i = 1; $i <= count($values); $i++) {
								    $clave = "producto".$i;

								    echo "<p>". $values[$x][$clave]["nombre"] ." Cantidad: ". $values[$x][$clave]["cantidad"] ."g.</p>";
								    $proteinas[] 		= round(($values[$x][$clave]["proteinas"]*$values[$x][$clave]["cantidad"])/100, 2);

								    $grasas[] 			= round(($values[$x][$clave]["grasas"]*$values[$x][$clave]["cantidad"])/100, 2);

								    $carbohidratos[] 	= round(($values[$x][$clave]["carbohidratos"]*$values[$x][$clave]["cantidad"])/100, 2);

								    $calorias[] 		= round(($values[$x][$clave]["calorias"]*$values[$x][$clave]["cantidad"])/100, 2);

								    $x++;
								}

							@endphp

							@foreach($stop as $action)
								@if($action == "t")
									<p>No es posible enviar a produccion</p>
								@endif
							@endforeach

						<p>
							Proteinas Totales<sup>(<?php echo count($proteinas);?>)</sup>: 
							<?php echo array_sum($proteinas);?>
						</p>
						<p>
							Grasas Totales<sup>(<?php echo count($grasas);?>)</sup>: 
							<?php echo array_sum($grasas);?>
						</p>
						<p>
							Carbohidratos Totales<sup>(<?php echo count($carbohidratos);?>)</sup>:
						 	<?php echo array_sum($carbohidratos);?>
						 </p>
						<p>
							Calorias Totales<sup>(<?php echo count($calorias);?>)</sup>: 
							<?php echo array_sum($calorias);?>
						</p>
						<a class="btn btn-primary" href="/produccion/producir_receta/{{ $key[1] }}" role="button">
							Enviar a produccion
						</a>
						<hr>
						@endforeach	
						<p>Valores nutricionales estimados para porciones de 100g</p>

				</div>
			</div>
		</div>
	</div>
</div>

@endsection