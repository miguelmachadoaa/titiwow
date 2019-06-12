

<table class="" id="categoriastable">
    <tr>
        <td colspan="6"> Vicepresidencia comercial Formato de Solicitud de Pedido AlpinaGo - V1</td>
        <td > Logo Alpina</td>
    </tr>
    <tr>
        <td colspan="6"> El presente formato aplica para solicitar un (1) pedido por cliente. Los campos marcados con (*) son obligatorios.</td>
        <td > </td>
    </tr>
    <tr>
        <td colspan="7">
            
        </td>
    </tr>
    <tr>
        <td>Tipo de Solicitud (*)</td>
        <td>Creación Pedido</td>
        <td>No.pedido SAP (aplica para modificación/ eliminación de pedido)</td>
        <td></td>
        <td>Fecha de entrega (*)</td>
        <td>{{$fecha}}</td>
        <td ></td>
    </tr>
    <tr>
        <td>Canal (*)</td>
        <td>ALTERNATIVOS</td>
        <td></td>
        <td>1000030242</td>
        <td></td>
        <td></td>
        <td ></td>
    </tr>

    <tr>
        <td>Nombre de Cliente (*)</td>
        <td colspan="3">WOOBSING</td>
        <td>Referencia Cliente</td>
        <td></td>
        <td ></td>
    </tr>

    <tr>
        <td colspan="7" ></td>
    </tr>


        <tr>
            <th ><b>SKU</b></th>
            <th ><b>EAN</b></th>
            <th ><b>Producto</b></th>
            <th><b>Cantidad</b></th>
            <th><b>Tipo Producto</b></th>
            <th ><b>Id Pedido Alpina</b></th>
            <th ><b>Resultado</b></th>

        </tr>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->referencia_producto!!}</td>
            <td>{!! $row->referencia_producto_sap!!}</td>
            <td>{!! $row->nombre_producto !!}</td>
            <td>{!! $row->cantidad !!}</td>
            <td>Producto de Línea</td>
            <td>{!! $row->id_orden!!}</td>
            <td> </td>
           
          
        </tr>
        @endforeach
</table>
                       