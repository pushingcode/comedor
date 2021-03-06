@extends('layouts.app')

@section('content')
@php
$meses = array('enero'=>1, 'febrero'=>2, 'marzo'=>3, 'abril'=>4, 'mayo'=>5, 'junio'=>6, 'julio'=>7, 'agosto'=>8, 'septiembre'=>9, 'octubre'=>10, 'noviembre'=>11, 'diciembre'=>12);
@endphp
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ $mensaje }}</h2></div>
                    <div class="panel-body">
                        <form action="/semanal/empresas" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="empresa" class="col-md-4 control-label">Seleccione la empresa: </label>
                               <div class="col-md-6">
                                   <select class="form-control" name="empresa" id="empresa">
                                       <option value="">Seleccione la Empresa</option>
                                       @foreach($selsectEmpresa as $key => $value)
                                       @php
                                        $key = explode("*",$key);
                                       @endphp
                                        <option value="{{ $key[0] }}">{{ $key[2] }} {{ $key[1] }}</option>
                                       @endforeach
                                   </select>
                               </div>

                           </div>
                            <br><br>
                            <hr>
                            <!--<input class="form-control btn btn-info" type="submit" value="Generar Reporte">-->
                        </form>
                    </div>
            </div>
	</div>
    </div>
</div>

    @foreach($idJs as $k =>$v )
    <div class="modal fade" id="{{$k}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Gestion de reportes para {{$v}}</h4>
            </div>
            <div class="modal-body">
                 <form method="POST" action="/mensual/cargar/reporte/{{$k}}" class="form-inline">
                      {{ csrf_field() }}
                    <h4>Reporte Mensual</h4>
                    <div class="form-group">
                      <label for="mes">Seleccione un mes</label>
                      <select name="mes" id="mes" class="form-control">
                        @foreach($meses as $mesN => $mesT)
                          <option value="{{$mesT}}" id="">{{$mesN}}</option>
                        @endforeach
                       </select>
                    </div>
                    <button type="submit" class="btn btn-default">Cargar Reporte</button>
                 </form>
                <hr>
                <h4>Reporte Semanal</h4>
                <form id="rep-{{$k}}" action="cargar/reporte/{{$k}}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                 <label for="rango" class="col-md-4 control-label">Seleccione: </label>
                        <div class="col-md-6">
                            <select class="form-control" name="rango" id="rango">
                                @foreach($selsectEmpresa as $key => $values)
                                  <!--paso A-->
                                 @php
                                  $key = explode("*",$key);
                                 @endphp

                                 @if($k == $key[0])
                                 <!--paso B-->
                                  @foreach($values as $value)
                                      <option value="{{$k}}*{{$value[0]}}*{{$value[1]}}">Semana del {{$value[0]}} al {{$value[1]}}</option>
                                  @endforeach

                                 @endif

                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
                <br>
                <br>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button onclick="event.preventDefault();
                     document.getElementById('rep-{{$k}}').submit();" type="button" class="btn btn-primary">Cargar Reporte</button>
            </div>
          </div>
        </div>
      </div>
    @endforeach
    <script>
        $("#empresa").bind("change", function () {
            
            @foreach($idJs as $key => $value)
            if (this.value === '{{$key}}')
                $('#{{$key}}').modal('show'); 
            
            @endforeach
        }).change();
    </script>
@endsection