@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $mensaje }}</div>

                <div class="panel-body">
                    @if(count($menus) === 0)
                    <h4>No Existen Planificaciones en este momento</h4>
                    @else
                    <!--Panel-->
                    @foreach($menus as $menu)
                    <form class="form-inline" action="/menu/activate/{{ $menu->id }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="nombre">Menu: </label>
                                <input name="nombre" class="form-control" value="" placeholder="{{ $menu->nombre }}">
                        </div> 
                        <button type="submit" class="btn btn-success">Activar</button>
                        <br>
                        <p class="info">Peridodo de servicio: {{ $menu->servicioPlan }}</p>
                        @foreach($planes as $plan)
                            @if($menu->idPlanes == $plan[0]->idProduccion)
                            <p>Nombre de la receta: {{ $plan[0]->nombre }}</p>
                            <p>Tipo de Plato: {{ $plan[0]->tipo }}</p>
                            <button type="button" class="btn btn-sm btn-danger" data-placement="left" data-html="true" data-toggle="popover" title="{{ $plan[0]->nombre }}" data-content='
                                    @php
                                        $receta = json_decode($plan[0]->receta, true);
                                        $br = "<br>";
                                    @endphp
                                        @foreach($receta as $values)
                                            @foreach($values as $value)
                                            <strong>{{$value['nombre']}}</strong> Cantidad: {{$value['cantidad']}}gr.<br>
                                            <small>Proteinas {{$value['proteinas']}}<br>Grasas: {{$value['grasas']}}<br>Carbohidratos: {{$value['carbohidratos']}}<br>Calorias: {{$value['calorias']}}<br></small>
                                            @endforeach
                                        @endforeach
                                    '>ver receta</button>
                            @endif
                        @endforeach
                    </form>
                    <hr>
                    @endforeach
                    <!--Panel-->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection