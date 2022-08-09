<div class="row">

                <div class="col-sm-12 mb-2" style="margin-top: 1em;">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <a  data-id="dashboard" class=" btn btn-primary cajita" href="#"><i class="fa fa-home"></i></a>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control"  type="text">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                  
                </div>

                <div class="col-sm-12">
                    

                    @foreach($clientes as $c)

                    <div class="row @if($loop->index%2==0) odd @endif">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <p style="margin: 0">{{$c->first_name.' '.$c->last_name}}</p>
                            <p style="margin: 0">{{$c->email}}</p>
                            <p style="margin: 0">{{$c->telefono_cliente}}</p>
                        </div>
                        <div class="col-sm-2 ">
                            
                            <button class="btn btn-primary mt-2"><i class="fa fa-edit"></i></button>
                        </div>
                    </div>
                    @endforeach

                </div>

               
            </div>