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
                            <th>Producto</th>
                            <th>Existencia</th>
                        </tr>
                        @foreach($stocks as $stock)
                        <tr>
                            <td>{{ $stock->producto }}</td>
                            <td>{{ ceil($stock->cantidad/1000) }} Kg</td>
                        </tr>
                         @endforeach
                    </table>
                    <!--tabla-->
                    <!--Panel-->
                    {{$stocks->links()}}
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection