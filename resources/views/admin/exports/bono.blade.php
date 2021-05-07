<table class="" id="categoriastable">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>{{$cliente->first_name.' '.$cliente->last_name}}</th>
        </tr>

        <tr>
            <th>Email</th>
            <th>{{$cliente->email}}</th>
        </tr>


        <tr>
            <th>Disponible</th>
            <th>{{$disponible->total}}</th>
        </tr>
    </thead>
</table>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Monto</th>

      <th>Origen</th>

      <th>Ususario</th>

      <th>Fecha</th>

    </tr>
  </thead>

    <tbody>

        @foreach ($history as $h)
        <tr>
             <td>{{$h->id}}</td>
            <td>{{$h->valor_abono}}</td>
            <td>{{$h->origen}}</td>
            <td>{{$h->id_cliente}}</td>
            <td>{{$h->created_at}}</td>
            
        </tr>
        @endforeach
    </tbody>
</table>
                       