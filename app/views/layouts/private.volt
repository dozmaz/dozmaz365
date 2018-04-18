{#{{ stylesheet_link('icheck/skins/flat/green.css') }}#}
{#{{ javascript_include('icheck/icheck.min.js') }}#}
<body class="app sidebar-mini rtl">
<!-- Navbar-->
<header class="app-header"><a class="app-header__logo" href="index.html"><i class="fa fa-lg fa-fw fa-futbol-o"></i>
        Pronósticos</a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"
                                    aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
        <li class="dropdown">
            {{ link_to('session/logout', '<i class="fa fa-sign-out fa-lg"></i>','title':'Salir','alt':'Salir', 'class':"app-nav__item" ) }}
        </li>

    </ul>
</header>
<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar"
                                        src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg"
                                        alt="User Image">
        <div>
            <p class="app-sidebar__user-name">{{ auth.getName() }}</p>
            <p class="app-sidebar__user-designation">{{ auth.getProfile() }}</p>
        </div>
    </div>
    <ul class="app-menu">
        <li>{% if "index" == dispatcher.getControllerName() %}
                {{ link_to("index", '<i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Inicio</span>', 'class':'app-menu__item active') }}
            {% else %}
                {{ link_to("index", '<i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Inicio</span>', 'class':'app-menu__item') }}
            {% endif %}
        </li>

        {%- set menus = [
            '<i class="fa fa-soccer-ball-o"></i>&nbsp; Pron&oacute;sticos': 'pronosticos/index',
            '<i class="fa fa-bar-chart-o"></i>&nbsp; Puntuaciones usuarios': 'pronosticos/puntuaciones',
            '<i class="fa fa-table"></i>&nbsp; Resultados partidos': 'pronosticos/resultados'
        ] -%}
        {%- for key, value in menus %}
            {% if value == dispatcher.getControllerName()~"/"~dispatcher.getActionName() %}
                <li>{{ link_to(value, key, 'class':'app-menu__item active') }}</li>
            {% else %}
                <li>{{ link_to(value, key, 'class':'app-menu__item') }}</li>
            {% endif %}
        {%- endfor -%}
        {% if auth.getProfile() == "ADMINISTRADOR" %}
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-gears"></i><span
                            class="app-menu__label">Configuraci&oacute;n</span><i
                            class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    {%- set menus = [
                        'Usuarios': 'usuarios',
                        'Perfiles': 'perfiles',
                        'Permisos': 'permisos'
                    ] -%}
                    {%- for key, value in menus %}
                        {% if value == dispatcher.getControllerName() %}
                            <li>{{ link_to(value, key, 'class':'treeview-item active') }}</li>
                        {% else %}
                            <li>{{ link_to(value, key, 'class':'treeview-item') }}</li>
                        {% endif %}
                    {%- endfor -%}
                </ul>
            </li>
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                            class="app-menu__icon fa fa-chevron-down"></i><span
                            class="app-menu__label">Partidos</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                    {%- set menus = [
                        'Campeonatos': 'campeonatos',
                        'Fases': 'fases',
                        'Equipos': 'equipos',
                        'Partidos': 'partidos'
                    ] -%}
                    {%- for key, value in menus %}
                        {% if value == dispatcher.getControllerName() %}
                            <li>{{ link_to(value, key, 'class':'treeview-item active') }}</li>
                        {% else %}
                            <li>{{ link_to(value, key, 'class':'treeview-item') }}</li>
                        {% endif %}
                    {%- endfor -%}
                </ul>
            </li>
        {% endif %}

    </ul>
</aside>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-futbol-o"></i> {{ title }}</h1>
        </div>
    </div>

    {{ content() }}

</main>

</body>