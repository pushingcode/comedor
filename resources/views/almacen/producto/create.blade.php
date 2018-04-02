@extends ('layouts.admin')
@section ('contenido')
      <div class="row">
           <div class="col-lg-6 col-sm-6 col-xs-12">
                 <h3>Nuevo Producto</h3>
                 @if (count($errors)>0)
                 <div class="alert alert-danger">
                 <ul>
                 	@foreach ($errors->all() as $error)
                 	<li>{{$error}}</li>
                 	@endforeach
                 </ul>
                 	
                 </div>
           	     @endif
                 </div>
            </div>

           	     {!!Form::open(array('url'=>'almacen/producto','method'=>'POST','autocomplete'=>'off','files'=>'true'))!!}
           	     {{Form::token()}}
      <div class="row">
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
              <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" required value="{{old('nombre')}}" class="form-control" placeholder="Nombre...">                  
                 </div>
          </div>
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
            <label>Categoría</label>
            <select name="idcategoria" class="form-control">
               @foreach ($categorias as $cat)
               <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
               @endforeach 
            </select>
              
            </div>
          </div>
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="nombre">Existencia(Kg)</label>
                    <input type="text" name="existencia" required value="{{old('existencia')}}" class="form-control" placeholder="Existencia...">                  
                 </div>
          </div>
         
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="nombre">Carbohidrátos</label>
                    <input type="text" name="carbohidratos" value="{{old('carbohidratos')}}" class="form-control" placeholder="Carbohidrátos...">                  
                 </div>
          </div>
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="nombre">Proteínas</label>
                    <input type="text" name="proteinas" value="{{old('proteinas')}}" class="form-control" placeholder="Proteínas...">                  
                 </div>
          </div>
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="nombre">Grasas</label>
                    <input type="text" name="grasa" value="{{old('grasa')}}" class="form-control" placeholder="Grasas...">                  
                 </div>
          </div>
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="nombre">Calorías</label>
                    <input type="text" name="calorias" value="{{old('calorias')}}" class="form-control" placeholder="Calorías...">                  
                 </div>
          </div>
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <label for="imagen">Imagen</label>
                    <input type="file" name="imagen" class="form-control">                  
                 </div>
          </div>
          <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                    <button class="btn btn-primary" type="submit">Guardar</button>
                    <button class="btn btn-danger" type="reset">Cancelar</button>
                  
                 </div>
          </div>
      </div>
           	    
           	     {!!Form::close()!!}
         

@endsection   