@extends ('layouts.admin')
@section ('contenido')
      <div class="row">
           <div class="col-lg-12col-md-12 col-sm-12 col-xs-12">
                 <h3>Nuevo Menú</h3>
                 @if (count($errors)>0)
                 <div class="alert alert-danger">
                 <ul>
                 	@foreach ($errors->all() as $error)
                 	<li>{{$error}}</li>
                 	@endforeach
                 </ul>
                 	
                 </div>
           	     @endif

           	     {!!Form::open(array('url'=>'menu/create','method'=>'POST','autocomplete'=>'off'))!!}
           	     {{Form::token()}}
            <div class="row">
                 <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
           	     <div class="form-group">
           	        <label for="nombre">Descripción</label>
           	        <input type="text" name="descripcion" class="form-control" placeholder="Descripción...">
           	     	</div>
           	     </div>
                 <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
           	     <div class="form-group">
           	        <label for="descripcion">Ingredientes</label>
           	        <input type="text" name="ingredientes" class="form-control" placeholder="Ingredientes...">
           	     	</div>
           	     </div>
            </div>
        <div class="row">
             <div class="panel panel-primary">
                 <div class="panel-body">
                     <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                        <div class="form-group">
                        <label>Productos</label>
                          <select name="pidproducto" class="form-control selectpicker" id="pidproducto" data-live-search="true">
                            @foreach($productos as $producto)
                            <option value="{{$producto->idproducto}}_{{$producto->existencia}}">{{$producto->producto}}</option>
                            @endforeach
                          </select>
                        </div>
                 </div> 
                 
                 <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                       <label for="cantidad">Cantidad</label>
                       <input type="number" name="pcantidad" id="pcantidad" class="form-control" placeholder="cantidad">
                      
                    </div>
                   
                 </div>
                 <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                       <label for="carbohidratos">Carbohidrátos</label>
                       <input type="number" disabled name="pcarbohidratos" id="pcarbohidratos" class="form-control" placeholder="Car">
                      
                    </div>
                   </div>
                  <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                       <label for="proteinas">Proteínas</label>
                       <input type="number" disabled name="pproteinas" id="pproteinas" class="form-control" placeholder="Pro">
                      
                    </div>
                   </div>
                   <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                       <label for="grasa">Grasas</label>
                       <input type="number" disabled name="pgrasa" id="pgrasa" class="form-control" placeholder="Gr">
                      
                    </div>
                   </div>
                   <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                       <label for="calorias">Calorías</label>
                       <input type="number" disabled name="pcalorias" id="pcalorias" class="form-control" placeholder="Cal">
                      
                    </div>
                   </div>
                   <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                       <label for="existencia">Existencia</label>
                       <input type="number" disabled name="pexistencia" id="pexistencia" class="form-control" placeholder="Exist">
                      
                    </div>
                   </div>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                         <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
                       <input type="number" name="pcantidad" id="pcantidad" class="form-control" placeholder="cantidad">
                      
                    </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                      <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                           <thead style="background-color:#A9D0F5">
                             <th>Opciones</th>
                             <th>Producto</th>
                             <th>Cantidad</th>
                             <th>Carbohidrátos</th>
                             <th>Proteínas</th>
                             <th>Grasas</th>
                             <th>Calorías</th>
                           </thead>
                          <tfoot>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><h4 id="total">0.00</h4> <input type="hidden" name="total_car" id="total_car"></th>
                          </tfoot>
                          <tbody>
                            
                          </tbody>
                      </table>
                      
                    </div>
                    </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-lg-12" id="guardar">
           	     <div class="form-group">
                     <input name="_token" value="{{ csrf_token() }}" type="hidden"></input>
           	        <button class="btn btn-primary" type="submit">Guardar</button>
           	        <button class="btn btn-danger" type="reset">Cancelar</button>
           	     	
           	     </div>
                 </div>
                 </div>
          

           	     {!!Form::close()!!}

@push ('scripts')
<script>
 $(document).ready(function()){
  $('#bt_add').click(function()){
    agregar();
  }),
 });

var cont=0;
total=0;

$("#guardar").hide();
$("#pidproducto").change(mostrarValores);

function mostrarValores()
{
  datosProducto=document.getElementById('pidproducto').value.split('__');
  $("#pcarbohidratos").val(datosProducto[2]);
  $("#pexistencia").val(datosProducto[1]);
}
function agregar()
{
  datosProducto=document.getElementById('pidproducto').value.split('__');

  idproducto=datosProducto[0];
  producto=$("#pidproducto option:select").text();
  cantidad=$("#pcantidad").val();
  carbohidratos=$("#pcarbohidratos").val();
  proteinas=$("#pproteinas").val();
  grasa=$("#pgrasa").val();
  calorias=$("pcalorias").val();
  existencia=$("pexistencia").val();

  if (idproducto!="" && cantidad!="" && cantidad>0 && carbohidratos!="" && proteinas!="" && grasa!="" && calorias!="")
}
  
  if (existencia>=cantidad)
  {
    total=total+carbohidratos[cont];

    var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idproducto[]" value="'+idproducto+'">'+producto+'</td><td><input type="number" name="cantidad[]" value="'+cantidad+'"></td><td><input type="number" name="carbohidratos[]" value="'+carbohidratos+'"></td></tr>';
    cont++;
    limpiar();
    $("#total").html(""+ total);
    $("#carbohidratos").val(total);
    evaluar();
    evaluar();
    $('#detalles').append(fila);
  }
  else
  {
    alert ('La cantidad supera la escitencia');
  }
}
else
{
  alert("Error al ingresar el detalle del menu, revise los datos del producto");
}
}
function limpiar(){
  $("#pcantidad").val("");
  $("#pcarbohidratos").val("");
  $("#pproteinas").val("");
  $("#pgrasa").val("");
  $("#pcalorias").val("");
   
}

function evaluar()
{
  if (total>0)
  {
    $("#guardar").show();
  }
  else
  {
    $("#guardar").hide();

  }
}
function eliminar(index){
  $("#carbohidratos").val(total);
  $("#fila" + index).remove();
  evaluar();
}
</script>
@endpush
@endsection   