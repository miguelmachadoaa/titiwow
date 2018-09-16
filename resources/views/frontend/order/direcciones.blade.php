<div class="col-sm-12">

@if(count($direcciones))

    <div class="form-group col-sm-10 col-sm-offset-1">

    @foreach($direcciones as $direccion)
               
        @if($direccion->default_address) 

        <?php $c='checked'; ?>

        @endif

        <!-- Se construyen las opciones de envios -->

        <div class="radio">

            <label>

                <input type="radio" name="id_direccion" class="custom-radio" id="id_direccion" value="{{ $direccion->id }}" {{ $c }}>

                <p>

                    <b>{{$direccion->nickname_address}}</b><br>

                    Direccion: {{$direccion->calle_address.' '.$direccion->calle2_address}}<br>

                    Codigo Postal: {{$direccion->codigo_postal_address}}<br>

                    Telefono: {{$direccion->telefono_address}}<br>

                    {{$direccion->country_name.', '.$direccion->state_name.', '.$direccion->city_name}}<br>

                </p>

            </label>

        </div>
   
        <?php $c=''; ?>

    @endforeach  

    </div>

@endif

</div>




<hr>
