

<table class="" id="categoriastable">
    <tr>
        <td colspan="6"> Vicepresidencia comercial Formato de Solicitud de Pedido AlpinaGo - V1</td>
        <td > Logo Alpina</td>
        <td></td>
        <td ></td>
    </tr>
    <tr>
        <td colspan="6"> El presente formato aplica para solicitar un (1) pedido por cliente. Los campos marcados con (*) son obligatorios.</td>
        <td > </td>
        <td></td>
        <td ></td>
    </tr>
    <tr>
        <td colspan="7">
            
        </td>
        <td></td>
        <td ></td>
    </tr>
    <tr>
        <td>Tipo de Solicitud (*)</td>
        <td>Creación Pedido</td>
        <td>No.pedido SAP (aplica para modificación/ eliminación de pedido)</td>
        <td></td>
        <td>Fecha de entrega (*)</td>
        <td>{{$fecha}}</td>
        <td ></td>
        <td></td>
        <td ></td>
    </tr>
    <tr>
        <td>Canal (*)</td>
        <td>ALTERNATIVOS</td>
        <td>Código Cliente (*)</td>
        
        @if($rol==9)

            <td>8000007883 </td>


        @elseif($rol==10)

            <td>8000007884 </td>

        @elseif($rol==11)

            <td>8000007882  </td>

        @else

            <td>8000007882</td>

        @endif

        <!--td>1000030242</td-->
        <td>Código CEDI</td>
        <td></td>
        <td ></td>
        <td></td>
        <td ></td>
    </tr>

    <tr>
        <td>Nombre de Cliente (*)</td>
        <td colspan="3">WOOBSING</td>
        <td>Referencia Cliente</td>
        <td></td>
        <td ></td>
        <td></td>
        <td ></td>
    </tr>

    <tr>
        <td colspan="9" ></td>
    </tr>


        <tr>
            <td >Código SKU (*)</td>
            <td >EAN13 / Código de barra (si aplica)</td>
            <td >Descripción SKU (*)</td>
            <td>Cantidad (*)</td>
            <td>Tipo Producto (*)</td>
            <td >Resultado (uso exclusivo Pedidos Alpina)</td>
            <td >ID Pedido AlpinaGo(*)</td>
            <td>Combo (uso exclusivo pedidos Alpina)</td>
            <td>Resultado pedido AlpinaGo (uso exclusivo pedidos Alpina)</td>

        </tr>

        @foreach ($productos as $row)
        <tr>
            <td>{!! $row->sku_envio!!}</td>
            <td>{!! $row->sku_envio!!}</td>
            <td>{!! 'Envio Orden '.$row->referencia !!}</td>
            <td>{!! '1'!!}</td>
            <td>Producto de Línea</td>
            <td></td>
            <td>{!! $row->id_orden!!}</td>
            <td> </td>
            <td> </td>
           
          
        </tr>
        @endforeach
</table>
                       