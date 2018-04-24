@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ $mensaje }}</h2></div>
                	<div class="panel-body">
	                	
						@if (count($inventarios)===0)
						<p>Alerta ninguno de los ingredientes esta disponible!!!!</p>
						@endif
						<script>
							var sumas =[];
						</script>
						@foreach($recetas as $key => $values)

						@php
						$key = explode("|",$key);
						@endphp

							<h3><a href="produccion/producir_receta/{{ $key[1] }}">{{ $key[0] }}</a></h3>

							<form action="/produccion" method="POST">
							{{ csrf_field() }}
							<input type="hidden" name="receta" value="{{ $key[1] }}">
								<p>Batch de Produccion: <input id="batch" type="text" name="batch" value='1'> (Porciones o Raciones)</p>
								<p><small>La produccion va en funcion de inventarios existentes</small></p>
						<hr>
							@php
								$x = 0;
								for ($i = 1; $i <= count($values); $i++) {
								    $clave = "producto" . $i;
								    echo"<p>". $values[$x][$clave]["nombre"] ." Cantidad Gr: <input placeholder=gramos type='text' name='".$values[$x][$clave]["id"]."' value='" . $values[$x][$clave]["cantidad"] . "'> (x Porciones o Raciones)<br>";
									foreach($inventarios as $inventario){

										if($values[$x][$clave]["id"] == $inventario->id) {

											echo "<p>Cantidades Aprox. en invetario: <span id='cantInv-".$values[$x][$clave]["id"]."'>" . $inventario->cantidad / 1000 . "Kg</span></p>";
											//echo"<input name='INV-".$values[$x][$clave]["id"]."' type='hidden' value='" . $inventario->cantidad / 1000 . "'>";
											echo "<p>Cantidades Aprox. despues de cargar el batch: <span class='cantidades' id='total-".$values[$x][$clave]["id"]."'></span></p>";
											echo "<p>Cantidad de Producto:  <span id='producido-".$values[$x][$clave]["id"]."'></span></p>";

										}
										
									}@endphp
											<script>
											$('input[name=batch]').keyup(function(){
												var epsilon = 0.001;
												var sum = parseInt($('input[name= @php echo $values[$x][$clave]["id"]; @endphp]').val(),10)*1000;
												var cantidad = parseInt($('@php echo"#cantInv-" . $values[$x][$clave]["id"]; @endphp').text(),10);
												sum *= parseInt($('input[name=batch]').val(),10)/1000000;
												total = ((cantidad - sum)+epsilon).toFixed(2);
												producido = (sum + epsilon).toFixed(2);
												sumas.push(producido);
												color = 'green';
												color2 = 'green';
												mensaje = '';
												mensaje2 = 'Requerido aprox.';
												if(total < 0){
													color = 'red';
													mensaje = 'No es posible cargar inventarios negativos';
												}else if(total <= 0.1 || total <= 1){
													color = 'orange';
													mensaje = 'Alerta!! El inventario esta peligrosamente bajo';
												}
												$('@php echo"#total-".$values[$x][$clave]["id"]; @endphp').text(total + "Kg " + mensaje).css('color', color);
												$('@php echo"#producido-".$values[$x][$clave]["id"]; @endphp').text(producido + "Kg " + mensaje2).css('color', color2);
												console.log(sum);

											})
											</script>
									@php
									
								    echo"</p>";

								    $x++;
								}

							@endphp

						<hr>
						<p class="lead">Peso total de Produccion: <span id="TotalProduccion"></span></p>
						@endforeach
						<script>

						$('input[name=batch]').keyup(function(){
							//console.log(sumas.reduce(function(a,b){ return a + b; }));
							$.sum = function(arr) {
							    var r = 0;
							    $.each(arr, function(i, v) {
							        r += +v;
							    });
							    return r;
							}
							var sum = $.sum(sumas)

							$('#TotalProduccion').text(sum + "Kg ");
							console.log(sum);
							sumas = [];
						})
							
						</script>

						<input type="submit" value="Enviar a Produccion">
						</form>

				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
	
	$(document).ready(function() {
		$('input[type="submit"]').attr('disabled', true);
		$('#batch').on('keyup',function() {
		    if($(this).val() != ''){
		        $('input[type="submit"]').attr('disabled' , false);
		    }else{
		        $('input[type="submit"]').attr('disabled' , true);
		    }
		});


		$("#batch").keydown(function (e) {
	        // Allow: backspace, delete, tab, escape, enter and .
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
	             // Allow: Ctrl+A, Command+A
	            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
	             // Allow: home, end, left, right, down, up
	            (e.keyCode >= 35 && e.keyCode <= 40)) {
	                 // let it happen, don't do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });
	});
</script>
@endsection