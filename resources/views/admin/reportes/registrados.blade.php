@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
Reportes Clientes Registrados
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>Reporte de Clientes Registrados</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Inicio
            </a>
        </li>
        <li><a href="#"> Reportes </a></li>
        <li class="active">Clientes Registrados</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Reportes Clientes Registrados
                    </h4>
                </div>
                <br />
                <div class="panel-body">
                    <div class="form-group col-sm-10 ">
                        <label for="select21" class="col-md-4 control-label text-right">
                        Fecha de Registro                                                 
                        </label>
                        <div class="col-md-8">   
                            <select id="fechas" name="fechas" class="form-control ">
                                <option value="">Seleccione</option>
                                <option id="1" value="1">Hoy</option>
                                <option id="2" value="2">Ayer</option>
                                <option id="2" value="2">Semana</option>
                                <option id="2" value="2">Mes</option>
                            </select>
                        </div>           
                    </div>

                    <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <a href="{{ secure_url('admin/reportes/registrados/export') }}" class="btn btn-md btn-primary" id="generar_report" target="_blank">
                                    Descargar productos en Excel
                                </a>
                                <a class="btn btn-md btn-danger" href="{{ route('admin.sedes.index') }}">
                                    Cancelar
                                </a>
                            
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




@stop
@section('footer_scripts')

<script>
        
   
</script>
@stop
