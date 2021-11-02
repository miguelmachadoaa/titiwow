
@extends('layouts/default')

{{-- Page title --}}
@section('title')
Area clientes   
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

  <link rel="canonical" href="{{secure_url('clientes')}}" />

    <link rel="stylesheet" type="text/css" href="{{ secure_asset('assets/css/frontend/shopping.css') }}">
    <link href="{{ secure_asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css"/>

    <style type="text/css">
        
        .btn-medium {
            height: 160px;
            line-height: 160px;
            width: 160px;
            margin-bottom: 1em;
            display: inline-block;
            border: 1px solid rgba(0,0,0,0.1);
            background: #fff !important; 
            box-shadow: 2px 2px 2px #ddd;
            border-radius: 1em;
        }


        .btn-medium {
            text-decoration: none;
            color: #000;
            background-color: #26a69a;
            text-align: center;
            letter-spacing: .5px;
            transition: .2s ease-out;
            cursor: pointer;
        }

        .btn-medium i {
            font-size: 3.6rem;
        }

        h4 span {
    color: #007add;
}



    </style>
@stop

{{-- breadcrumb --}}
@section('top')
    <div class="breadcum">
        <div class="container">
            <ol class="breadcrumb">
                <li>
                    <a href="{{ secure_url('/') }}"> <i class="livicon icon3 icon4" data-name="home" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>Inicio
                    </a>
                </li>
                <li>
                    <i class="livicon icon3" data-name="angle-double-right" data-size="18" data-loop="true" data-c="#241F48" data-hc="#241F48"></i>
                    <a href="{{ secure_url('clientes') }}">Mi Perfil </a>
                </li>

                
            </ol>
        </div>
    </div>
@stop


{{-- Page content --}}
@section('content')
<div class="container contain_body">
    <div class="row">

        @if($user->confirma_email==0)

        <div class="alert alert-danger">
            Hola, recuerda confirmar tu correo, sigue el siguiente enlace para reenviar el correo de confirmación. 
            <a class="btn btn-link" href="{{secure_url('/reenviarcorreo/'.$user->token)}}">Reenviar Correo</a>
        </div>

        @endif

        
        
        <div class="alert alert-default text-center">
        @if(isset($cliente))


            @if($role->role_id==12)

            <div class="col-sm-9" style="text-align: center;"><h4> <span>{{$user->first_name.' '.$user->last_name}}</span> </h4>
        <h4> Perteneces al rol: <span>{{$rol->name}}</span></h4>

                <h4>Cliente afiliado por la empresa: <span> {{ $cliente->nombre_empresa }}</span>        </h4></div>
            <div class="col-sm-3">
                <img style="width:84px;border:2px solid #f5ecec;" src='{{secure_url('uploads/empresas/'.$cliente->imagen_empresa)}}'class='img-responsive' alt='Image'>
            </div>

            @endif

            @if($role->role_id==11)

            <h4>   <span>{{$user->first_name.' '.$user->last_name}}</span> </h4>
        <h4> Perteneces al rol:  <span>{{$rol->name}}</span> </h4>

                <h4>Usted es parte de los amigos alpina de:  <span> {{ $cliente->nombre_embajador }}</span></h4>

            @endif

            @if($role->role_id==10 || $role->role_id==14)

                <h4>Bienvenido a AlpinaGo, ya eres un Embajador Alpina. Invita a tus Amigos y familiares para empezar a disfrutar de nuestro producto.</h4>

                <h4>   <span><{{$user->first_name.' '.$user->last_name}}/span> </h4>
            <h4> Perteneces al rol:  <span>{{$rol->name}}</span> </h4>

             

            @endif

            @if($role->role_id==9)

            <h4>   <span>{{$user->first_name.' '.$user->last_name}}</span> </h4>
        <h4> Perteneces al rol: <span> {{$rol->name}}</span> </h4>

                 <h4>Bienvenido a AlpinaGo, Ya eres un Cliente AlpinaGo.</h4>

            @endif

        @else

            <h4>Bienvenido a Tu Perfil</h4>

        @endif


        </div>


     

         @if(isset($direccion->id))

        @else

         <div class="row">
            <div class="col-sm-12">
                
                <div class="alert alert-danger">
                    Antes de Continuar con el proceso de compra, por favor has <a href="{{secure_url('mi-cuenta')}}">click aqui</a> para actualizar tus datos y crea una dirección de envio <a href="{{secure_url('misdirecciones')}}"> Aqui</a>.
                </div>  

            </div>
        </div>


        @endif

        

        
        <div class="col-sm-12 " style="margin-top: 1em;">
            <div class="row">
            <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center">
                <a href="{{ secure_url('mi-cuenta') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-user"></i></div>
                    <div class="col-sm-12">Mi Cuenta</div>
                    </div>
            

                </a>
            </div>    
            <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 col-md-3 text-center">
                <a href="{{ secure_url('miscompras') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-shopping-cart"></i></div>
                    <div class="col-sm-12">Mis Compras</div>
                    </div>
            

                </a>
            </div>    
            <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center">
                <a href="{{ secure_url('misdirecciones') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-map"></i></div>
                    <div class="col-sm-12">Mi Dirección </div>
                    </div>
                </a>
            </div> 
            <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center">   
                <a href="{{ secure_url('productos') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-shopping-cart"></i></div>
                    <div class="col-sm-12">Comprar </div>
                    </div>
                </a>
            </div> 

            <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center">   
                <a href="{{ secure_url('bono') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-gift"></i></div>
                    <div class="col-sm-12">Tarjeta de Regalo </div>
                    </div>
                </a>
            </div>



                @if (Sentinel::getUser()->hasAnyAccess(['clientes.misamigos']))
                <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a  href="{{ secure_url('misamigos') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-users"></i></div>
                    <div class="col-sm-12">Mis Amigos</div>
                    </div>
                </a>
                </div> 
                @endif


                 @if($role->role_id==9 || $role->role_id==12)

                  <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a  href="{{ secure_url('convenios') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-industry"></i></div>
                    
                        <div class="col-sm-12">Mi Convenio</div>

                    </div>
                </a>
                </div> 

                @endif


                @if (Sentinel::getUser()->hasAnyAccess(['clientes.misamigos']))
                <!--div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a  href="{{ secure_url('miestatus') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-users"></i></div>
                    <div class="col-sm-12">Mi Estatus </div>
                    </div>
                </a>
                </div--> 
                </div> 
            <div class="row">
                @endif
                <div class="col-xs-6 col-sm-3 col-lg-2 col-md-3 text-center"> 
                <a href="{{ secure_url('paginas/faqs') }}" class=" btn-medium cajita">
                    <div class="row">
                    <div class="col-sm-12" style="height: 2em;" ><i class="fa fa-question-circle"></i></div>
                    <div class="col-sm-12">Preguntas</div>
                    </div>
                </a>
                </div> 
                
            </div>
        </div>


       


       
    </div>
