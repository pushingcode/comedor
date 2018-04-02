@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{$mensaje}}</h2></div>

                <div class="panel-body">

					<h3>Planificacion de servicios</h3>
                                        
						@foreach($productos as $producto)
                                                
                                                @php

                                                        $cantidad = $producto->cantidad_e - $producto->cantidad_s

                                                @endphp
                                                
                                                @if($cantidad == 0)
                                                
                                                @else
                                                
						<form class="form-horizontal" action="/plan" method="POST">

						{{ csrf_field() }}

						<input type="hidden" name="clase" value="sencillo">
						<input type="hidden" name="id" value="{{ $producto->id }}">
                                                    
							{{$producto->nombre}} 
							<br>
							Codigo de produccion: {{$producto->codigo}}
							<br>
							<div class="form-group">
                                                             <label for="cantidad" class="col-md-4 control-label">Cantidad de productos a enviar: </label>
                                                            <div class="col-md-6">
                                                                <select class="form-control" name="cantidad" id="{{$producto->codigo}}">

                                                                    
                                                                    @for($i = $cantidad; $i >=1 ; $i--)

                                                                            <option value="{{ $i }}">{{ $i }} de {{ $cantidad }}</option>

                                                                    @endfor


                                                                    </select>
                                                            </div>

                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="servicios" class="col-md-4 control-label">Tipo de servicio: </label>
                                                            <div class="col-md-6">
                                                                <select class="form-control" name="servicios" id="servicios">
									<option value="desayuno">Desayuno</option>
									<option value="almuerzo">Almuerzo</option>
									<option value="cena">Cena</option>
								</select>
                                                            </div>
                                                        </div>
							
                                                        <div class="form-group">
                                                            <label for="seccion" class="col-md-4 control-label">Seccion de Servicio: </label>
                                                            <div class="col-md-6">
                                                                <select class="form-control" name="seccion" id="seccion">
									<option value="comedor">Comedor</option>
									<option value="delivery">Delivery</option>
									<option value="vip">Vip</option>
								</select>
                                                            </div>
                                                        </div>

							<br>

							<input class="form-control btn btn-info" type="submit" value="Enviar {{ $producto->codigo }} a Planificacion">

							<hr>
						</form>
                                                @endif
						@endforeach

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
@endsection