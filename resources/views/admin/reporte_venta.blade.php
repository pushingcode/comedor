@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $mensaje }}</div>
                <div class="panel-body">
                    <!--Panel-->
                    <h4>Periodo: {{$periodo}}</h4>
                    <!--Creando el reporte-->
                    <p>Cantidad de Ordens Procesadas: {{$totales}}</p>
                    <hr>
                    <h4>Reporte de Principales</h4>
                    @php
                      foreach($payLoadP as $plato => $cantidad){
                        echo "<p>Plato: ".$plato."<p>";
                        echo "<p>Servidas: ".$cantidad."<p>";
                        $calculo = ($cantidad * 100) / $totales;
                        echo"<div class='progress'>
                          <div class='progress-bar' role='progressbar' aria-valuenow='".$calculo."' aria-valuemin='0' aria-valuemax='100' style='width: ".$calculo."%;'>
                            ".ceil($calculo)."%
                          </div>
                        </div>";
                      }
                    @endphp
                    <hr>
                    <h4>Reporte de Contorno</h4>
                    @php
                      foreach($payLoadC1 as $plato => $cantidad){
                        echo "<p>controrno: ".$plato."<p>";
                        echo "<p>Servidas: ".$cantidad."<p>";
                        $calculo = ($cantidad * 100) / $totales;
                        echo"<div class='progress'>
                          <div class='progress-bar' role='progressbar' aria-valuenow='".$calculo."' aria-valuemin='0' aria-valuemax='100' style='width: ".$calculo."%;'>
                            ".ceil($calculo)."%
                          </div>
                        </div>";
                      }
                    @endphp
                    <hr>
                    <h4>Reporte de Contorno Adicional</h4>
                    @php
                      foreach($payLoadC2 as $plato => $cantidad){
                        echo "<p>controrno: ".$plato."<p>";
                        echo "<p>Servidas: ".$cantidad."<p>";
                        $calculo = ($cantidad * 100) / $totales;
                        echo"<div class='progress'>
                          <div class='progress-bar' role='progressbar' aria-valuenow='".$calculo."' aria-valuemin='0' aria-valuemax='100' style='width: ".$calculo."%;'>
                            ".ceil($calculo)."%
                          </div>
                        </div>";
                      }
                    @endphp
                    <!--Creando el reporte-->
                    <!--Panel-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
