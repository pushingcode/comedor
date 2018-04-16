@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>Bienvenido {{ Auth::user()->name }}
                        @role('superadmin')
                                esta conectado como: <a href="#">Administrador del sistema</a>
                        @endrole
                    </p>

                    <p>


                        @role('superadmin')
                            <!-- Split button -->
<!--                            <div class="btn-group">
                              <button type="button" class="btn btn-danger">Permisos</button>
                              <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu">
                                <li><a href="#">Crear Roles</a></li>
                                <li><a href="#">Editar Roles</a></li>
                                <li><a href="#">Eliminar Roles</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Crear Permisos</a></li>
                                <li><a href="#">Editar Permisos</a></li>
                                <li><a href="#">Eliminar Permisos</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Ver Lista de Roles</a></li>
                                <li><a href="#">Ver Lista de Permisos</a></li>
                              </ul>
                            </div>-->
                            <!-- Split button -->
                            <div class="btn-group">
                              <button type="button" class="btn btn-danger">Clientes</button>
                              <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu">
                                <li><a href="/clientes/create">Crear Usuarios</a></li>
                                <li><a href="#" @role('superadmin') data-toggle="modal" data-target="#editUsuario" @endrole>Editar Usuarios</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/empresa/create">Crear Empresa</a></li>
                                <li><a href="#" @role('superadmin') data-toggle="modal" data-target="#editEmpresa" @endrole>Editar Empresa</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/empresa">Ver Lista de Empresas</a></li>
                                <li><a href="#">Ver Lista de Usuarios</a></li>
                              </ul>
                            </div>

                            <!--Modal para editar usuarios-->

                            <div class="modal fade" id="editUsuario" tabindex="-1" role="dialog" aria-labelledby="myeditUsuario">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    {!! Form::open(['url' => 'clientes/reedit']) !!}
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Seleccione el Usuario a Editar</h4>
                                  </div>
                                  <div class="modal-body">
                                        <div class="form-group">
                                            {!! Form::label('usuario','Usuario: ', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-6">
                                                @php
                                                    $myUser = array();
                                                    foreach($users as $user){
                                                    if($user->userCargo == 'admin') {$marca = "(*)";} else {$marca = "";}
                                                        $myUser[$user->userId] = $user->userNombre . " " . $user->userEmail . "". $marca;
                                                    }
                                                @endphp
                                            {!! Form::select('usuario', $myUser, null, ['placeholder' => 'seleccione...', 'class' => 'form-control']) !!}
                                            <br>

                                            </div>
                                      </div>
                                      <div class="form-group">
                                        <button type="submit" class="btn btn-primary form-control">Editar</button>
                                     </div>
                                  </div>

                                    {!! Form::close() !!}
                                </div>
                              </div>
                            </div>

                            <!--Modal para editar empresas-->

                            <div class="modal fade" id="editEmpresa" tabindex="-1" role="dialog" aria-labelledby="myeditEmpresa">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    {!! Form::open(['url' => 'empresa/reedit']) !!}
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Seleccione Empresa a Editar</h4>
                                  </div>
                                  <div class="modal-body">
                                        <div class="form-group">
                                            {!! Form::label('empresa','Empresa: ', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-6">
                                                @php
                                                    $myEmpresa = array();
                                                    foreach($empresas as $empresa){
                                                        $myEmpresa[$empresa->id] = $empresa->nombre;
                                                    }
                                                @endphp
                                            {!! Form::select('empresa', $myEmpresa, null, ['placeholder' => 'seleccione...', 'class' => 'form-control']) !!}
                                            <br>

                                            </div>
                                      </div>
                                      <div class="form-group">
                                        <button type="submit" class="btn btn-primary form-control">Editar</button>
                                     </div>
                                  </div>

                                    {!! Form::close() !!}
                                </div>
                              </div>
                            </div>

                        @endrole
                        @role('superadmin|jefe de cocina')

                            <button type="button" class="btn btn-success">Inventario</button>

                            <a href="events" class="btn btn-default"><i class="fa fa-calendar" aria-hidden="true"></i></a>

                            <!-- Split button recetas -->
                            <div class="btn-group">
                              <button type="button" class="btn btn-danger">Recetas</button>
                              <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu">
                                <li><a href="/recetas/create">Crear Receta</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/recetas">Ver Lista de Recetas</a></li>
                              </ul>
                            </div>
                            <!-- Split button -->
                            <div class="btn-group">
                              <button type="button" class="btn btn-danger">Produccion</button>
                              <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu">
                                <li><a href="/recetas">Recetas a Produccion</a></li>
<!--                                <li><a href="#">Editar Receta</a></li>
                                <li><a href="#">Eliminar Receta</a></li>-->
                                <li role="separator" class="divider"></li>
                                <li><a href="/produccion">Ver Lista de produccion</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/plan/create">Planificacion</a></li>
                              </ul>
                            </div>

                            <script>
                              $('.destroyRecetas').on('click', function(){
                                    $.confirm({
                                      title: 'Eliminar Receta',
                                      content: '' +
                                      '<form action="" class="formDestroyRecetas">' +
                                      '{{ csrf_field() }} {{ method_field('PUT') }}' +
                                      '<div class="form-group">' +
                                      '<label>Seleccione una receta</label>' +
                                      '<select id="remove_field" name="producto">' +
                                      @foreach($recetas as $receta)
                                      '<option id="OREC-{{ $receta->id }}" class="name form-control" value="{{ $receta->id }}">{{ $receta->nombre }}</option>' +
                                      @endforeach
                                      '</select>' +
                                      '</div>' +
                                      '</form>',
                                      buttons: {
                                          formSubmit: {
                                              text: 'Enviar',
                                              btnClass: 'btn-blue',
                                              action: function () {
                                                  var value = $('#remove_field').val();

                                                  //$.alert('Estas eliminando ');
                                                  $.get('/recetas/sdestroy/' + value, $('#remove_field').serialize(), function(data) {
                                                    $('#REC-' + value).remove();
                                                    console.log('enviado');
                                                     },
                                                     'json' // I expect a JSON response
                                                  );

                                              }
                                          },
                                          cancel: function () {
                                              //close
                                          },
                                      },
                                      onContentReady: function () {
                                          // bind to events
                                          var jc = this;
                                          this.$content.find('form').on('submit', function (e) {
                                              // if the user submits the form by pressing enter in the field.
                                              e.preventDefault();
                                              jc.$$formSubmit.trigger('click'); // reference the button and click it
                                          });
                                      }
                                  });
                              });
                            </script>

                            <!-- Split button -->
                            <div class="btn-group">
                              <button type="button" class="btn btn-danger">Menus</button>
                              <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu">
                                <li><a href="/menu/create">Crear Menu</a></li>
<!--                                <li><a href="#">Editar Menus</a></li>
                                <li><a href="#">Eliminar Menus</a></li>-->
                                <li role="separator" class="divider"></li>
                                <li><a href="/menu">Ver Lista de Menus</a></li>
                              </ul>
                            </div>
                        @endrole
                        @role('superadmin|jefe de cocina')

                            <!-- Split button -->
                            <div class="btn-group">
                              <button type="button" class="btn btn-default">Reportes</button>
                              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu">
                                <li><a href="/semanal/empresas">Pedidos x Empresas</a></li>
                                <li><a data-toggle="modal" data-target="#RepMes" href="#">Pedidos Mensuales</a></li>
<!--                                <li><a href="#"></a></li>-->
<!--                                <li role="separator" class="divider"></li>
                                <li><a href="#">Separated link</a></li>-->
                              </ul>
                            </div>

                        @endrole
                    </p>

                    @if(Auth::check())
                    <br>
                    <h3>Ultimas Recetas</h3>
                    <hr>
                        <ul>
                            @foreach($recetas as $receta)
                                <li id="REC-{{$receta->id}}"><a href="/produccion/producir_receta/{{ $receta->id }}">{{ $receta->nombre }}</a></li>
                            @endforeach
                        </ul>
                    <br>
                    <h3>Produccion</h3>
                    <hr>
                        <ul>
                            @foreach($producciones as $produccion)
                                <li>Codigo: {{ $produccion->codigo }} </li>
                            @endforeach
                        </ul>
                    <br>
                    <h3>Planes</h3>
                    <hr>
                        <ul>
                            @foreach($planes as $plan)
                                <li>Codigo: {{ $plan->codigo }} </li>
                            @endforeach
                        </ul>
                    <br>
                    <h3>Menus</h3>
                    <hr>
                        <ul>
                            @foreach($menus as $menu)
                                <li>Nombre de menu: {{ $menu->nombre }} Activo: {{ $menu->activo }} </li>
                            @endforeach
                        </ul>
                    <br>
                    <h3>Comentarios</h3>
                    <hr>
                        <ul>
                            @foreach($comentarios as $comentario)
                                <li>Comentario: {{ $comentario->comentario }} De: {{ $comentario->userNombre }} Empresa; {{$comentario->empresaNombre}} </li>
                            @endforeach
                        </ul>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal para reportes de plato x mes-->
@php
$meses = array('enero'=>1, 'febrero'=>2, 'marzo'=>3, 'abril'=>4, 'mayo'=>5, 'junio'=>6, 'julio'=>7, 'agosto'=>8, 'septiembre'=>9, 'octubre'=>10, 'noviembre'=>11, 'diciembre'=>12);
@endphp
<div class="modal fade" id="RepMes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Gestion de Reportes</h4>
        </div>
        <div class="modal-body">
          <form class="" action="rporpedido" method="post">
            {{ csrf_field() }}
          <div class="form-group">
            <label for="mes">Seleccione un mes</label>
            <select name="mes" id="mes" class="form-control">
              @foreach($meses as $mesN => $mesT)
                <option value="{{$mesT}}" id="">{{$mesN}}</option>
              @endforeach
             </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Consultar</button>
        </form>
        </div>
      </div>
    </div>
  </div>
<!--Modal para reportes de plato x mes-->
@endsection
