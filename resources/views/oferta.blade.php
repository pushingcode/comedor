@extends('layouts.app')

@section('content')
@php
    $bloackForm = false;
    $OrdenId = 0;
    $menuServidos = array();
@endphp
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Gestion de Pedidos</h2></div>

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
                        @php $bloackForm = true; @endphp
                        <!--boton de sugerencias y comentarios-->
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#comentarios">
                          Dejanos un Comentario o sugerencia
                        </button> <a href="events" class="btn btn-default"><i class="fa fa-calendar" aria-hidden="true"></i></a>

                        <hr>



                        <!-- Modal -->
                        <div class="modal fade" id="comentarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">En que podemos Mejorar!!!</h4>
                              </div>
                              <div class="modal-body">
                                  <form id="enviarComentario" action="comentarios" method="POST">
                                      {{csrf_field()}}
                                      <div class="form-group">
                                          <label class="col-md-4 control-label">Deja tu Comentario:</label>
                                          <div class="col-md-6">
                                              <textarea name="comentario" class="form-control"></textarea>
                                          </div>
                                      </div>
                                      <br>
                                      <br>
                                      <br>
                                  </form>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button onclick="event.preventDefault(); document.getElementById('enviarComentario').submit();" type="button" class="btn btn-primary">Enviar</button>
                              </div>
                            </div>
                          </div>
                        </div>


                        <!--boton de comentarios y sugerencias-->

                        {{-- crear las consultas en el controlador [Ordenes,menu] --}}

                            @if(count($ordenes) === 0)
                                <h4>No existen Ordenes Activas</h4>

                            @else

                            <h4>Ordenes Pendientes</h4>
                            <ul>

                                @foreach($ordenes as $orden)


                                    @if($orden->entregado === 'no')
                                    @php
                                    $bloackForm = false;
                                    @endphp
                                    <li>{{ $orden->nombreMenu }} <a class="btn btn-xs btn-danger" href="#" role="button" onclick="event.preventDefault(); document.getElementById('deleteOrden{{ $orden->id }}').submit();">Eliminar orden</a>
                                        <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#t{{ $orden->id }}">Ver Ticker</button></li>
                                        <form id="deleteOrden{{$orden->id}}" action="ordenes/{{$orden->id}}" method="POST" style="display: none;">
                                            {{ csrf_field() }} {{ method_field('DELETE') }}
                                        </form>
                                    @endif

                                    <!--Modals-->

                                    <div class="modal fade" id="t{{ $orden->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              <h2 class="modal-title" id="myModalLabel">Ticket de Servicio {{$orden->id}}</h2>
                                            </div>
                                            <div class="modal-body">
                                                @php

                                                    $decode = json_decode($orden->codigo, true);
                                                    foreach($decode as $code){

                                                        foreach($planes as $plan){

                                                            if($code['principal'] == $plan[0]->id){ echo"<h3> Principal: ".$plan[0]->nombre."</h3>";}
                                                            if($code['contorno1'] == $plan[0]->id){ echo"<h3> Contorno: ".$plan[0]->nombre."</h3>";}
                                                            if($code['contorno2'] == $plan[0]->id){ echo"<h3> Contorno: ".$plan[0]->nombre."</h3>";}
                                                            //if($code['contorno3'] == $plan[0]->id){ echo"<h3> Contorno: ".$plan[0]->nombre."</h3>";}
                                                            //if($code['contorno4'] == $plan[0]->id){ echo"<h3> Contorno: ".$plan[0]->nombre."</h3>";}


                                                        }

                                                    }

                                                @endphp

                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>


                                    <!--Modals-->

                                @endforeach

                            </ul>

                            <hr>

                            <h4>Ordenes Entregadas</h4>
                            <ul>

                                @foreach($ordenes as $orden)


                                    @if($orden->entregado === 'si')
                                    @php

                                    if($menus[0]->id == $orden->idMenu) {
                                    $bloackForm = false;
                                    }
                                    
                                    @endphp
                                    <li>{{ $orden->nombreMenu }} <a class="btn btn-xs btn-success" href="#" role="button">Orden Entregada</a></li>
                                    @endif

                                @endforeach

                            </ul>


                            @endif

                        @endif




                        @if(count($menus) === 0)
                            <h4>No existen Menus Creados</h4>
                        @else


                        <!--Avisos-->

                         @if($menus[0]->id === 0)
                                <p class="bg-danger">El menu activo se encuentra ordenado.</p>
                                
                         @else

                        <hr>

                        @if($cliente->activo == 'si')
                        <p class="bg-success">Escoje el menu de tu prederencia</p>
                        @else

                        <p class="bg-danger">Te encuentras inactivo. Contacta al representante de tu empresa.</p>
                        @endif
                        <!--Avisos-->

                            <!--Form Pedido-->

                                <form class="form-inline" action="ordenes" method="POST">
                                    <fieldset {{ $bloackForm === true ? '' : 'disabled' }} >
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
                                                                <h3 class="panel-title">{{ $plan[0]->nombre }}</h3>
                                                          </div>
                                                              <div class="panel-body">
                                                              @php
                                                                $formControl = true;
                                                              foreach($payLoadP as $key => $value){
                                                                if($plan[0]->nombre == $key){

                                                                  if($value == $plan[0]->salidaProduccion){
                                                                    echo"<div class='alert alert-danger' role='alert'>AGOTADO</div>";
                                                                    $formControl = false;
                                                                  }

                                                                }
                                                              }

                                                              @endphp
                                                              @if($formControl == true)
                                                                  <div class="form-group">
                                                                      <label for="principal">Seleccionar:</label>
                                                                      <input type="radio" name="principal" value="{{$plan[0]->id}}">
                                                                  </div>
                                                              @endif
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
                                <select class="form-control" id="add_field">
                                <option>Seleccione uno o mas Contornos</option>
                                @foreach($menus as $menu)
                                    @foreach($planes as $plan)
                                        @if($menu->idPlanes === $plan[0]->idProduccion)
                                            @if($plan[0]->tipo === "contorno")
                                            <option  data-valor="
                                                                @php
                                                                    $receta = json_decode($plan[0]->receta, true);
                                                                @endphp
                                                                    @foreach($receta as $values)
                                                                        @foreach($values as $value)
                                                                        <strong>{{$value['nombre']}}</strong> Cantidad: {{$value['cantidad']}}gr.<br><small>Proteinas {{$value['proteinas']}}<br>Grasas: {{$value['grasas']}}<br>Carbohidratos: {{$value['carbohidratos']}}<br>Calorias: {{$value['calorias']}}<br></small>
                                                                        @endforeach
                                                                    @endforeach
                                                                " value="{{$plan[0]->id}}">
                                                {{$plan[0]->nombre}}
                                            </option>
                                            @endif
                                        @endif
                                    @endforeach
                                @endforeach
                                </select>
                                <hr>
                                <div id="txtboxToFilter" class="input_fields_wrap"></div>
                                <!--jquery-->
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
                                <script>
                                $(document).ready(function() {
                                    var max_fields      = 2; //max input permitidos
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
                                                $(wrapper).append('<div class="form-group"><div class="col-sm-8"><input class="form-control" id="id-' + val_input + '" type="hidden" name="contorno' + x + '" value="' + val_input + '"/></div> <button type="button" class="btn btn-success" data-html="true" data-placement="top" data-toggle="popover" title="' + opt_input + '" data-content="' + info_input + '">' + opt_input + '</button> <a href="#" class="remove_field btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a><div class="col-md-6 col-md-offset-3"></div></div>'); //add input box
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
                                <!--jquery-->
                                </div>
                              </div>
                                 <button type="submit" class="btn btn-primary">Crear Orden</button>
                                 </fieldset>
                                </form>


                        <!--Form Pedido-->


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
