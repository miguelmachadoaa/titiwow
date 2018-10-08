

        <br>    

            @if ($direcciones->count() >= 1)

                @foreach ($direcciones as $row)

                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="livicon" data-name="tasks" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i> {{ $row->nickname_address }}
                            </h3>
                            
                        </div>
                        <div class="panel-body">
                            <div class="box-body">
                                <dl class="dl-horizontal">

                                    <dt>Pais</dt>
                                    <dd>{{ $row->country_name }}</dd>

                                    <dt>Departamento</dt>
                                    <dd>{{ $row->state_name }}</dd>

                                    <dt>Ciudad</dt>
                                    <dd>{{ $row->city_name }}</dd>

                                    <dt>Direccion</dt>
                                    <dd>
                                       {{ $row->calle_address.' '.$row->calle2_address }}
                                    </dd>

                                    <dt>Codigo Postal</dt>
                                    <dd>{{ $row->codigo_postal_address }}</dd>

                                    <dt>Telefono</dt>
                                    <dd>{{ $row->telefono_address }}</dd>

                                    <dt>Notas</dt>
                                    <dd>{{ $row->notas }}</dd>

                                </dl>
                            </div>
                            <!-- /.box-body -->
                        </div>

                        <div class="panel-footer " style="text-align: right;">

                            <button data-id="{{ $row->id }}" type="button" class="btn btn-danger btn-xs deldir"><i class="fa fa-trash"></i></button>
                              
                          </div>


                        
                    </div>
                </div>

                @endforeach

            @else
                <div class="alert alert-danger">
                        <p>El Cliente aun no posee direcciones Registradas</p>
                    </div>
            @endif   
             
