

        @foreach ($ordenes as $row)
           

                <table class="" id="categoriastable">

                    <tr>
                        <td colspan="5" style="font-size: 14px; text-align: center;">Vicepresidencia comercial</td>
                        <td rowspan="2" ><!--img src="../assets/img/login.png" alt=""--></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="font-size: 14px; text-align: center;">Formato de Solicitud de Pedido</td>
                    </tr>

                    <tr>
                        <td colspan="6">
                            El presente formato aplica para solicitar un (1) pedido por cliente. Los campos marcados con (*) son obligatorios.
                        </td>
                    </tr>

                    <tr>
                        <td style="background: #203764;color:#ffffff; height: 50px;">Tipo de Solicitud (*)</td>
                        <td>Creación Pedido</td>
                        <td style="background: #203764;color:#ffffff; height: 50px;">No.pedido SAP (aplica para modificación/ eliminación de pedido)</td>
                        <td>{!! $row->referencia!!}</td>
                        <td style="background: #203764;color:#ffffff; height: 50px;">Fecha de entrega (*)</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td style="background: #203764;color:#ffffff; height: 50px;">Canal (*)</td>
                        <td>ALTERNATIVOS</td>
                        <td style="background: #203764;color:#ffffff; height: 50px;">Código Cliente (*)</td>
                        <td>{!! $row->cliente->cod_oracle_cliente!!}</td>
                        <td style="background: #203764;color:#ffffff; height: 50px;">Código CEDI</td>
                        <td></td>
                    </tr>


                     <tr>
                        <td>Nombre de Cliente (*)</td>
                        <td colspan="3">{!! $row->cliente->first_name.' '.$row->cliente->last_name!!}</td>
                        <td>Referencia Cliente</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>


                    <tr>
                        <td style="background: #203764;color:#ffffff; height: 50px;" >Código SKU (*)</td>
                        <td style="background: #203764;color:#ffffff; height: 50px;" >EAN13 / Código de barra (si aplica)</td>
                        <td style="background: #203764;color:#ffffff; height: 50px;" >Descripción SKU (*)</td>
                        <td style="background: #203764;color:#ffffff; height: 50px;" >Cantidad (*)</td>
                        <td style="background: #203764;color:#ffffff; height: 50px;" >Tipo Producto (*)</td>
                        <td style="background: #5B9BD5;color:#ffffff; height: 50px;" >Resultado (uso exclusivo Pedidos Alpina)</td>
                    </tr>


                    @foreach($row->detalles as $detalle)

                        
                        <tr>
                            <td>{!! $detalle->referencia_producto_sap!!}</td>
                            <td>{!! $detalle->referencia_producto!!}</td>
                            <td>{!! $detalle->nombre_producto!!}</td>
                            <td>{!! $detalle->cantidad!!}</td>
                            <td>Producto en Linea</td>
                            <td></td>
                        </tr>


                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="background: #5B9BD5;color:#ffffff;"  >ID pedido SAP (uso exclusivo Pedidos Alpina)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td  style="background: #5B9BD5;color:#ffffff;" >Valor pedido  (uso exclusivo Pedidos Alpina)</td>
                        <td></td>
                    </tr>
                        
                        
                </table>
           
                @endforeach
       
              