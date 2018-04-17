@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ $mensaje }}</h2> <span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span></div>
                	<div class="panel-body">
                            <!-- Carga de infos menus menuUpdate -->
                            <table class="table table-condensed">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Disponible</th>
                                    <th>Activo</th>
                                    <th>Menu</th>
                                    <th>Pedidos</th>
                                    <th>Entregados</th>
                                    <th>Operaciones</th>
                                </tr>
                                @foreach($menus as $menu)
                                <tr>
                                    <td>{{$menu->created_at}}</td>
                                    <td>{{$menu->publicar}}</td>
                                    <td>
                                        @if($menu->activo === 'no')

                                        <button class="btn btn-danger btn-xs">{{$menu->activo}}</button>

                                        @else

                                        <button class="btn btn-success btn-xs">{{$menu->activo}}</button>

                                        @endif
                                    </td>
                                    <td>{{$menu->nombre}}</td>
                                    <td>
                                        @php
                                        $apagar = false;
                                        $menuId = "/entrega/menu/".$menu->id;
                                            foreach($ordenesP AS $key => $value){
                                                if($key == $menu->id){
                                                    echo count($value);
                                                    if(count($value) == 0){
                                                      $apagar = true;
                                                      $menuId = '';
                                                    }
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            foreach($ordenesE AS $key => $value){
                                                if($key == $menu->id){
                                                    echo count($value);
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        <a @if($apagar) disabled @endif class="btn btn-info btn-xs" href="{{ $menuId }}" role="button"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                                        <a @if($menu->activo === 'no') disabled @endif class="btn btn-danger btn-xs"
                                           href="" role="button"
                                           onclick="event.preventDefault();
                                            document.getElementById('Update-{{ $menu->id }}').submit();">Cerrar</a>
                                        @if($menu->activo === 'no')
                                        <!--Apagado por directivas-->
                                        @else
                                        <form id="Update-{{ $menu->id }}" action="/menu/{{ $menu->id }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}{{ method_field('PUT') }} <input type="hidden" name="orden" value="cerrar">
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach


                            </table>
                        </div>
			</div>
		</div>
	</div>
</div>

@endsection
