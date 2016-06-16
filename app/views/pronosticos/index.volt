{{ content() }}
{% for fecha in fechas %}
    {% if pronosticos.permitidoModificar(fecha.FECHA_PARTIDO ~ " 23:59:59") %}
    {% if fechaSeleccionada == fecha.FECHA_PARTIDO %}
        {{ link_to("pronosticos/index/" ~ fecha.FECHA_PARTIDO, fecha.FECHA_PARTIDO, 'class':'btn btn-primary') }}
    {% else %}
        {{ link_to("pronosticos/index/" ~ fecha.FECHA_PARTIDO, fecha.FECHA_PARTIDO, 'class':'btn btn-default') }}
    {% endif %}
    {% endif %}
{% else %}
    No hay partidos pendientes
{% endfor %}

{{ form("pronosticos/save", "id":"formBoleta", "class":"form-horizontal form-label-left") }}
<input type="hidden" name="fechaSeleccionada" value="{{ fechaSeleccionada }}">
{% for partido in partidosDelDia %}
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 class="text-{% if partido.FASES.NOMBRE == "Penales" %}danger{% endif %}">{{ partido.FECHA }}
                        <span class="btn btn-round btn-{% if partido.FASES.NOMBRE != "Penales" %}dark{% else %}danger{% endif %}" style="color: #FFFFFF;">{{ partido.FASES.NOMBRE }}</span>
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br>
                    <div class="item form-group">
                        <div class="col-md-2 col-sm-2 col-xs-2 text-right">
                            <div class="row"><br/>
                                <h4>{{ partido.getLocal().NOMBRE }}</h4></div>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1 text-center">
                            <div class="row">
                                <img src="data:image/jpeg;base64,{{ base64_encode(partido.getLocal().LOGO) }}"
                                     class="img-thumbnail"/></div>
                        </div>
                        <div class="col-md-1 col-sm-2 col-xs-2 text-center">
                            <br/>
                                {% if pronosticos.permitidoModificar(partido.FECHA) %}
                                    {{ numeric_field("LOCAL_" ~ partido.PARTIDOS_ID ~"_" ~ partido.LOCAL,  'min':"0", 'max':"100", 'class':"form-control input-lg", "required":"required") }}
                                {% else %}
                                    {{ text_field("LOCAL_" ~ partido.PARTIDOS_ID ~"_" ~ partido.LOCAL, 'readonly':"readonly", 'class':"form-control input-lg", 'id':"", 'name':"") }}
                                {% endif %}
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1 text-center"><br/>
                            <h4>VS</h4>
                        </div>
                        <div class="col-md-1 col-sm-2 col-xs-2 text-center">
                            <br/>
                                {% if pronosticos.permitidoModificar(partido.FECHA) %}
                                    {{ numeric_field("VISITANTE_" ~ partido.PARTIDOS_ID ~"_" ~ partido.VISITANTE, 'min':"0", 'max':"100", 'class':"form-control input-lg", "required":"required") }}
                                {% else %}
                                    {{ text_field("VISITANTE_" ~ partido.PARTIDOS_ID ~"_" ~ partido.VISITANTE, 'readonly':"readonly", 'class':"form-control input-lg", 'id':"", 'name':"") }}
                                {% endif %}
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1 text-center">
                            <div class="row">
                                <img src="data:image/jpeg;base64,{{ base64_encode(partido.getVisitante().LOGO) }}"
                                     class="img-thumbnail img-responsive"/></div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2 text-left">
                            <div class="row"><br/>
                                <h4>{{ partido.getVisitante().NOMBRE }}</h4></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {% if loop.last %}
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel text-center">
                    <button type="submit" class="btn btn-success">Registrar pronósticos para la
                        fecha {{ fechaSeleccionada }}</button>
                </div>
            </div>
        </div>
    {% endif %}
{% endfor %}
{{ endform() }}

<style type="text/css">
    .input-lg{
        padding: 0px;
        text-align: center;
    }
</style>