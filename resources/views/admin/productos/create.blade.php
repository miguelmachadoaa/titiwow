
@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Nuevo Producto
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    
    <link href="{{ asset('assets/vendors/acc-wizard/acc-wizard.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/accordionformwizard.css') }}" rel="stylesheet" />
    
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
                <!--section starts-->
                <h1>Nuevo Producto</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#">Productos</a>
                    </li>
                    <li class="active">Nuevo Producto</li>
                </ol>
            </section>
            <!--section ends-->
<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="livicon" data-name="gear" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Specials
                </h3>
                <span class="pull-right">
                             <i class="glyphicon glyphicon-chevron-up clickable"></i>
                             <i class="glyphicon glyphicon glyphicon-remove removepanel clickable"></i>
                        </span>
            </div>
            <div class="panel-body">
            {!! Form::open(['url' => 'admin\productos', 'class' => 'form-horizontal']) !!}

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="row acc-wizard">
                    <div class="col-md-3 pd-2">
                        <p class="mar-2">
                            Follow the steps below to add an accordion wizard to your web page.
                        </p>
                        <ol class="acc-wizard-sidebar">
                            <li class="acc-wizard-todo acc-wizard-active">
                                <a href="#divbasicos">Datos Basicos</a>
                            </li>
                            <li class="acc-wizard-todo">
                                <a href="#addwizard">Descripción</a>
                            </li>
                            <li class="acc-wizard-todo">
                                <a href="#adjusthtml">Adjust HTML</a>
                            </li>
                            <li class="acc-wizard-todo">
                                <a href="#viewpage">Release</a>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-9 pd-r">
                        <div id="accordion-demo" class="panel-group">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#divbasicos" data-parent="#accordion-demo" data-toggle="collapse">Datos Básicos</a>
                                    </h4>
                                </div>
                                <div id="divbasicos" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <form id="form-divbasicos" class="form-horizontal">
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="nombre_producto">Nombre del Producto</label>
                                                <div class="col-md-9">
                                                    <input id="nombre_producto" name="nombre_producto" type="text" placeholder="Your name" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Referencia</label>
                                                <div class="col-md-9">
                                                    <input id="referencia_producto" name="referencia_producto" type="text" placeholder="Your email" class="form-control"></div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 control-label" for="referencia_producto">Referencia</label>
                                                <div class="col-md-9">
                                                    <input id="referencia_producto" name="referencia_producto" type="text" placeholder="Your email" class="form-control"></div>
                                            </div>
                                            <div class="acc-wizard-step"></div>
                                        </form>
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#prerequisites --> </div>
                            <!-- /.panel.panel-default -->

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#addwizard" data-parent="#accordion-demo" data-toggle="collapse">Descripción</a>
                                    </h4>
                                </div>
                                <div id="addwizard" class="panel-collapse collapse awd-h" style="height: 36.400001525878906px;">
                                    <div class="panel-body">
                                        <form id="form-addwizard">
                                        <div class="form-group clearfix">
                                            <label class="col-md-3 control-label" for="descripcion_corta">Descripción Corta</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control resize_vertical" id="descripcion_corta" name="descripcion_corta" placeholder="Please enter your message here..." rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <label class="col-md-3 control-label" for="descripcion_larga">Descripción Larga</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control resize_vertical" id="descripcion_larga" name="descripcion_larga" placeholder="Please enter your message here..." rows="5"></textarea>
                                            </div>
                                        </div>
                                            <div class="acc-wizard-step"></div>
                                        </form>
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#addwizard --> </div>
                            <!-- /.panel.panel-default -->
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#adjusthtml" data-parent="#accordion-demo" data-toggle="collapse">Adjust HTML Markup</a>
                                    </h4>
                                </div>
                                <div id="adjusthtml" class="panel-collapse collapse" style="height: 36.400001525878906px;">
                                    <div class="panel-body">
                                        <form id="form-adjusthtml">
                                            <p>
                                                Now you can modify your HTML markup to activate the accordion wizard. There are two parts to the markup—the collapsible accordion itself and the task list. I prefer putting both in the same
                                                <code>.row</code>
                                                with the task list taking up a
                                                <code>.col-md-3</code>
                                                and the accordion panels in a
                                                <code>.col-md-9</code>
                                                , but that's not a requirement.
                                            </p>
                                            <p>
                                                The accordion panel can be exactly as documented in the
                                                <a href="http://getbootstrap.com/javascript/#collapse-examples">Bootstrap example</a>
                                                , but I think there's a problem with the Bootstrap implementation. Specifically, the Bootstrap example only adds the class
                                                <code>.in</code>
                                                to one of the accordion panels. That class marks the panel as visible by default. The problem with only having one panel visible by default is that users without javascript will
                                                <strong>never</strong>
                                                be able to see the other panels. Sure, that's a minority of users, but why make your pages unworkable even for a small minority. Instead, I suggest adding
                                                <code>.in</code>
                                                to all your
                                                <code>.collapse</code>
                                                elements and have javascript code select only one to make visible when it runs. The accordion wizard javascript will handle that for you if you choose to use that approach.
                                            </p>
                                            <p>
                                                The sidebar task list is nothing but a standard HTML ordered list. The only required additions are adding the
                                                <code>.acc-wizard-sidebar</code>
                                                class to the
                                                <code>&lt;ol&gt;</code>
                                                element and
                                                <code>.acc-wizard-todo</code>
                                                to the individual list items. If you want to indicate that some steps are already complete, you can instead add the
                                                <code>.acc-wizard-completed</code>
                                                class to the corresponding
                                                <code>&lt;li&gt;</code>
                                                elements.
                                            </p>
                                                        <pre>&lt;ol class="acc-wizard-sidebar"&gt;
                                                              &lt;li class="acc-wizard-todo"&gt;&lt;a href="#prerequisites"&gt;Install Bootstrap and jQuery&lt;/a&gt;&lt;/li&gt;
                                                              &lt;li class="acc-wizard-todo"&gt;&lt;a href="#addwizard"&gt;Add Accordion Wizard&lt;/a&gt;&lt;/li&gt;
                                                              &lt;li class="acc-wizard-todo"&gt;&lt;a href="#adjusthtml"&gt;Adjust Your HTML Markup&lt;/a&gt;&lt;/li&gt;
                                                              &lt;li class="acc-wizard-todo"&gt;&lt;a href="#viewpage"&gt;Test Your Page&lt;/a&gt;&lt;/li&gt;
                                                                &lt;/ol&gt;
                                                        </pre>
                                            <p>
                                                Finally, you'll want to active the wizard in your javascript. That's nothing more than simply calling the plugin on an appropriate selection.
                                            </p>
                                                        <pre>&lt;script&gt;
                                                            $(window).load(function() {
                                                                $(".acc-wizard").accwizard();
                                                            });
                                                            &lt;/script&gt;
                                                        </pre>
                                            <p>
                                                The default options are probably fine for most uses, but there are many customizations you can use when you activate the wizard. Check out the documentation on
                                                <a href="https://github.com/sathomas/acc-wizard">github</a>
                                                for the details.
                                            </p>
                                            <div class="acc-wizard-step"></div>
                                        </form>
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#adjusthtml --> </div>
                            <!-- /.panel.panel-default -->
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#viewpage" data-parent="#accordion-demo" data-toggle="collapse">Test Your Page</a>
                                    </h4>
                                </div>
                                <div id="viewpage" class="panel-collapse collapse" style="height: 36.400001525878906px;">
                                    <div class="panel-body">
                                        <form id="form-viewpage">
                                            <p>
                                                Naturally, the last thing you'll want to do is test your page with the accordion wizard. Once you've confirmed that it's working as expected, release it on the world. Your users will definitely appreciate the feedback and guidance it gives to multi-step and complex tasks on your web site.
                                            </p>
                                            <div class="acc-wizard-step"></div>
                                        </form>
                                    </div>
                                    <!--/.panel-body --> </div>
                                <!-- /#adjusthtml --> </div>
                            <!-- /.panel.panel-default --> </div>
                    </div>
                </div>
            {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!--main content ends--> </section>
            <!-- content --> 
    @stop

{{-- page level scripts --}}
@section('footer_scripts')
    
    <script src="{{ asset('assets/vendors/acc-wizard/acc-wizard.min.js') }}" ></script>
    <script src="{{ asset('assets/js/pages/accordionformwizard.js') }}"  type="text/javascript"></script>
    
@stop
