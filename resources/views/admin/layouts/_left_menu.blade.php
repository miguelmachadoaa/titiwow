<ul id="menu" class="page-sidebar-menu">

    <li {!! (Request::is('admin/index1') ? 'class="active"' : '') !!}>
        <a href="{{  URL::to('admin/index1') }}">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#EF6F6C" data-hc="#EF6F6C"
               data-loop="true"></i>
            Escritorio
        </a>
    </li>
    <li class="{{ Request::is('admin/productos*') ? 'active' : '' }}">
        <a href="#">
            <i class="livicon" data-name="shopping-cart" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Catálogo</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/productos*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.productos.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Productos
                </a>
            </li>
            <li {!! (Request::is('admin/categorias*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.categorias.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Categorias
                </a>
            </li>
            <li {!! (Request::is('admin/marcas*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.marcas.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Marcas
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ Request::is('admin/formaspago*') ? 'active' : '' }}">
        <a href="#">
            <i class="livicon" data-name="gear" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
               data-loop="true"></i>
            <span class="title">Configuraciones</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/configuracion*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.configuracion.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Configuracion General 
                </a>
            </li>
        </ul>

         <ul class="sub-menu">
            <li {!! (Request::is('admin/estatus*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.estatus.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Estatus Envios
                </a>
            </li>
        </ul>

        <ul class="sub-menu">
            <li {!! (Request::is('admin/formaspago*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.formaspago.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Formas de Pago
                </a>
            </li>
        </ul>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/formasenvio*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.formasenvio.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Formas de Envio
                </a>
            </li>
        </ul>

        <ul class="sub-menu">
            <li {!! (Request::is('admin/rolenvios*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.rolenvios.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Formas de Envio para Roles
                </a>
            </li>
        </ul>

        <ul class="sub-menu">
            <li {!! (Request::is('admin/rolpagos*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.rolpagos.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Formas de Pago para Roles
                </a>
            </li>
        </ul>

        <ul class="sub-menu">
            <li {!! (Request::is('admin/impuestos*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.impuestos.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Impuestos
                </a>
            </li>
        </ul>

        <ul class="sub-menu">
            <li {!! (Request::is('admin/marcas*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.marcas.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Marcas
                </a>
            </li>
        </ul>

        <ul class="sub-menu">
            <li {!! (Request::is('admin/documentos*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.documentos.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Tipos de Documentos
                </a>
            </li>
        </ul>       

        <ul class="sub-menu">
            <li {!! (Request::is('admin/transportistas*') ? 'class="active"' : '') !!}>
                <a href="{!! route('admin.transportistas.index') !!}">
                    <i class="fa fa-angle-double-right"></i>
                    Transportistas
                </a>
            </li>
        </ul>

        

        
    </li>

   
    <li {!! (Request::is('admin/activity_log') ? 'class="active"' : '') !!}>
        <a href="{{  URL::to('admin/activity_log') }}">
            <i class="livicon" data-name="eye-open" data-size="18" data-c="#F89A14" data-hc="#F89A14"
               data-loop="true"></i>
           Log de Actividades
        </a>
    </li>
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
    <li {!! (Request::is('admin/tasks') ? 'class="active"' : '') !!}>
        <a href="{{ URL::to('admin/tasks') }}">
            <i class="livicon" data-c="#EF6F6C" data-hc="#EF6F6C" data-name="list-ul" data-size="18"
               data-loop="true"></i>
            Tareas
            <span class="badge badge-danger" id="taskcount">{{ Request::get('tasks_count') }}</span>
        </a>
    </li>
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
    <li {!! (Request::is('admin/clientes') || Request::is('admin/clientes/create') || Request::is('admin/user_profile') || Request::is('admin/clientes/*') || Request::is('admin/deleted_users') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="users" data-size="18" data-c="#F89A14" data-hc="#F89A14"
               data-loop="true"></i>
            <span class="title">Clientes</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/clientes') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/clientes') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Clientes
                </a>
            </li>
            <li {!! (Request::is('admin/clientes/create') ? 'class="active" id="active"' : '') !!}>
                <a href="{{ URL::to('admin/clientes/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Crear Nuevo Cliente
                </a>
            </li>
        </ul>
    </li>
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
    <!-- Menus generated by CRUD generator -->
    @include('admin/layouts/menu')
</ul>