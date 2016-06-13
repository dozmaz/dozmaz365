{{ stylesheet_link('icheck/skins/flat/green.css') }}
{{ javascript_include('icheck/icheck.min.js') }}

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title navbar-fixed-top" style="border: 0;">
                    <a href="#" class="site_title"><i class="fa fa-line-chart"></i>
                        <span>Pronosticos</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        {{ fotoPerfil }}
                    </div>
                    <div class="profile_info">
                        <span>Bienvenido,</span>
                        <h2>{{ auth.getName() }}</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br/>
                <div class="clearfix"></div>
                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                            <li>
                                {%- set menus = [
                                '<i class="fa fa-soccer-ball-o"></i> Pron&oacute;sticos': 'pronosticos/index',
                                '<i class="fa fa-bar-chart-o"></i> Puntuaciones usuarios': 'pronosticos/puntuaciones',
                                '<i class="fa fa-table"></i> Resultados partidos': 'pronosticos/resultados'
                                ] -%}
                                {%- for key, value in menus %}
                                {% if value == dispatcher.getControllerName() %}
                            <li class="active">{{ link_to(value, key) }}</li>
                            {% else %}
                                <li>{{ link_to(value, key) }}</li>
                            {% endif %}
                            {%- endfor -%}
                            </li>
                        </ul>
                    </div>
                    {% if auth.getProfile() == "ADMINISTRADOR" %}
                    <div class="menu_section">
                        <h3>Configuraci&oacute;n</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-users"></i> Usuarios <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    {%- set menus = [
                                    'Usuarios': 'usuarios',
                                    'Perfiles': 'perfiles',
                                    'Permisos': 'permisos'
                                    ] -%}
                                    {%- for key, value in menus %}
                                        {% if value == dispatcher.getControllerName() %}
                                            <li class="active">{{ link_to(value, key) }}</li>
                                        {% else %}
                                            <li>{{ link_to(value, key) }}</li>
                                        {% endif %}
                                    {%- endfor -%}
                                </ul>
                            </li>
                            <li><a><i class="fa fa-gears"></i> Partidos <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    {%- set menus = [
                                    'Campeonatos': 'campeonatos',
                                    'Fases': 'fases',
                                    'Equipos': 'equipos',
                                    'Partidos': 'partidos'
                                    ] -%}
                                    {%- for key, value in menus %}
                                        {% if value == dispatcher.getControllerName() %}
                                            <li class="active">{{ link_to(value, key) }}</li>
                                        {% else %}
                                            <li>{{ link_to(value, key) }}</li>
                                        {% endif %}
                                    {%- endfor -%}
                                </ul>
                            </li>
                            <li>
                                {{ link_to('session/logout', '<i class="fa fa-sign-out"></i> Cerrar sesi&oacute;n') }}
                            </li>
                        </ul>
                    </div>
                    {% endif %}
                </div>
                <!-- /sidebar menu -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav navbar navbar-fixed-top">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="col-md-1 col-xs-3 col-sm-3 col-lg-1 row">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                    </div>
                    <div class="col-md-8 col-xs-6 col-sm-8 col-lg-9 titulo row">
                        <h2>{{ title }}</h2>
                    </div>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->


        <!-- page content -->
        <div class="right_col custom_page_content" role="main">
            {{ content() }}
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Sistema de pronosticos
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>
</body>