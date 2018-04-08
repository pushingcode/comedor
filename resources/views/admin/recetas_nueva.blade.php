@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row"> 
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Crear Nueva {{ $mensaje }}</h2></div>
                	<div class="panel-body">
						<form class="form-horizontal" action="/recetas" method="POST">
						{{ csrf_field() }}

						<div class="form-group">
							<label for="nombre" class="col-sm-2 control-label">Receta: </label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="nombre">
							</div>
						</div>
						<div class="form-group">
							<label for="tipo" class="col-sm-2 control-label">Tipo:</label>
							<div class="col-sm-10">
								<select class="form-control" name="tipo" id="tipo" >
									<option value="principal">Principal</option>
									<option value="contorno">Contorno</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="producto" class="col-sm-2 control-label">Producto: </label>
							<div class="col-sm-10">
								<select class="form-control" id="add_field" name="producto">
										<option value="0">Selecione</option>
									@foreach($materia_primas as $materia_prima)

										<option data-valor="Calorias: {{ $materia_prima->calorias }} Proteinas: {{ $materia_prima->proteinas }} Grasas: {{ $materia_prima->grasas }} Carbohidratos: {{ $materia_prima->carbohidratos }}" value="{{ $materia_prima->id }}">{{ $materia_prima->producto }}</option>

									@endforeach
								</select>
							</div>
						</div>
							

							<hr>

							<div id="txtboxToFilter" class="input_fields_wrap"></div>

							<hr>
							<small>Cantidad maxima de 30 items</small>
							<br><input class="form-control" type="submit" value="Crear receta">
							
						</form>
						
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
						<script>
						$(document).ready(function() {
						    var max_fields      = 30; //max input permitidos
						    var wrapper         = $(".input_fields_wrap"); //clase wrapper para input's
						    var add_button      = $("#add_field"); //Select ID
						   
						    var x = 0; //init cd input disponibles
						    $(add_button).change(function(e){ //On.Change value disparo nuevo input
						    var val_input		= $("#add_field").val();
						    var opt_input		= $("#add_field option:selected").text();
						    var info_input		= $("#add_field option:selected").data().valor;
						        e.preventDefault();
						        if (val_input == 0) {
						        	return;
						        } else {
						        	if(x < max_fields){ //cuenta de input's
							            x++; //text box increment
							            $(wrapper).append('<div class="form-group"><label class="col-sm-3 control-label" for="id-' + val_input + '">' + opt_input + ':</label><div class="col-sm-8"><input class="form-control" id="id-' + val_input + '" type="text" name="' + val_input + '"/></div><a href="#" class="remove_field btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a><div class="col-md-6 col-md-offset-3"> <small>' + info_input + '</small> </div></div>'); //add input box
							        }
						        }
						    });
						   
						    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
						        e.preventDefault(); $(this).parent('div').remove(); x--;
						    });

						    $("#txtboxToFilter").keydown(function (e) {
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
				</div>
			</div>
		</div>
	</div>
</div>

@endsection