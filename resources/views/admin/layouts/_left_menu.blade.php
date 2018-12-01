<ul id="menu" class="page-sidebar-menu">

    <li {!! (Request::is('admin') ? 'class="active"' : '') !!}>
        <a href="{{  URL::to('admin') }}">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#EF6F6C" data-hc="#EF6F6C"
               data-loop="true"></i>
            Escritorio
        </a>
    </li>

    @if (Sentinel::getUser()->hasAnyAccess(['productos.*']) || Sentinel::getUser()->hasAnyAccess(['categorias.*']) ||Sentinel::getUser()->hasAnyAccess(['marcas.*']))

    <li class="{{ Request::is('admin/productos*') ? 'active' : '' }}">
        <a href="#">
            <i class="livicon" data-name="shopping-cart" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Catálogo</span>
            <span class="fa arrow"></span>
        </a>

        <ul class="sub-menu">
            @if (Sentinel::getUser()->hasAnyAccess(['productos.*']))

            <li {!! (Request::is('admin/productos*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.productos.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Productos
                </a>
            </li>
            @endif
            @if (Sentinel::getUser()->hasAnyAccess(['categorias.*']))

            

            <li {!! (Request::is('admin/categorias*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.categorias.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Categorias
                </a>
            </li>
            @endif
            @if (Sentinel::getUser()->hasAnyAccess(['marcas.*']))

            

            <li {!! (Request::is('admin/marcas*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.marcas.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Marcas
                </a>
            </li>
            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['inventario.*']))
            <li {!! (Request::is('admin/inventario*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.inventario.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Inventario
                </a>
            </li>
            @endif
        </ul>
    </li>

    @endif

    
    @if (Sentinel::getUser()->hasAnyAccess(['ordenes.*']))

     <li class="{{ Request::is('admin/ordenes*') ? 'active' : '' }}">
        <a href="#">
            <i class="livicon" data-name="shopping-cart" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Ordenes</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
           

             @if (Sentinel::getUser()->hasAnyAccess(['ordenes.recibidos']))
                <li {!! (Request::is('admin/ordenes*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.ordenes.recibidos') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Ordenes Recibidas
                </a>
            </li>
            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['ordenes.aprobados']))
                <li {!! (Request::is('admin/ordenes*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.ordenes.aprobados') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Ordenes Aprobadas
                </a>
            </li>
            @endif

             @if (Sentinel::getUser()->hasAnyAccess(['ordenes.facturados']))
                <li {!! (Request::is('admin/ordenes*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.ordenes.facturados') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Ordenes Facturadas
                </a>
            </li>
            @endif

             @if (Sentinel::getUser()->hasAnyAccess(['ordenes.enviados']))
                <li {!! (Request::is('admin/ordenes*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.ordenes.enviados') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Ordenes Enviados
                </a>
            </li>
            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['ordenes.empresas']))
                <li {!! (Request::is('admin/ordenes*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.ordenes.empresas') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Ordenes Empresas
                </a>
            </li>
            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['ordenes.consolidado']))
                <li {!! (Request::is('admin/ordenes*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.ordenes.consolidado') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Ordenes Consolidado del Dia
                </a>
            </li>
            @endif

             @if (Sentinel::getUser()->hasAnyAccess(['ordenes.index']))
                <li {!! (Request::is('admin/ordenes*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.ordenes.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Todas las Ordenes
                </a>
            </li>
            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['ordenes.descuento']))
                <li {!! (Request::is('admin/ordenes*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.ordenes.descuento') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Ordenes con Descuentos
                </a>
            </li>
            @endif
            
           
        </ul>
    </li>

    @endif


    @if (Sentinel::getUser()->hasAnyAccess(['envios.*']))

     <li class="{{ Request::is('admin/envios*') ? 'active' : '' }}">
        <a href="#">
            <i class="livicon" data-name="shopping-cart" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Envios</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            @if (Sentinel::getUser()->hasAnyAccess(['envios.index']))
                <li {!! (Request::is('admin/envios*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.envios.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Envios
                </a>
            </li>
            @endif
            
           
        </ul>
    </li>

    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['formaspago.*']) || Sentinel::getUser()->hasAnyAccess(['configuracion.*']) || Sentinel::getUser()->hasAnyAccess(['estatus.*']) || Sentinel::getUser()->hasAnyAccess(['formasenvio.*']) || Sentinel::getUser()->hasAnyAccess(['rolenvios.*']) || Sentinel::getUser()->hasAnyAccess(['rolpagos.*']) || Sentinel::getUser()->hasAnyAccess(['impuestos.*']) || Sentinel::getUser()->hasAnyAccess(['marcas.*']) || Sentinel::getUser()->hasAnyAccess(['menus.*']) || Sentinel::getUser()->hasAnyAccess(['transportistas.*']) || Sentinel::getUser()->hasAnyAccess(['sedes.*']) || Sentinel::getUser()->hasAnyAccess(['empresas.*']) || Sentinel::getUser()->hasAnyAccess(['cupones.*'])|| Sentinel::getUser()->hasAnyAccess(['documentos.*'])  )



    <li class="{{ Request::is('admin/formaspago*') ? 'active' : '' }}">
        <a href="#">
            <i class="livicon" data-name="gear" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Configuraciones</span>
            <span class="fa arrow"></span>
        </a>
        
    @if (Sentinel::getUser()->hasAnyAccess(['configuracion.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/configuracion*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.configuracion.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Configuracion General 
                </a>
            </li> 
        </ul>
    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['cupones.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/cupones*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.cupones.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Cupones
                </a>
            </li> 
        </ul>
    @endif
  
    @if (Sentinel::getUser()->hasAnyAccess(['estatus.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/estatus*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.estatus.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Estatus Ordenes
                </a>
            </li>
        </ul>
    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['estatuspagos.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/estatuspagos*') ? 'class="active"' : '') !!}>
                <a href="{!! secure_url('admin/estatuspagos') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Estatus Pagos
                </a>
            </li>
        </ul>
    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['estatusenvios.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/estatusenvios*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.estatusenvios.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Estatus Envios
                </a>
            </li>
        </ul>
    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['empresas.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/empresas*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.empresas.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Empresas
                </a>
            </li>
        </ul>
    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['formaspago.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/formaspago*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.formaspago.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Formas de Pago
                </a>
            </li>
        </ul>
    @endif



    @if (Sentinel::getUser()->hasAnyAccess(['formasenvio.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/formasenvio*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.formasenvio.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Formas de Envio
                </a>
            </li>
        </ul>
    @endif
     
    @if (Sentinel::getUser()->hasAnyAccess(['rolenvios.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/rolenvios*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.rolenvios.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Roles - Envios
                </a>
            </li>
        </ul>
    @endif
    @if (Sentinel::getUser()->hasAnyAccess(['rolpagos.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/rolpagos*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.rolpagos.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Roles - Pagos
                </a>
            </li>
        </ul>
    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['rolconfiguracion.*']))
        <!--<ul class="sub-menu">
            <li {!! (Request::is('admin/rolconfiguracion*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.rolconfiguracion.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Roles - Configuracion
                </a>
            </li>
        </ul>-->
    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['impuestos.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/impuestos*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.impuestos.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Impuestos
                </a>
            </li>
        </ul>
    @endif
    
    @if (Sentinel::getUser()->hasAnyAccess(['menus.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/menus*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.menus.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Menus
                </a>
            </li>
        </ul>
    @endif
    @if (Sentinel::getUser()->hasAnyAccess(['documentos.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/documentos*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.documentos.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Tipos de Documentos
                </a>
            </li>
        </ul>       

    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['transportistas.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/transportistas*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.transportistas.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Transportistas
                </a>
            </li>
        </ul>
    @endif


    @if (Sentinel::getUser()->hasAnyAccess(['sedes.*']))
        <ul class="sub-menu">
            <li {!! (Request::is('admin/sedes*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.sedes.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Sedes
                </a>
            </li>
        </ul>
    @endif

        

    </li>

    @endif

            @if (Sentinel::getUser()->hasAnyAccess(['activity_log.*']))
                <li {!! (Request::is('admin/activity_log') ? 'class="active"' : '') !!}>
                        <a href="{{  URL::to('admin/activity_log') }}">
                            <i class="livicon" data-name="eye-open" data-size="18" data-c="#F89A14" data-hc="#F89A14"
                               data-loop="true"></i>
                           Log de Actividades
                        </a>
                    </li>
            @endif
   
            @if (Sentinel::getUser()->hasAnyAccess(['inbox.*']))

            
    
    <li {!! (Request::is('admin/inbox') || Request::is('admin/compose') || Request::is('admin/view_mail') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="mail" data-size="18" data-c="#67C5DF" data-hc="#67C5DF"
               data-loop="true"></i>
            <span class="title">Email</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/inbox') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('admin/inbox') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Inbox
                </a>
            </li>
            <li {!! (Request::is('admin/compose') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('admin/compose') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Compose
                </a>
            </li>
            <li {!! (Request::is('admin/view_mail') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('admin/view_mail') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Single Mail
                </a>
            </li>
        </ul>
    </li>

    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['tasks.*']))

        <li {!! (Request::is('admin/tasks') ? 'class="active"' : '') !!}>
            <a href="{{ URL::to('admin/tasks') }}">
                <i class="livicon" data-c="#EF6F6C" data-hc="#EF6F6C" data-name="list-ul" data-size="18"
                   data-loop="true"></i>
                Tareas
                <span class="badge badge-danger" id="taskcount">{{ Request::get('tasks_count') }}</span>
            </a>
        </li>

    @endif


    @if (Sentinel::getUser()->hasAnyAccess(['users.*']))

        <li {!! (Request::is('admin/users') || Request::is('admin/users/create') || Request::is('admin/user_profile') || Request::is('admin/users/*') || Request::is('admin/deleted_users') ? 'class="active"' : '') !!}>
            <a href="#">
                <i class="livicon" data-name="user" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
                   data-loop="true"></i>
                <span class="title">Usuarios</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="sub-menu">
                <li {!! (Request::is('admin/users') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/users') }}">
                        <i class="fa fa-angle-double-right"></i>
                        Usuarios
                    </a>
                </li>
                <li {!! (Request::is('admin/users/create') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/users/create') }}">
                        <i class="fa fa-angle-double-right"></i>
                        Crear Nuevo Usuario
                    </a>
                </li>
                <li {!! ((Request::is('admin/users/*')) && !(Request::is('admin/users/create')) ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::route('admin.users.show',Sentinel::getUser()->id) }}">
                        <i class="fa fa-angle-double-right"></i>
                        Ver Perfil
                    </a>
                </li>
                <li {!! (Request::is('admin/deleted_users') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/deleted_users') }}">
                        <i class="fa fa-angle-double-right"></i>
                        Usuarios Eliminados
                    </a>
                </li>


            </ul>
        </li>

    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['clientes.*']))

     <li {!! (Request::is('admin/clientes') || Request::is('admin/clientes/create') || Request::is('admin/user_profile') || Request::is('admin/clientes/*') || Request::is('admin/deleted_users') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="users" data-size="18" data-c="#F89A14" data-hc="#F89A14"
               data-loop="true"></i>
            <span class="title">Clientes</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">

            @if (Sentinel::getUser()->hasAnyAccess(['clientes.index']))
                <li {!! (Request::is('admin/clientes') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/clientes') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Clientes
                </a>
            </li>
            @endif


            @if (Sentinel::getUser()->hasAnyAccess(['clientes.empresas']))
                <li {!! (Request::is('admin/clientes/empresas/list') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/clientes/empresas/list') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Clientes Empresas
                </a>
            </li>

            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['clientes.inactivos']))
                <li {!! (Request::is('admin/clientes/inactivos') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/clientes/inactivos') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Clientes Inactivo
                </a>
            </li>

            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['clientes.rechazados']))
                <li {!! (Request::is('admin/clientes/rechazados') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/clientes/rechazados') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Clientes Rechazados
                </a>
            </li>

            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['clientes.create']))

             <li {!! (Request::is('admin/clientes/create') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/clientes/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Crear Nuevo Cliente
                </a>
            </li>
            @endif 
        </ul>
    </li>

    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['clientes.*']))

        <li {!! (Request::is('admin/alpinistas') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="users" data-size="18" data-c="#F89A14" data-hc="#F89A14"
                data-loop="true"></i>
            <span class="title">Alpinistas</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/alpinistas') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/alpinistas') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Lista de Alpinistas
                </a>
            </li>
            <li {!! (Request::is('admin/alpinistas/create') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/alpinistas/cargar') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Cargar Alpinistas
                </a>
            </li>
            <li {!! (Request::is('admin/alpinistas/destroy') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/alpinistas/show') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Retirar Alpinistas
                </a>
            </li>
        </ul>
    @endif

    @if (Sentinel::getUser()->hasAnyAccess(['groups.*']))

    <li {!! (Request::is('admin/groups') || Request::is('admin/groups/create') || Request::is('admin/groups/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="users" data-size="18" data-c="#418BCA" data-hc="#418BCA"
               data-loop="true"></i>
            <span class="title">Grupos</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/groups') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/groups') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Group List
                </a>
            </li>
            <li {!! (Request::is('admin/groups/create') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/groups/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Add New Group
                </a>
            </li>
        </ul>
    </li>

    @endif
    @if (Sentinel::getUser()->hasAnyAccess(['reportes.*']))

    <li {!! (Request::is('admin/groups') || Request::is('admin/groups/create') || Request::is('admin/groups/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="table" data-size="18" data-c="#418BCA" data-hc="#418BCA"
               data-loop="true"></i>
            <span class="title">Reportes</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">

            @if (Sentinel::getUser()->hasAnyAccess(['reportes.usuarios']))

                 <li {!! (Request::is('admin/groups') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/reportes/registrados') }}">
                        <i class="fa fa-angle-double-right"></i>
                        Usuarios Registrados
                    </a>
                </li>
            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['reportes.clientes']))

                <li {!! (Request::is('admin/groups') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/reportes/ventas') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Ventas por cliente
                </a>
            </li>
            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['reportes.productos']))

                <li {!! (Request::is('admin/groups') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/reportes/productos') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Ventas por producto
                </a>
            </li>
            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['reportes.carritos']))
                <li {!! (Request::is('admin/groups') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/reportes/carrito') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Carritos Abandonados
                </a>
            </li>
            @endif

            @if (Sentinel::getUser()->hasAnyAccess(['reportes.financiero']))

                <li {!! (Request::is('admin/groups') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/reportes/financiero') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Financiero
                </a>
            </li>
            @endif

            

            
        </ul>
    </li>

    @endif



    @if (Sentinel::getUser()->hasAnyAccess(['blog.*']))

     
    <li {!! ((Request::is('admin/blogcategory') || Request::is('admin/blogcategory/create') || Request::is('admin/blog') ||  Request::is('admin/blog/create')) || Request::is('admin/blog/*') || Request::is('admin/blogcategory/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="comment" data-c="#F89A14" data-hc="#F89A14" data-size="18"
               data-loop="true"></i>
            <span class="title">Blog</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/blogcategory') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('admin/blogcategory') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Blog Category List
                </a>
            </li>
            <li {!! (Request::is('admin/blog') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('admin/blog') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Blog List
                </a>
            </li>
            <li {!! (Request::is('admin/blog/create') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('admin/blog/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Add New Blog
                </a>
            </li>
        </ul>
    </li>

    @endif
    
    @if (Sentinel::getUser()->hasAnyAccess(['cms.*']))

     
    <li {!! (( Request::is('admin/cms') ||  Request::is('admin/cms/create')) || Request::is('admin/cms/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="comment" data-c="#F89A14" data-hc="#F89A14" data-size="18"
               data-loop="true"></i>
            <span class="title">Páginas</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
           
            <li {!! (Request::is('admin/cms') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('admin/cms') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Lista de Páginas
                </a>
            </li>
            <li {!! (Request::is('admin/cms/create') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('admin/cms/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Nueva Página
                </a>
            </li>
        </ul>
    </li>

    @endif
   
   
   
    <!-- Menus generated by CRUD generator -->
    @include('admin/layouts/menu')
</ul>