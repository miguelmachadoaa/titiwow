 <h3>Respuestas </h3>
                            
                                
@foreach($comentarios as $c)


    @if($c->id_padre==0)


        <div class="media">
          <div class="media-left">
            <a href="#">
                @if(is_null($c->pic))
              <img class="media-object" src="{{secure_url('uploads/users/default.jpg')}}" alt="{{$c->first_name}}">
              @else
                <img class="media-object" src="{{secure_url('uploads/users/'.$c->pic)}}" alt="{{$c->first_name}}">
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
                    
                    <button class="btn btn-link responder" data-id="{{$c->id}}"><i class="fa fa-edit"></i></button>

                </div>
            </div>
           


          </div>
        </div>


    @endif



@endforeach