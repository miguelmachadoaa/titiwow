@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Editar Sedes
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        Editar Sedes
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ secure_url('admin') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Inicio
            </a>
        </li>
        <li>Sedess</li>
        <li class="active">Editar</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Editar Sedes
                    </h4>
                </div>
                <div class="panel-body">
                    
                        {!! Form::model($sedes, ['url' => secure_url('admin/sedes/'. $sedes->id), 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            <!-- CSRF Token -->
                            {{ csrf_field() }}
                          
                             <div class="form-group {{ $errors->
                            first('nombre_sede', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Nombre Sedes
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="nombre_sede" name="nombre_sede" class="form-control" placeholder="Nombre de Sedes"
                                       value="{!! old('nombre_sede', $sedes->nombre_sede) !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('nombre_sede', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('descripcion_sede', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Descaripcion Sedes
                            </label>
                            <div class="col-sm-5">
                                

                                <textarea class="form-control resize_vertical" id="descripcion_sede" name="descripcion_sede" placeholder="Descripcion Sedes" rows="5">{!! old('descripcion_sede', $sedes->descripcion_sede) !!}</textarea>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('descripcion_sede', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                            <div class="form-group col-sm-12">
                                <label for="coords"  class="col-sm-2 control-label">Ubicación
                                </label>
                                <div class="col-sm-5">
                                    <input class="form-control" type="text" name="coords" id="coords" placeholder="(Longitud, Latitud)" value="({{ $sedes->latitud_sede.','.$sedes->longitud_sede }})">
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                              <div id="map" style="height: 400px; width: 100%; "></div>


                            </div>

                            <!-- ubicacion de la sede   -->


                       <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <button type="submit" class="btn btn-success">
                                    Actualizar
                                </button>
                                <a class="btn btn-danger" href="{{ route('admin.sedes.index') }}">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>

@stop

@section('footer_scripts')



<script>
// Initialize and add the map
function initMap() {
 map = new google.maps.Map(document.getElementById('map'), {
     zoom: 8,
     center: {lat: {{ $sedes->latitud_sede }}, lng: {{ $sedes->longitud_sede }}}
   });
   
   var marker=new google.maps.Marker({
      position:map.getCenter(), 
      map:map, 
      draggable:true
   });
      
   google.maps.event.addListener(marker,'dragend',function(event) {
      document.getElementById("coords").value = this.getPosition().toString();
   });
}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpHwisz-ifc6C95v8SOVXDfWvIOMqFlfI&callback=initMap">
    </script>

@stop



