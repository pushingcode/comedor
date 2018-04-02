@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $mensaje }}</div>

                <div class="panel-body">
                    <!--Panel-->
                    <!--tabla-->
                    <table class="table table-condensed">
                        <tr>
                            <th>Empresa</th>
                            <th>RIF</th>
                            <th>Telefono</th>
                            <th>Cant. Empleados</th>
                            <th>Estado Activo</th>
                            <th></th>
                        </tr>
                        @foreach($empresas as $empresa)
                        <tr>
                            <td>{{ $empresa->nombre }}</td>
                            <td>{{ $empresa->rif }}</td>
                            <td>{{ $empresa->telefono }}</td>
                            <td>{{ $empresa->empleados }}</td>
                            <td>{{ $empresa->activo }}</td>
                            <td><a href="/empresa/{{ $empresa->id }}/edit">Editar</a></td>
                        </tr>
                         @endforeach
                    </table>
                    <!--tabla-->
                    <!--Panel-->
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection