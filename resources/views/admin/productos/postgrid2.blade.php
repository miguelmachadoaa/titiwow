


@if(isset($data))
<label for="">Codigo Html</label>
<textarea style="width: 100%" name="data" id="data" cols="30" rows="10">
    
    {{$data}}

</textarea>

@endif

<table style="text-align: center;">
    <tr>
        <td>
            
            <table align="center" width="600" style="width: 600px;">
                <tr>
                    <td>

                        <table style="background: #ebe9e9;  width: 100%">

                            @foreach($prods as $pro)

                            @if($loop->iteration%2!=0)

                            <tr>
                                <td style=" text-align: center; padding: 2em;">
                                    <div>
                                        <p style="    float: initial;    background: #fff;    font-weight: 800;    font-size: 1.5em;    margin: 0;    margin-top: 1em;    padding-top: 1em;">{{$pro->nombre_producto}}</p>
                                    </div>
                                    
                                    <div style="">
                                    <img src="{{secure_url('uploads/productos/'.$pro->imagen_producto)}}" alt="">

                                     @if($pro->precio_oferta==$pro->precio_base)

                                    <p style="             font-weight: 800;    font-size: 1.5em;    text-align: center; background: #fff;   margin: 0;">{{$pro->precio_base}}</p>

                                    @else
                                    <p style="         font-weight: 800;    font-size: 1.5em;    text-align: center; background: #fff;   margin: 0;  ">{{$pro->precio_base}}</p>
                                    <p style="      font-weight: 800;    font-size: 1.5em;    text-align: center;  
                                background: #fff;    margin: 0;    padding-top: 1em;    padding: 1em;">{{$pro->precio_oferta}}</p>
                                    @endif
                                        
                                    </div>
                                </td>

                            @else

                            <td style=" text-align: center; padding: 2em;">
                                    <p style="float: initial;    background: #fff;    font-weight: 800;    font-size: 1.5em;    margin: 0;
                                margin-top: 1em;    padding-top: 1em;">{{$pro->nombre_producto}}</p>
                                   
                                   <div style="">
                                    <img src="{{secure_url('uploads/productos/'.$pro->imagen_producto)}}" alt="">

                                    @if($pro->precio_oferta==$pro->precio_base)

                                    <p style="        font-weight: 800;    font-size: 1.5em;    text-align: center;   background: #fff;   margin: 0;">{{$pro->precio_base}}</p>

                                    @else

                                    <p style="        font-weight: 800;    font-size: 1.5em;    text-align: center;  background: #fff;    margin: 0;">{{$pro->precio_base}}</p>
                                    <p style="           font-weight: 800;    font-size: 1.5em;    text-align: center;  
                                background: #fff;    margin: 0;    padding-top: 1em;    padding: 1em;">{{$pro->precio_oferta}}</p>

                                    @endif
                                        
                                    </div>
                                </td>
                            </tr>

                            @endif

                            @if($loop->last%2==0)

                            @else
                            <td></td>
                            <tr>

                            @endif

                            @endforeach

                            </table>
                    </td>
                </tr>
                
            </table>

        </td>
    </tr>
</table>



