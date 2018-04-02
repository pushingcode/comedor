@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Gestion de Pedidos</div>

                <div class="panel-body">
                    <!--Panel-->
                    <p>Bienvenido {{ Auth::user()->name }}
                        @role('superadmin')
                                esta conectado como: <a href="#">Administrador del sistema</a>
                        @endrole
                    </p>
                    <!--comprobando parametros empresa no esta registrada en la costenitas-->
                    @if(count($clientes) === 0)
                    
                        <h3>El usuario no tiene asignada empresa</h3>
                        <form class="form-horizontal" action="clientes/rif" method="POST">
                            {{ csrf_field() }}
                        <div class="form-group">
                            <label for="rif" class="col-md-4 control-label">Ingrese el RIF<sup>*</sup></label>
                            <div class="col-md-6">
                                <input id="rif" type="text" class="form-control" name="rif" value="" placeholder="J294493904" required autofocus>
                                <br>
                                <input type="submit" class="form-control" value="Verificar">
                            </div>
                        </div>
                            <small>El RIF a ingresar debe ser cliente activo de Restaurantes Las Costenitas CA</small><br>
                            <small>Si el RIF ingresado cumple con lo anterior sera agregado al grupo de consumo de la empresa en estado inactivo</small>
                        </form>
                    @else
                           <!--empresa registrada en las costenitas-->
                         {{-- cargamos el nombre de la empresa --}}
                        @foreach($empresas as $empresa)
                            Empresa Afiliada: {{ $empresa->nombre }}
                            <br>
                            Rif: {{ $empresa->rif }}
                            <br>
                            Empresa Activa: {{ $empresa->activo }}
                        @endforeach
                        <br>
                        {{-- cargamos info del usuario --}}
                    
                        @foreach($clientes as $cliente)
                            Estado del cliente: @if($cliente->activo == 'si') <span class="label label-success" data-toggle="tooltip" data-placement="right" title="ok">Activo con la empresa {{$empresa->nombre}}</span> @else <span class="label label-warning" data-toggle="tooltip" data-placement="right" title="Contacte al representate de su empresa">Inactivo</span> @endif<br>
                        @endforeach
                        <hr>
                        
                    {{-- si el usuario no esta activo no leemos los menus disponibles --}}
                    
                        @if($cliente->activo === 'si')
                        
                        {{-- crear las consultas en el controlador [Ordenes,menu] --}}
                        
                            @if(count($ordenes) === 0)
                                <h4>No existen Ordenes Creadas</h4>
                            @else
                                @foreach($ordenes as $orden)
                                    @if($orden->entregado === 'no')
                                        <h4>Ordenes Pendientes</h4>
                                        {{ $orden->nombreMenu }} {{ $orden->fechaMenu }}<br>
                                    @else
                                        <h4>Ordenes Completadas</h4>
                                    @endif
                                @endforeach
                            @endif
                            
                        @endif
                        
                        
                        
                        
                        @if(count($menus) === 0)
                            <h4>No existen Menus Creados</h4>
                        @else
                        @if($menus[0]->id === $ordenes[0]->idMenu)
                            <p class="bg-danger">El menu activo se encuentra ordenado.</p>
                        @else
                        <hr>
                        <!--Avisos-->
                        @if($cliente->activo == 'si')
                        <p class="bg-success">Escoje el menu de tu prederencia</p>
                        @else
                        <p class="bg-danger">Te encuentras inactivo. Contacta al representante de tu empresa.</p>
                        @endif
                        <form class="form-inline" action="ordenes" method="POST">
                            <fieldset {{ $cliente->activo === 'si' ? '' : 'disabled' }}>
                            {{ csrf_field() }}
                            <input type="hidden" name="menu" value="{{$menus[0]->id}}">
                        <!-- menus principales-->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                              <h3 class="panel-title"><h3>{{ $menus[0]->nombre }}<small> servicio de {{ $menus[0]->servicioPlan }}</small></h3></h3>
                            </div>
                            <div class="panel-body">
                                @foreach($menus as $menu)
                                    @foreach($planes as $plan)
                                    @if($menu->idPlanes === $plan[0]->idProduccion)

                                     @if($plan[0]->tipo === "principal")
                                        <!-- principales -->
                                          <div class="col-md-4">
                                              
                                              <div class="panel panel-info">
                                                  
                                                  <div class="panel-heading">
                                                        <h3 class="panel-title">{{$plan[0]->nombre}}</h3>
                                                  </div>
                                                      <div class="panel-body">
                                                          <div class="form-group">
                                                              <label for="principal">Seleccionar:</label>
                                                              <input type="radio" name="principal" value="p-{{$plan[0]->id}}">
                                                          </div>
                                                              <button type="button" 
                                                                      class="btn btn-xs btn-success" 
                                                                      data-html="true" 
                                                                      data-placement="top"
                                                                      data-toggle="popover" 
                                                                      title="{{$plan[0]->nombre}}" 
                                                                      data-content="
                                                                        @php
                                                                            $receta = json_decode($plan[0]->receta, true);
                                                                        @endphp
                                                                            @foreach($receta as $values)
                                                                                @foreach($values as $value)
                                                                                <strong>{{$value['nombre']}}</strong> Cantidad: {{$value['cantidad']}}gr.<br>
                                                                                <small>Proteinas {{$value['proteinas']}}<br>Grasas: {{$value['grasas']}}<br>Carbohidratos: {{$value['carbohidratos']}}<br>Calorias: {{$value['calorias']}}<br></small>
                                                                                @endforeach
                                                                            @endforeach
                                                                      ">Info. Nutricional</button>
                                                      </div>
                                              </div>  
                                          </div>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                            </div>
                        </div>
                        <!--Contornos-->
                        <div class="panel panel-warning">
                        <div class="panel-heading">
                          <h3 class="panel-title">Contornos disponibles</h3>
                        </div>
                        <div class="panel-body">
                          @foreach($menus as $menu)
                                    @foreach($planes as $plan)
                                    @if($menu->idPlanes === $plan[0]->idProduccion)

                                     @if($plan[0]->tipo === "contorno")
                                        <!-- principales -->
                                          <div class="col-md-4">
                                              
                                              <div class="panel panel-info">
                                                  
                                                  <div class="panel-heading">
                                                        <h3 class="panel-title">{{$plan[0]->nombre}}</h3>
                                                  </div>
                                                      <div class="panel-body">
                                                          <div class="form-group">
                                                              <label for="contorno">Seleccionar:</label>
                                                              <input type="radio" name="contorno" value="c-{{$plan[0]->id}}">
                                                          </div>
                                                              <button type="button" 
                                                                      class="btn btn-xs btn-success" 
                                                                      data-html="true" 
                                                                      data-placement="top"
                                                                      data-toggle="popover" 
                                                                      title="{{$plan[0]->nombre}}" 
                                                                      data-content="
                                                                        @php
                                                                            $receta = json_decode($plan[0]->receta, true);
                                                                        @endphp
                                                                            @foreach($receta as $values)
                                                                                @foreach($values as $value)
                                                                                <strong>{{$value['nombre']}}</strong> Cantidad: {{$value['cantidad']}}gr.<br>
                                                                                <small>Proteinas {{$value['proteinas']}}<br>Grasas: {{$value['grasas']}}<br>Carbohidratos: {{$value['carbohidratos']}}<br>Calorias: {{$value['calorias']}}<br></small>
                                                                                @endforeach
                                                                            @endforeach
                                                                      ">Info. Nutricional</button>
                                                      </div>
                                              </div>  
                                          </div>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                        </div>
                      </div>
                         <button type="submit" class="btn btn-primary">Crear Orden</button>
                         </fieldset>
                        </form>
                        @endif
                        <hr>
                    @endif
                    <!--Panel-->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection