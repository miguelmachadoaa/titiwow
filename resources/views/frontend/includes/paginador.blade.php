        @if(count($productos)>0)
<div class="row">
            <div class="col-md-3 text-left align-bottom">Productos {{($productos->currentpage()-1)*$productos->perpage()+1}} a  
                @if(($productos->currentpage()*$productos->perpage())>=$productos->total()) 

                    {{$productos->total()}}

                        

                @else

                    {{$productos->currentpage()*$productos->perpage()}}

                @endif


                  
                de  {{$productos->total()}}
            </div>
            <div class="col-md-9 text-right">
                {{ $productos->links() }}
            </div>
        </div>
        @endif
 

  
