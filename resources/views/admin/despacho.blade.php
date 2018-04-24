@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row"> 
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ $mensaje }}</h2> <span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span></div>
                            <div class="panel-body">
                                <form action="#">
                                    <div class="form-group">
                                        <label for="orden" class="col-md-4 control-label">Orden</label>

                                        <div class="col-md-6">
                                            <select id="orden" name="activo" class="form-control">
                                                <option value="no">Seleccione la orden por cliente</option>
                                                @foreach($ordenes as $orden)
                                                <option value="{{$orden->id}}">{{$orden->userName}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                
                                </form>
                            </div>
			</div>
		</div>
	</div>
</div>
    <!--modals-->
    @foreach($payloads as $key => $payload)
    
        <div class="modal fade" id="{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Gestion de Servicio</h4>
                </div>
                <div class="modal-body">
                    
                  <h2>Ticket de Servicio: {{$key}}</h2>
                  
                  @php
                    $data = explode("-", $payload);
                    echo "<h3>".$data[0]."</h3>";
                    echo "<h4>".$data[1]."</h4>";
                    echo "<hr>";
                    echo "<h3>".$data[2]."</h3>";
                    //echo "<h3>".$data[3]."</h3>";
                    //echo "<h3>".$data[4]."</h3>";
                  @endphp
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <a type="button" 
                     class="btn btn-success" 
                     onclick="event.preventDefault();
                     document.getElementById('editOrden-form').submit();">Entregar Orden</a>
                  <form id="editOrden-form" action="/ordenes/{{$key}}" method="POST" style="display: none;">
                      {{ csrf_field() }} {{ method_field('PUT') }} <input type="hidden" name="orden" value="cerrar">
                </form>
                  
                  <a type="button" 
                     class="btn btn-danger" 
                     onclick="event.preventDefault();
                     document.getElementById('deleteOrden-form').submit();">Anular Orden</a>
                <form id="deleteOrden-form" action="/ordenes/{{$key}}" method="POST" style="display: none;">
                      {{ csrf_field() }} {{ method_field('DELETE') }}
                </form>
                  
                </div>
              </div>
            </div>
          </div>
    @endforeach
    
    <script>
        $("#orden").bind("change", function () {
            
            @foreach($payloads as $key => $payload)
            if (this.value === '{{$key}}')
                $('#{{$key}}').modal('show'); 
            
            @endforeach
        }).change();
    </script>
    
@endsection