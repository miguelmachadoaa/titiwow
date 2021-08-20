 <h3>Respuestas </h3>
                            
                                
@foreach($comentarios as $c)


    @if($c->id_padre==0)


        <div class="media">
          <div class="media-left">
            <a href="#">
                @if(is_null($c->pic))
              <img class="media-object" style="width: 60px;" src="{{secure_url('/uploads/users/default.jpg')}}" alt="{{$c->first_name}}">
              @else
                <img class="media-object"style="width: 60px;"  src="{{secure_url('/uploads/users/'.$c->pic)}}" alt="{{$c->first_name}}">
              @endif
            </a>
          </div>
          <div class="media-body">
            <div class="row">
                
                <div class="col-sm-12">

                     <h4 class="media-heading">{{$c->first_name.' '.$c->last_name}}</h4>
            
                    {{$c->texto_ticket}}

                </div>

                <div class="col-sm-12">
                    
                    <button class="btn btn-link responder" data-ticket="{{$ticket->id}}" data-padre="{{$c->id}}"><i class="fa fa-edit"></i></button>

                </div>

                @if(is_null($c->archivo) || $c->archivo=='')

                        <div class="col-sm-12">
                            
                            <h3>Sin archivos Adjuntos </h3>

                        </div>


                        @else

                        <div class="col-sm-12">
                            <h3>Adjunto <a class="btn btn-info" target="_blank" href="{{secure_url('uploads/ticket/'.$c->archivo)}}">Ver Archivo </a> </h3>

                        </div>


                @endif 
            </div>
           


          </div>
        </div>



        @if(is_null($c->respuestas))

        @else

          @foreach($c->respuestas as $r)

            <div class="media" style="margin-left: 3em;">
          <div class="media-left">
            <a href="#">
                @if(is_null($r->pic))
              <img class="media-object" style="width: 60px;" src="{{secure_url('/uploads/users/default.jpg')}}" alt="{{$r->first_name}}">
              @else
                <img class="media-object"style="width: 60px;"  src="{{secure_url('/uploads/users/'.$r->pic)}}" alt="{{$r->first_name}}">
              @endif
            </a>
          </div>
          <div class="media-body">
            <div class="row">
                
                <div class="col-sm-12">

                     <h4 class="media-heading">{{$r->first_name.' '.$r->last_name}}</h4>
            
                    {{$r->texto_ticket}}

                </div>

                <div class="col-sm-12">
                    
                    <button class="btn btn-link responder" data-ticket="{{$ticket->id}}" data-padre="{{$r->id}}"><i class="fa fa-edit"></i></button>

                </div>

                @if(is_null($r->archivo) || $r->archivo=='')

                        <div class="col-sm-12">
                            
                            <h3>Sin archivos Adjuntos </h3>

                        </div>


                        @else

                        <div class="col-sm-12">
                            <h3>Adjunto <a class="btn btn-info" target="_blank" href="{{secure_url('uploads/ticket/'.$r->archivo)}}">Ver Archivo </a> </h3>

                        </div>


                        @endif 
            </div>
           


          </div>
        </div>




            @if(is_null($r->respuestas))

        @else

          @foreach($r->respuestas as $rr)

            <div class="media" style="margin-left: 6em;">
          <div class="media-left">
            <a href="#">
                @if(is_null($rr->pic))
              <img class="media-object" style="width: 60px;" src="{{secure_url('/uploads/users/default.jpg')}}" alt="{{$rr->first_name}}">
              @else
                <img class="media-object"style="width: 60px;"  src="{{secure_url('/uploads/users/'.$rr->pic)}}" alt="{{$rr->first_name}}">
              @endif
            </a>
          </div>
          <div class="media-body">
            <div class="row">
                
                <div class="col-sm-12">

                     <h4 class="media-heading">{{$rr->first_name.' '.$rr->last_name}}</h4>
            
                    {{$rr->texto_ticket}}

                </div>

                <div class="col-sm-12">
                    
                    <button class="btn btn-link responder" data-ticket="{{$ticket->id}}" data-padre="{{$rr->id}}"><i class="fa fa-edit"></i></button>

                </div>

                @if(is_null($rr->archivo) || $rr->archivo=='')

                        <div class="col-sm-12">
                            
                            <h3>Sin archivos Adjuntos </h3>

                        </div>


                        @else

                        <div class="col-sm-12">
                            <h3>Adjunto <a class="btn btn-info" target="_blank" href="{{secure_url('uploads/ticket/'.$rr->archivo)}}">Ver Archivo </a> </h3>

                        </div>


                        @endif 
            </div>
           


          </div>
        </div>


          @endforeach


        @endif


          @endforeach


        @endif


    @endif



@endforeach