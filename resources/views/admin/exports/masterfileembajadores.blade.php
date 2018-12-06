<table class="" id="categoriastable">
    <thead>
        <tr>
            <th ><b>TIPO_REGISTRO</b></th>
            <th><b> TIPO_CLIENTE</b></th>
            <th><b>EMPLEADO</b></th>
            <th><b>FECHA_INGRESO</b></th>
            <th><b>BILLTO_LOCATION</b></th>

            <th><b>CODIGO_CLIENTE</b></th>
            <th><b>CANAL_VENTAS</b></th>
            <th><b>NOMBRE_ORGANIZACION</b></th>
            <th><b>NOMBRES</b></th>
            <th><b>NUMERO_DOCUMENTO</b></th>
            <th><b>BUZON_PRICAT</b></th>
            <th><b>BUZON_CADENA</b></th>
            <th><b>FACTURA_KILOS</b></th>
            <th><b>TIPO_DOCUMENTO</b></th>
            <th><b>VALID_PRIMARIO</b></th>
            <th><b>DIGITO_VERIFICACION</b></th>
            <th><b>DIRECCION</b></th>
            <th><b>DIRECCION2</b></th>
            <th><b>NOMBRE_NEGOCIO</b></th>
            <th><b>DEPARTAMENTO</b></th>
            <th><b>CIUDAD</b></th>
            <th><b>BARRIO</b></th>
            <th><b>DIRECCION_EDI</b></th>
            <th><b>PLATAFORMA</b></th>
            <th><b>CARGUE_PLATAFORMA</b></th>
            <th><b>TIPOLOGIA</b></th>
            <th><b>AGRUPA_TIPOLOGIA</b></th>
            <th><b>METODO_RECORDATORIO</b></th>
            <th><b>CRITERIOS_DESPACHO</b></th>
            <th><b>CLASE_CONTRIBUYENTE</b></th>
            <th><b>CUENTA_COBRAR</b></th>
            <th><b>CUENTA_INGRESO</b></th>
            <th><b>TERMINO_PAGO</b></th>
            <th><b>SHIPTO_COMPANIA</b></th>
            <th><b>SHIPTO_PAIS</b></th>
            <th><b>SHIPTO_REGIONAL</b></th>
            <th><b>SHIPTO_AGENCIA</b></th>
            <th><b>SHIPTO_CANAL</b></th>
            <th><b>SHIPTO_MACROZONA</b></th>
            <th><b>SHIPTO_ZONA</b></th>
            <th><b>SHIPTO_DANE</b></th>
            <th><b>SHIPTO_VENDEDOR</b></th>
            <th><b>TIPO_PEDIDO_SHIP</b></th>
            <th><b>LISTA_PRECIOS_SHIP</b></th>
            <th><b>DEPOSITO_SHIPTO</b></th>
            <th><b>DELIVERTO_COMPANIA</b></th>
            <th><b>DELIVERTO_PAIS</b></th>
            <th><b>DELIVERTO_REGIONAL</b></th>
            <th><b>DELIVERTO_AGENCIA</b></th>
            <th><b>DELIVERTO_CANAL</b></th>
            <th><b>DELIVERTO_MACROZONA</b></th>
            <th><b>DELIVERTO_ZONA</b></th>
            <th><b>DELIVERTO_DANE</b></th>
            <th><b>DELIVERTO_VENDEDOR</b></th>
            <th><b>TIPO_PEDIDO_DELIVER</b></th>
            <th><b>LISTA_PRECIOS_DELIVER</b></th>
            <th><b>DEPOSITO_DELIVERTO</b></th>
            <th><b>CLASIFICACION_VENTAS</b></th>
            <th><b>CODIGO_PLATAFORMA</b></th>
            <th><b>NOMBRES_CONTACTO</b></th>
            <th><b>APELLIDOS_CONTACTO</b></th>
            <th><b>TELEFONO</b></th>
            <th><b>EMAIL</b></th>
            <th><b>Latitud</b></th>
            <th><b>Longitud</b></th>
            <th><b>Tipo RMA EDI</b></th>
            <th><b>Clasificación</b></th>
            <th><b>VALOR_CONTEXTO</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($usuarios as $row)
        <tr>
            <th ><b>I</b></th>
            <th><b> 2</b></th>
            <th><b>D</b></th>
            <th><b>{{ $row->fecha_ingreso }}</b></th>
            <th><b></b></th>

            <th><b></b></th>
            <th><b>EC</b></th>
            <th><b>{{ $row->first_name.' '.$row->last_name }}</b></th>
            <th><b>{{ $row->first_name.' '.$row->last_name }}</b></th>
            <th><b>{{ $row->doc_cliente }}</b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b>C</b></th>
            <th><b>I</b></th>
            <th><b></b></th>
            <th><b>{{ $row->abrevia_estructura.' '.$row->principal_address.' '.$row->secundaria_address.' '.$row->edificio_address     }}</b></th>
            <th><b></b></th>
            <th><b>{{ $row->first_name.' '.$row->last_name }}</b></th>
            <th><b>{{ $row->state_nme }}</b></th>
            <th><b>{{ $row->city_name }}</b></th>
            <th><b>{{ $row->barrio_address }}</b></th>
            <th><b> </b></th>
            <th><b> </b></th>
            <th><b> </b></th>
            <th><b>NUEVA</b></th>
            <th><b>NUEVA</b></th>
            <th><b>00</b></th>
            <th><b></b></th><!-- CRITERIOS DESPACHo-->
            <th><b>NONWITHHOLDER-NATIONAL</b></th>
            <th><b>10-00-130505-0000-0000-0000-000-00-0-0000</b></th>
            <th><b>10-10-412005-0000-1315-9130-000-00-0-0000</b></th>
            <th><b>1</b></th>
            <th><b>10</b></th>
            <th><b>CO</b></th>
            <th><b>1</b></th>
            <th><b>T05</b></th>
            <th><b>RT</b></th>
            <th><b>CR</b></th>
            <th><b>64</b></th>
            <th><b>0</b></th>
            <th><b>No Sales Credit</b></th>
            <th><b>T00_VTA_ESTANDAR CABANA</b></th>
            <th><b></b></th>
            <th><b>315</b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b>{{ $row->telefono_cliente }}</b></th>
            <th><b>{{ $row->email }}</b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b></b></th>
            <th><b>E</b></th>
          
        </tr>
        @endforeach
    </tbody>
</table>
                       