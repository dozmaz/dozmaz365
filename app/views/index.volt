<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Pronosticos</title>

    {#{{ stylesheet_link('css/bootstrap.min.css') }}#}
    {{ stylesheet_link('css/main.css') }}
    {{ stylesheet_link('font-awesome/css/font-awesome.min.css') }}
</head>
{{ content() }}

<!-- Essential javascripts for application to work-->
{{ javascript_include('js/jquery-3.3.1.min.js') }}
{{ javascript_include('js/popper.min.js') }}
{{ javascript_include('js/bootstrap.min.js') }}
{{ javascript_include('js/main.js') }}
<!-- The javascript plugin to display page loading on top-->
{{ javascript_include('js/plugins/pace.min.js') }}

{#{{ javascript_include('js/custom.js') }}#}
{#{{ javascript_include('validator-master/validator.min.js') }}#}
</html>
