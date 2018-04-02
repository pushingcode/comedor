@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ $mensaje }}</h2></div>
                <div class="panel-body">
                    <!--Datos de la empresa-->
                    @foreach($empresas as $empresa)
                    <h3>{{ $empresa->nombre }}</h3>
                    <p>RIF:{{ $empresa->rif }}</p>
                    <p>Tel:{{ $empresa->telefono }}</p>
                    @endforeach
                    <h4>Periodo: {{$preriodo}}</h4>
                    <!--Datos de consumo-->
                    <table class="table table-condensed">
                        <tr>
                            <th>Afiliado</th>
                            <th>Menu</th>
                            <th>Entregado</th>
                            <th>Servicio</th>
                            <th>Fecha</th>
                        </tr>
                        @foreach($ordenes as $orden)
                        <tr>
                            <td>
                                {{$orden[0]->nombreUser}}
                            </td>
                            <td>
                                {{$orden[0]->nombreMenu}}
                            </td>
                            <td>
                                {{$orden[0]->entregado}}
                            </td>
                            <td>
                                {{$orden[0]->MenuSeccion}}
                            </td>
                            <td>
                                {{$orden[0]->created_at}}
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