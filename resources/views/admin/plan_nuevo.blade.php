@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{$mensaje}}</h2></div>

                <div class="panel-body">

					<h3>Planificacion de servicios</h3>
                    
                    <!---->
                    <table class="table table-condensed">
                        <tr>
                            <th>Producto</th>
                            <th>Codigo</th>
                            <th>Cantidades</th>
                        </tr>
                    @foreach($productos as $producto)
                        <tr>
                            <td>{{$producto->nombre}}</td>
                            <td>{{$producto->codigo}}</td>
                            <td>{{$producto->cantidad_e}}</td>
                        </tr>
                    @endforeach

                    </table>
                    <!---->
						

						<form class="form-horizontal" action="/plan" method="POST">

						{{ csrf_field() }}

						<input type="hidden" name="clase" value="multiple">

                                                <p>Enviar todos los productos existentes a planificacion y crear el menu del dia</p>
							<div class="form-group">
                                <label class="col-md-4 control-label" for="menu_name">Nombre del Menu: </label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="menu_name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="servicios">Tipo de servicio: </label>
                                <div class="col-md-6">
                                    <select class="form-control" name="servicios" id="servicios">
                                        <option value="desayuno">Desayuno</option>
                                        <option value="almuerzo">Almuerzo</option>
                                        <option value="cena">Cena</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="servicios">Seccion de Servicio:</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="seccion" id="seccion">
                                        <option value="comedor">Comedor</option>
                                        <option value="delivery">Delivery</option>
                                        <option value="vip">Vip</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="publicar">Fecha de publicacion: </label>
                                <div class="col-md-6">
                                    <input class="date form-control" type="text" name="publicar">
                                </div>
                            </div>
                                                        
							

								<input type="hidden" name="produccion" value='
								@php 
                                    foreach ($productos as $producto){
                                        echo $producto->id.",";
                                    }				
								@endphp
								'>
								

							<input class="form-control btn btn-success" type="submit" value="Enviar ( {{ $totalPlan }} ) productos a planificacion">

						</form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $('.date').datepicker({  

       format: 'dd-mm-yyyy',
       autoclose: true

     });  

</script>
@endsection