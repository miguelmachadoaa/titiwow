@if(count($cities))

    <table class="table table-responsive">
        <thead>
            <tr>
                <td>Ciudad</td>
                <td>Accion</td>
            </tr>
        </thead>
        <tbody>

    @foreach($cities as $ciudad)
       <tr>
           <td>{{ $ciudad->state_name.' - '.$ciudad->city_name }}</td>
           <td>
               <button data-id="{{ $ciudad->id }}" type="button" class="btn btn-danger btn-xs delCiudad"><i class="fa fa-trash"></i></button>
           </td>
       </tr>        

    @endforeach  

  </tbody>
    </table>

@endif


<hr>