</div>



@if(isset($direccion->barriomodal))

       <!-- Modal Direccion -->
       <div class="modal fade" id="barrioModal" role="dialog" aria-labelledby="modalLabeldanger" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-sucess">
                            <h4 class="modal-title" id="modalLabeldanger">Seleccione el barrio Para su Dirección</h4>
                    </div>
                    
                    <div class="modal-body cartcontenido">

                        <div class="row">

                        <div class="form-group col-sm-12">

                             <p> <b>Dirección: </b> {{ $direccion->country_name.', '.$direccion->state_name.', '.$direccion->city_name }} | {{ $direccion->nombre_estructura.' '.$direccion->principal_address.' - '.$direccion->secundaria_address.' '.$direccion->edificio_address.' '.$direccion->detalle_address.' '.$direccion->barrio_address }}    </p>
                       
                        </div>

                       

                    <input type="hidden" id="modal_id_dir" name="modal_id_dir" value="{{$direccion->id}}">

                    <div class="form-group {{ $errors->
                            first('modal_id_barrio', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                Barrios 
                            </label>
                            <div class="col-sm-5">
                                
                                 <select id="modal_id_barrio" name="modal_id_barrio" class="form-control select2 js-example-responsive">

                                    @foreach($barrios as $b)

                                    <option  @if($b->id == old('modal_id_barrio')) selected="selected" @endif    value="{{ $b->id }}">  {{ $b->barrio_name}}</option>
                                    @endforeach
                                    
                                  
                                </select>
                            </div>
                            <div class="col-sm-4 errorBarrio">
                                {!! $errors->first('modal_id_barrio', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>


                        </div>
                  
                   
                            
                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn  btn-primary updateBarrio" data-dismiss="modal">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>


@endif

<input type="hidden" id="base" name="base" value="{{secure_url('/')}}">

  
@endsection

<!-- Modal Direccion -->

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ secure_asset('assets/vendors/wow/js/wow.min.js') }}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            new WOW().init();
        });


        $('#modal_id_barrio').select2();

        $(".js-example-responsive").select2({
                                width: 'resolve'
                            });



        $(document).ready(function(){
            
            $('#barrioModal').modal('show', {backdrop: 'static', keyboard: false});
        });


        $('.updateBarrio').on('click', function(){

            id_barrio=$('#modal_id_barrio').val();
            id_dir=$('#modal_id_dir').val();
            base=$('#base').val();

            console.log('id barrio '+id_barrio);

            if(id_barrio==null || id_barrio==undefined || id_barrio==''){

                $('.errorBarrio').html('Todos los campos son obligatorios')


            }else{

                $.ajax({
                    type: "POST",
                    data:{  id_barrio, id_dir },

                    url: base+"/configuracion/setbarrio",
                        
                    complete: function(datos){     

                      //  $('#barrioModal').modal('hide');

                        //location.reload();

                    }

                });


            }

          

        });


    </script>
@stop